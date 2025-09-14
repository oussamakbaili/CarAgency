<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Agency;
use App\Models\Rental;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function dashboard()
    {
        // Overall financial statistics
        $stats = [
            'totalRevenue' => Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->sum('amount'),
            'monthlyRevenue' => Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
            'totalCommissions' => Transaction::where('type', Transaction::TYPE_COMMISSION)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->sum('amount'),
            'pendingPayouts' => Agency::sum('pending_earnings'),
            'totalAgencies' => Agency::where('status', 'approved')->count(),
            'activeRentals' => Rental::where('status', 'active')->count(),
        ];

        // Revenue trends (last 12 months)
        $revenueTrends = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            
            $revenueTrends[] = $revenue;
            $labels[] = $date->format('M Y');
        }

        // Top performing agencies
        $topAgencies = Agency::where('status', 'approved')
            ->withSum(['transactions' => function($query) {
                $query->where('type', Transaction::TYPE_RENTAL_PAYMENT)
                      ->where('status', Transaction::STATUS_COMPLETED);
            }], 'amount')
            ->orderBy('transactions_sum_amount', 'desc')
            ->take(10)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['agency', 'rental'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.finance.dashboard', compact(
            'stats', 
            'revenueTrends', 
            'labels', 
            'topAgencies', 
            'recentTransactions'
        ));
    }

    public function commissions(Request $request)
    {
        $query = Agency::where('status', 'approved');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('agency_name', 'like', '%' . $search . '%');
        }

        $agencies = $query->withSum(['transactions' => function($query) {
            $query->where('type', Transaction::TYPE_RENTAL_PAYMENT)
                  ->where('status', Transaction::STATUS_COMPLETED);
        }], 'amount')
        ->withSum(['transactions' => function($query) {
            $query->where('type', Transaction::TYPE_COMMISSION)
                  ->where('status', Transaction::STATUS_COMPLETED);
        }], 'amount')
        ->orderBy('transactions_sum_amount', 'desc')
        ->paginate(15);

        // Calculate commission statistics
        $commissionStats = [
            'totalCommissions' => Transaction::where('type', Transaction::TYPE_COMMISSION)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->sum('amount'),
            'monthlyCommissions' => Transaction::where('type', Transaction::TYPE_COMMISSION)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
            'averageCommissionRate' => Agency::where('status', 'approved')->avg('commission_rate'),
        ];

        return view('admin.finance.commissions', compact('agencies', 'commissionStats'));
    }

    public function payments(Request $request)
    {
        $query = Transaction::with(['agency', 'rental']);

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by agency
        if ($request->has('agency_id') && $request->agency_id !== '') {
            $query->where('agency_id', $request->agency_id);
        }

        $transactions = $query->latest()->paginate(20);

        // Get statistics
        $stats = [
            'totalTransactions' => Transaction::count(),
            'completedTransactions' => Transaction::where('status', Transaction::STATUS_COMPLETED)->count(),
            'pendingTransactions' => Transaction::where('status', Transaction::STATUS_PENDING)->count(),
            'failedTransactions' => Transaction::where('status', Transaction::STATUS_FAILED)->count(),
            'totalAmount' => Transaction::where('status', Transaction::STATUS_COMPLETED)->sum('amount'),
        ];

        // Get agencies for filter
        $agencies = Agency::where('status', 'approved')->orderBy('agency_name')->get();

        return view('admin.finance.payments', compact('transactions', 'stats', 'agencies'));
    }

    public function reports(Request $request)
    {
        $period = $request->get('period', '12months');
        
        // Revenue report
        $revenueReport = $this->getRevenueReport($period);
        
        // Commission report
        $commissionReport = $this->getCommissionReport($period);
        
        // Agency performance report
        $agencyReport = $this->getAgencyPerformanceReport($period);
        
        // Payment method analysis
        $paymentAnalysis = $this->getPaymentAnalysis($period);

        return view('admin.finance.reports', compact(
            'revenueReport',
            'commissionReport', 
            'agencyReport',
            'paymentAnalysis'
        ));
    }

    public function payouts(Request $request)
    {
        $query = Agency::where('status', 'approved')
            ->where('pending_earnings', '>', 0);

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('agency_name', 'like', '%' . $search . '%');
        }

        $agencies = $query->orderBy('pending_earnings', 'desc')->paginate(15);

        // Get payout statistics
        $payoutStats = [
            'totalPending' => Agency::sum('pending_earnings'),
            'agenciesWithPending' => Agency::where('pending_earnings', '>', 0)->count(),
            'totalPaidOut' => Transaction::where('type', Transaction::TYPE_WITHDRAWAL)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->sum('amount'),
            'monthlyPayouts' => Transaction::where('type', Transaction::TYPE_WITHDRAWAL)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
        ];

        return view('admin.finance.payouts', compact('agencies', 'payoutStats'));
    }

    public function processPayout(Request $request, Agency $agency)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $agency->pending_earnings,
            'notes' => 'nullable|string|max:500',
        ]);

        // Create withdrawal transaction
        $transaction = Transaction::create([
            'agency_id' => $agency->id,
            'type' => Transaction::TYPE_WITHDRAWAL,
            'amount' => $request->amount,
            'balance_before' => $agency->balance,
            'balance_after' => $agency->balance - $request->amount,
            'description' => 'Payout processed by admin',
            'status' => Transaction::STATUS_COMPLETED,
            'processed_at' => now(),
            'metadata' => [
                'processed_by' => auth()->user()->name,
                'notes' => $request->notes,
            ]
        ]);

        // Update agency balance
        $agency->decrement('balance', $request->amount);
        $agency->decrement('pending_earnings', $request->amount);
        $agency->increment('total_earnings', $request->amount);
        $agency->update(['last_payout_at' => now()]);

        return redirect()->route('admin.finance.payouts')
            ->with('success', 'Le paiement de ' . number_format($request->amount, 2) . ' MAD a été traité avec succès pour ' . $agency->agency_name);
    }

    public function export(Request $request)
    {
        $query = Transaction::with(['agency', 'rental']);

        // Apply filters
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->get();

        $filename = 'financial_transactions_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Type', 'Agence', 'Montant', 'Solde Avant', 'Solde Après', 
                'Description', 'Statut', 'Date Création', 'Date Traitement'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->type,
                    $transaction->agency->agency_name ?? 'N/A',
                    $transaction->amount,
                    $transaction->balance_before,
                    $transaction->balance_after,
                    $transaction->description,
                    $transaction->status,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->processed_at ? $transaction->processed_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getRevenueReport($period)
    {
        $data = [];
        $labels = [];

        if ($period === '12months') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M Y');
                $data[] = Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                    ->where('status', Transaction::STATUS_COMPLETED)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount');
            }
        } else {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                $data[] = Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                    ->where('status', Transaction::STATUS_COMPLETED)
                    ->whereDate('created_at', $date)
                    ->sum('amount');
            }
        }

        return ['data' => $data, 'labels' => $labels];
    }

    private function getCommissionReport($period)
    {
        $data = [];
        $labels = [];

        if ($period === '12months') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M Y');
                $data[] = Transaction::where('type', Transaction::TYPE_COMMISSION)
                    ->where('status', Transaction::STATUS_COMPLETED)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount');
            }
        }

        return ['data' => $data, 'labels' => $labels];
    }

    private function getAgencyPerformanceReport($period)
    {
        $query = Agency::where('status', 'approved')
            ->withSum(['transactions' => function($query) {
                $query->where('type', Transaction::TYPE_RENTAL_PAYMENT)
                      ->where('status', Transaction::STATUS_COMPLETED);
            }], 'amount')
            ->withSum(['transactions' => function($query) {
                $query->where('type', Transaction::TYPE_COMMISSION)
                      ->where('status', Transaction::STATUS_COMPLETED);
            }], 'amount')
            ->orderBy('transactions_sum_amount', 'desc');

        if ($period === '12months') {
            $query->whereHas('transactions', function($q) {
                $q->where('created_at', '>=', Carbon::now()->subYear());
            });
        }

        return $query->take(20)->get();
    }

    private function getPaymentAnalysis($period)
    {
        // This would analyze payment methods, success rates, etc.
        // For now, return basic transaction type analysis
        return Transaction::select('type', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->where('status', Transaction::STATUS_COMPLETED)
            ->groupBy('type')
            ->get();
    }
}
