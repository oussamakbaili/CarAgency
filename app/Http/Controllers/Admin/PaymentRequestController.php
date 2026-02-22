<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentRequestController extends Controller
{
    public function index()
    {
        $paymentRequests = Transaction::where('type', 'withdrawal_request')
            ->with('agency.user')
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_requests' => Transaction::where('type', 'withdrawal_request')->count(),
            'pending_requests' => Transaction::where('type', 'withdrawal_request')->where('status', 'pending')->count(),
            'approved_requests' => Transaction::where('type', 'withdrawal_request')->where('status', 'completed')->count(),
            'rejected_requests' => Transaction::where('type', 'withdrawal_request')->where('status', 'failed')->count(),
            'total_amount' => Transaction::where('type', 'withdrawal_request')->where('status', 'pending')->sum('amount'),
        ];
        
        return view('admin.payment-requests', compact('paymentRequests', 'stats'));
    }
    
    public function approve(Request $request, $id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('type', 'withdrawal_request')
            ->where('status', 'pending')
            ->first();
            
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Demande non trouvée'], 404);
        }
        
        DB::beginTransaction();
        
        try {
            // Update transaction status
            $transaction->update([
                'status' => 'completed',
                'processed_at' => now(),
                'metadata' => array_merge($transaction->metadata ?? [], [
                    'approved_at' => now()->toISOString(),
                    'approved_by' => auth()->id()
                ])
            ]);
            
            // Update agency balance
            $agency = $transaction->agency;
            $agency->update([
                'balance' => $agency->balance - $transaction->amount
            ]);
            
            // Log the approval
            \Log::info('Payment request approved', [
                'transaction_id' => $transaction->id,
                'agency_id' => $agency->id,
                'amount' => $transaction->amount,
                'approved_by' => auth()->id()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Demande de paiement approuvée avec succès'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Payment request approval failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'approbation: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function reject(Request $request, $id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('type', 'withdrawal_request')
            ->where('status', 'pending')
            ->first();
            
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Demande non trouvée'], 404);
        }
        
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        try {
            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'processed_at' => now(),
                'metadata' => array_merge($transaction->metadata ?? [], [
                    'rejected_at' => now()->toISOString(),
                    'rejected_by' => auth()->id(),
                    'rejection_reason' => $request->reason
                ])
            ]);
            
            // Log the rejection
            \Log::info('Payment request rejected', [
                'transaction_id' => $transaction->id,
                'agency_id' => $transaction->agency_id,
                'amount' => $transaction->amount,
                'reason' => $request->reason,
                'rejected_by' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Demande de paiement rejetée avec succès'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Payment request rejection failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rejet: ' . $e->getMessage()
            ], 500);
        }
    }
}