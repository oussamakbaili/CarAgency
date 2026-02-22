<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Rental;
use App\Models\Agency;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;
        
        // Financial overview
        $overview = [
            'total_earnings' => $agency->total_earnings ?? 0,
            'pending_earnings' => $agency->pending_earnings ?? 0,
            'current_balance' => $agency->balance ?? 0,
            'monthly_revenue' => Rental::where('rentals.agency_id', $agency->id)
                ->whereIn('rentals.status', ['active', 'completed'])
                ->whereMonth('rentals.created_at', Carbon::now()->month)
                ->sum('rentals.total_price'),
            'commission_rate' => $agency->commission_rate ?? 0,
        ];
        
        // Recent transactions
        $recentTransactions = Transaction::where('transactions.agency_id', $agency->id)
            ->latest()
            ->take(10)
            ->get();
        
        // Revenue trends (last 6 months)
        $revenueTrends = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->select(
                DB::raw('MONTH(rentals.created_at) as month'),
                DB::raw('YEAR(rentals.created_at) as year'),
                DB::raw('SUM(rentals.total_price) as revenue')
            )
            ->where('rentals.created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        return view('agence.finance.index', compact('overview', 'recentTransactions', 'revenueTrends'));
    }
    
    public function payments(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $query = Transaction::where('transactions.agency_id', $agency->id)
            ->with(['rental.user', 'rental.car']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('rental.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('period')) {
            $period = $request->period;
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        $payments = $query->latest()->paginate(20);
        
        return view('agence.finance.payments', compact('payments'));
    }
    
    public function commissions(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $query = Transaction::where('transactions.agency_id', $agency->id)
            ->whereIn('transactions.type', ['commission', 'revenue', 'fee'])
            ->with(['rental.user', 'rental.car']);
        
        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('amount_range')) {
            $range = $request->amount_range;
            switch ($range) {
                case '0-100':
                    $query->whereBetween('amount', [0, 100]);
                    break;
                case '100-500':
                    $query->whereBetween('amount', [100, 500]);
                    break;
                case '500-1000':
                    $query->whereBetween('amount', [500, 1000]);
                    break;
                case '1000+':
                    $query->where('amount', '>', 1000);
                    break;
            }
        }
        
        if ($request->filled('period')) {
            $period = $request->period;
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        $commissions = $query->latest()->paginate(20);
        
        return view('agence.finance.commissions', compact('commissions'));
    }
    
    public function payouts(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Build query for withdrawal transactions
        $query = Transaction::where('transactions.agency_id', $agency->id)
            ->whereIn('transactions.type', ['withdrawal', 'withdrawal_request'])
            ->latest();
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('period')) {
            $period = $request->period;
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        if ($request->filled('method')) {
            $query->whereJsonContains('metadata->payment_method', $request->method);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%");
            });
        }
        
        $payouts = $query->paginate(20);
        
        // Calculate statistics
        $stats = [
            'total_payouts' => Transaction::where('agency_id', $agency->id)
                ->whereIn('type', ['withdrawal', 'withdrawal_request'])
                ->count(),
            'completed_payouts' => Transaction::where('agency_id', $agency->id)
                ->whereIn('type', ['withdrawal', 'withdrawal_request'])
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_payouts' => Transaction::where('agency_id', $agency->id)
                ->whereIn('type', ['withdrawal', 'withdrawal_request'])
                ->where('status', 'pending')
                ->sum('amount'),
            'this_month' => Transaction::where('agency_id', $agency->id)
                ->whereIn('type', ['withdrawal', 'withdrawal_request'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->sum('amount'),
        ];
        
        // Get last payout date
        $lastPayout = Transaction::where('agency_id', $agency->id)
            ->whereIn('type', ['withdrawal', 'withdrawal_request'])
            ->where('status', 'completed')
            ->latest()
            ->first();
        
        return view('agence.finance.payouts', compact('payouts', 'stats', 'lastPayout'));
    }
    
    public function exportPayouts(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Build query for withdrawal transactions (same as payouts method)
        $query = Transaction::where('transactions.agency_id', $agency->id)
            ->whereIn('transactions.type', ['withdrawal', 'withdrawal_request'])
            ->latest();
        
        // Apply filters (same as payouts method)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('period')) {
            $period = $request->period;
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        if ($request->filled('method')) {
            $query->whereJsonContains('metadata->payment_method', $request->method);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%");
            });
        }
        
        // Handle single payout export
        if ($request->filled('single')) {
            $query->where('id', $request->single);
        }
        
        $payouts = $query->get();
        
        $filename = 'payouts_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($payouts) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Montant (DH)',
                'Méthode',
                'Statut',
                'Description',
                'Date de Création',
                'Date de Traitement',
                'Notes'
            ]);
            
            // CSV data
            foreach ($payouts as $payout) {
                fputcsv($file, [
                    $payout->id,
                    number_format($payout->amount, 2),
                    ucfirst(str_replace('_', ' ', $payout->metadata['payment_method'] ?? 'N/A')),
                    ucfirst($payout->status),
                    $payout->description,
                    $payout->created_at->format('d/m/Y H:i'),
                    $payout->processed_at ? $payout->processed_at->format('d/m/Y H:i') : 'N/A',
                    $payout->metadata['notes'] ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function getPayoutDetails($id)
    {
        $agency = auth()->user()->agency;
        
        $payout = Transaction::where('id', $id)
            ->where('agency_id', $agency->id)
            ->whereIn('type', ['withdrawal', 'withdrawal_request'])
            ->first();
            
        if (!$payout) {
            return response()->json(['success' => false, 'message' => 'Paiement non trouvé'], 404);
        }
        
        $data = [
            'id' => $payout->id,
            'amount' => number_format($payout->amount, 2),
            'method' => ucfirst(str_replace('_', ' ', $payout->metadata['payment_method'] ?? 'N/A')),
            'status' => ucfirst($payout->status),
            'description' => $payout->description,
            'created_at' => $payout->created_at->format('d/m/Y H:i'),
            'processed_at' => $payout->processed_at ? $payout->processed_at->format('d/m/Y H:i') : 'N/A',
            'notes' => $payout->metadata['notes'] ?? 'N/A',
            'balance_before' => number_format($payout->balance_before, 2),
            'balance_after' => number_format($payout->balance_after, 2),
        ];
        
        return response()->json(['success' => true, 'data' => $data]);
    }
    
    public function reports()
    {
        $agency = auth()->user()->agency;
        
        // Financial reports data
        $reports = [
            'monthly_revenue' => $this->getMonthlyRevenue($agency),
            'top_performing_cars' => $this->getTopPerformingCars($agency),
            'customer_analysis' => $this->getCustomerAnalysis($agency),
        ];
        
        return view('agence.finance.reports', compact('reports'));
    }
    
    public function export()
    {
        $agency = auth()->user()->agency;
        
        // Get financial data for export
        $transactions = Transaction::where('transactions.agency_id', $agency->id)
            ->with('rental')
            ->latest()
            ->get();
        
        $filename = 'rapport_financier_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Date',
                'Type',
                'Description',
                'Montant (DH)',
                'Statut',
                'Réservation ID'
            ]);
            
            // CSV data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('d/m/Y H:i'),
                    $transaction->type === 'income' ? 'Revenu' : 'Dépense',
                    $transaction->description,
                    $transaction->amount,
                    ucfirst($transaction->status),
                    $transaction->rental_id ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function requestPayment(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $request->validate([
            'amount' => 'required|numeric|min:100|max:' . ($agency->balance ?? 0),
            'payment_method' => 'required|in:bank_transfer,check,cash',
            'notes' => 'nullable|string|max:500'
        ]);
        
        try {
            // Create payment request transaction
            $transaction = Transaction::create([
                'agency_id' => $agency->id,
                'rental_id' => null,
                'type' => 'withdrawal_request',
                'amount' => $request->amount,
                'balance_before' => $agency->balance ?? 0,
                'balance_after' => $agency->balance ?? 0, // Balance doesn't change until approved
                'description' => 'Demande de paiement - ' . $request->payment_method,
                'status' => 'pending',
                'metadata' => [
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                    'requested_at' => now()->toISOString()
                ],
                'processed_at' => null
            ]);
            
            // Log the request
            \Log::info('Payment request created', [
                'agency_id' => $agency->id,
                'transaction_id' => $transaction->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Demande de paiement créée avec succès'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Payment request failed', [
                'agency_id' => $agency->id,
                'error' => $e->getMessage(),
                'amount' => $request->amount
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la demande: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getPaymentDetails($id)
    {
        $agency = auth()->user()->agency;
        
        $payment = Transaction::where('id', $id)
            ->where('agency_id', $agency->id)
            ->with(['rental.user', 'rental.car'])
            ->first();
            
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
        
        $typeLabels = [
            'rental_payment' => 'Paiement Location',
            'withdrawal' => 'Retrait',
            'refund' => 'Remboursement',
            'commission' => 'Commission',
            'withdrawal_request' => 'Demande de Retrait'
        ];
        
        $statusColors = [
            'completed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-blue-100 text-blue-800'
        ];
        
        return response()->json([
            'id' => $payment->id,
            'type' => $payment->type,
            'type_label' => $typeLabels[$payment->type] ?? ucfirst($payment->type),
            'amount' => number_format($payment->amount, 0),
            'status' => ucfirst($payment->status),
            'status_color' => $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800',
            'created_at' => $payment->created_at->format('d/m/Y H:i'),
            'processed_at' => $payment->processed_at ? $payment->processed_at->format('d/m/Y H:i') : null,
            'client_name' => $payment->rental->user->name ?? 'N/A',
            'client_email' => $payment->rental->user->email ?? 'N/A',
            'rental_id' => $payment->rental_id,
            'vehicle' => $payment->rental && $payment->rental->car ? 
                $payment->rental->car->brand . ' ' . $payment->rental->car->model : null,
            'description' => $payment->description
        ]);
    }
    
    public function approvePayment($id)
    {
        $agency = auth()->user()->agency;
        
        $payment = Transaction::where('id', $id)
            ->where('agency_id', $agency->id)
            ->first();
            
        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }
        
        if ($payment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Payment is not pending'], 400);
        }
        
        $payment->update([
            'status' => 'completed',
            'processed_at' => now()
        ]);
        
        // Log the approval
        \Log::info('Payment approved', [
            'agency_id' => $agency->id,
            'payment_id' => $payment->id,
            'amount' => $payment->amount
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Paiement approuvé avec succès'
        ]);
    }
    
    public function refundPayment(Request $request, $id)
    {
        $agency = auth()->user()->agency;
        
        $payment = Transaction::where('id', $id)
            ->where('agency_id', $agency->id)
            ->first();
            
        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $payment->amount,
            'reason' => 'required|in:cancellation,vehicle_issue,service_issue,other',
            'notes' => 'nullable|string|max:500'
        ]);
        
        // Create refund transaction
        $refund = Transaction::create([
            'agency_id' => $agency->id,
            'type' => 'refund',
            'amount' => $request->amount,
            'description' => 'Remboursement - ' . $request->reason,
            'status' => 'completed',
            'metadata' => [
                'original_payment_id' => $payment->id,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'refunded_at' => now()
            ]
        ]);
        
        // Update original payment status
        $payment->update(['status' => 'refunded']);
        
        // Log the refund
        \Log::info('Payment refunded', [
            'agency_id' => $agency->id,
            'original_payment_id' => $payment->id,
            'refund_id' => $refund->id,
            'amount' => $request->amount,
            'reason' => $request->reason
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Remboursement traité avec succès'
        ]);
    }
    
    public function exportPayments(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Apply same filters as payments method
        $query = Transaction::where('transactions.agency_id', $agency->id)
            ->with(['rental.user', 'rental.car']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('rental.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('period')) {
            $period = $request->period;
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        $payments = $query->latest()->get();
        
        $filename = 'paiements_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID Transaction',
                'Type',
                'Montant (DH)',
                'Statut',
                'Client',
                'Email Client',
                'Réservation ID',
                'Véhicule',
                'Date',
                'Description'
            ]);
            
            // CSV data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->type,
                    $payment->amount,
                    $payment->status,
                    $payment->rental->user->name ?? 'N/A',
                    $payment->rental->user->email ?? 'N/A',
                    $payment->rental_id ?? 'N/A',
                    $payment->rental && $payment->rental->car ? 
                        $payment->rental->car->brand . ' ' . $payment->rental->car->model : 'N/A',
                    $payment->created_at->format('d/m/Y H:i'),
                    $payment->description
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function getCommissionDetails($id)
    {
        $agency = auth()->user()->agency;
        
        $commission = Transaction::where('id', $id)
            ->where('agency_id', $agency->id)
            ->whereIn('type', ['commission', 'revenue', 'fee'])
            ->with(['rental.user', 'rental.car'])
            ->first();
            
        if (!$commission) {
            return response()->json(['error' => 'Commission not found'], 404);
        }
        
        $typeLabels = [
            'revenue' => 'Revenus',
            'fee' => 'Frais Plateforme',
            'commission' => 'Commission'
        ];
        
        $typeColors = [
            'revenue' => 'bg-green-100 text-green-800',
            'fee' => 'bg-red-100 text-red-800',
            'commission' => 'bg-blue-100 text-blue-800'
        ];
        
        $statusColors = [
            'completed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800'
        ];
        
        // Calculate financial breakdown
        $grossAmount = $commission->metadata['gross_amount'] ?? $commission->amount;
        $platformFee = $commission->metadata['platform_fee'] ?? 0;
        $netAmount = $commission->metadata['net_amount'] ?? ($grossAmount - $platformFee);
        
        $breakdown = [
            ['label' => 'Montant Brut', 'amount' => number_format($grossAmount, 0)],
            ['label' => 'Commission Plateforme', 'amount' => number_format($platformFee, 0)],
            ['label' => 'Montant Net', 'amount' => number_format($netAmount, 0)]
        ];
        
        return response()->json([
            'id' => $commission->id,
            'type' => $commission->type,
            'type_label' => $typeLabels[$commission->type] ?? ucfirst($commission->type),
            'type_color' => $typeColors[$commission->type] ?? 'bg-gray-100 text-gray-800',
            'amount' => number_format($commission->amount, 0),
            'rate' => number_format($commission->metadata['rate'] ?? 0, 1),
            'status' => ucfirst($commission->status),
            'status_color' => $statusColors[$commission->status] ?? 'bg-gray-100 text-gray-800',
            'created_at' => $commission->created_at->format('d/m/Y H:i'),
            'processed_at' => $commission->processed_at ? $commission->processed_at->format('d/m/Y H:i') : null,
            'gross_amount' => number_format($grossAmount, 0),
            'platform_fee' => number_format($platformFee, 0),
            'net_amount' => number_format($netAmount, 0),
            'rental_id' => $commission->rental_id,
            'description' => $commission->description,
            'breakdown' => $breakdown
        ]);
    }
    
    public function downloadCommissionReport($id)
    {
        $agency = auth()->user()->agency;
        
        $commission = Transaction::where('id', $id)
            ->where('agency_id', $agency->id)
            ->whereIn('type', ['commission', 'revenue', 'fee'])
            ->with(['rental.user', 'rental.car'])
            ->first();
            
        if (!$commission) {
            return response()->json(['error' => 'Commission not found'], 404);
        }
        
        // Generate PDF report (simplified for now)
        $filename = 'rapport_commission_' . $commission->id . '_' . now()->format('Y-m-d') . '.pdf';
        
        // For now, return a simple text response
        // In a real application, you would generate a PDF
        return response()->streamDownload(function() use ($commission) {
            echo "RAPPORT DE COMMISSION\n";
            echo "====================\n\n";
            echo "ID Commission: #{$commission->id}\n";
            echo "Type: " . ucfirst($commission->type) . "\n";
            echo "Montant: " . number_format($commission->amount, 0) . " MAD\n";
            echo "Date: " . $commission->created_at->format('d/m/Y H:i') . "\n";
            echo "Description: " . $commission->description . "\n\n";
            echo "Généré le: " . now()->format('d/m/Y H:i') . "\n";
        }, $filename, ['Content-Type' => 'text/plain']);
    }
    
    public function exportCommissions(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Apply same filters as commissions method
        $query = Transaction::where('transactions.agency_id', $agency->id)
            ->whereIn('transactions.type', ['commission', 'revenue', 'fee'])
            ->with(['rental.user', 'rental.car']);
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('amount_range')) {
            $range = $request->amount_range;
            switch ($range) {
                case '0-100':
                    $query->whereBetween('amount', [0, 100]);
                    break;
                case '100-500':
                    $query->whereBetween('amount', [100, 500]);
                    break;
                case '500-1000':
                    $query->whereBetween('amount', [500, 1000]);
                    break;
                case '1000+':
                    $query->where('amount', '>', 1000);
                    break;
            }
        }
        
        if ($request->filled('period')) {
            $period = $request->period;
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        $commissions = $query->latest()->get();
        
        $filename = 'commissions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($commissions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID Commission',
                'Type',
                'Montant (MAD)',
                'Taux (%)',
                'Statut',
                'Réservation ID',
                'Client',
                'Véhicule',
                'Date',
                'Description'
            ]);
            
            // CSV data
            foreach ($commissions as $commission) {
                fputcsv($file, [
                    $commission->id,
                    $commission->type,
                    $commission->amount,
                    $commission->metadata['rate'] ?? 0,
                    $commission->status,
                    $commission->rental_id ?? 'N/A',
                    $commission->rental->user->name ?? 'N/A',
                    $commission->rental && $commission->rental->car ? 
                        $commission->rental->car->brand . ' ' . $commission->rental->car->model : 'N/A',
                    $commission->created_at->format('d/m/Y H:i'),
                    $commission->description
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function getMonthlyRevenue($agency)
    {
        return Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->select(
                DB::raw('MONTH(rentals.created_at) as month'),
                DB::raw('YEAR(rentals.created_at) as year'),
                DB::raw('SUM(rentals.total_price) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->where('rentals.created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }
    
    private function getTopPerformingCars($agency)
    {
        return Car::where('cars.agency_id', $agency->id)
            ->withCount(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }])
            ->withSum(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }], 'total_price')
            ->orderBy('rentals_sum_total_price', 'desc')
            ->take(10)
            ->get();
    }
    
    private function getCustomerAnalysis($agency)
    {
        return [
            'total_customers' => Client::whereHas('rentals', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->count(),
            'repeat_customers' => Client::whereHas('rentals', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->havingRaw('COUNT(rentals.id) > 1')->count(),
            'average_rental_value' => Rental::where('rentals.agency_id', $agency->id)
                ->whereIn('rentals.status', ['active', 'completed'])
                ->avg('rentals.total_price'),
        ];
    }
}
