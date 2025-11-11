<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentNotificationService;
use Illuminate\Validation\ValidationException;

class PaymentRequestController extends Controller
{
    public function index()
    {
        $paymentRequests = Transaction::where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)
            ->with('agency.user')
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_requests' => Transaction::where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)->count(),
            'pending_requests' => Transaction::where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)->where('status', Transaction::STATUS_PENDING)->count(),
            'approved_requests' => Transaction::where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)->where('status', Transaction::STATUS_COMPLETED)->count(),
            'rejected_requests' => Transaction::where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)->where('status', Transaction::STATUS_FAILED)->count(),
            'total_amount' => Transaction::where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)->where('status', Transaction::STATUS_PENDING)->sum('amount'),
        ];
        
        return view('admin.payment-requests', compact('paymentRequests', 'stats'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('agency.user')
            ->where('id', $id)
            ->where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Demande non trouvée'], 404);
        }

        $metadata = $transaction->metadata ?? [];
        $formattedAmount = number_format($transaction->amount, 2, ',', ' ');
        $formattedRib = isset($metadata['rib_number'])
            ? trim(chunk_split(preg_replace('/\s+/', '', $metadata['rib_number']), 4, ' '))
            : '—';

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaction->id,
                'agency_name' => $transaction->agency->agency_name,
                'amount' => $transaction->amount,
                'amount_formatted' => $formattedAmount,
                'bank_name' => $metadata['bank_name'] ?? '—',
                'rib_number' => $formattedRib,
                'account_holder' => $metadata['account_holder'] ?? '—',
                'notes' => $metadata['notes'] ?? null,
                'admin_notes' => $metadata['admin_notes'] ?? null,
                'transfer_reference' => $metadata['transfer_reference'] ?? null,
                'rejection_reason' => $metadata['rejection_reason'] ?? null,
                'approved_by_name' => $metadata['approved_by_name'] ?? null,
                'rejected_by_name' => $metadata['rejected_by_name'] ?? null,
                'status' => ucfirst($transaction->status),
                'requested_at' => $transaction->created_at->format('d/m/Y H:i'),
                'processed_at' => $transaction->processed_at ? $transaction->processed_at->format('d/m/Y H:i') : null,
            ],
        ]);
    }
    
    public function approve(Request $request, $id)
    {
        $transaction = Transaction::with('agency')
            ->where('id', $id)
            ->where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)
            ->where('status', Transaction::STATUS_PENDING)
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Demande non trouvée'], 404);
        }

        $request->validate([
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $agency = $transaction->agency()->lockForUpdate()->first();

            $metadata = $transaction->metadata ?? [];
            $metadata['approved_at'] = now()->toISOString();
            $metadata['approved_by'] = auth()->id();
            $metadata['approved_by_name'] = auth()->user()->name;
            if ($request->filled('reference')) {
                $metadata['transfer_reference'] = $request->reference;
            }
            if ($request->filled('notes')) {
                $metadata['admin_notes'] = $request->notes;
            }

            $transaction->update([
                'status' => Transaction::STATUS_COMPLETED,
                'processed_at' => now(),
                'metadata' => $metadata,
            ]);

            // Update agency pending earnings and last payout timestamp
            $agency->pending_earnings = max(0, ($agency->pending_earnings ?? 0) - $transaction->amount);
            $agency->last_payout_at = now();
            $agency->save();

            DB::commit();

            PaymentNotificationService::notifyAgencyPayoutProcessed($transaction->fresh());

            return response()->json([
                'success' => true,
                'message' => 'Demande de paiement approuvée avec succès',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Payment request approval failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'approbation: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $transaction = Transaction::with('agency')
            ->where('id', $id)
            ->where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)
            ->where('status', Transaction::STATUS_PENDING)
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Demande non trouvée'], 404);
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::transaction(function () use ($transaction, $request) {
                $agency = $transaction->agency()->lockForUpdate()->first();

                $metadata = $transaction->metadata ?? [];
                $metadata['rejected_at'] = now()->toISOString();
                $metadata['rejected_by'] = auth()->id();
                $metadata['rejected_by_name'] = auth()->user()->name;
                $metadata['rejection_reason'] = $request->reason;

                $transaction->update([
                    'status' => Transaction::STATUS_FAILED,
                    'processed_at' => now(),
                    'metadata' => $metadata,
                ]);

                // Return funds to agency balance and reduce pending earnings
                $agency->balance = ($agency->balance ?? 0) + $transaction->amount;
                $agency->pending_earnings = max(0, ($agency->pending_earnings ?? 0) - $transaction->amount);
                $agency->save();
            });

            PaymentNotificationService::notifyAgencyPayoutRejected($transaction->fresh());

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