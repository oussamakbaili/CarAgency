<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Rental;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PaymentNotificationService;
use Illuminate\Validation\ValidationException;

class FinanceController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;
        
        // Calculate monthly revenue (current month)
        $monthlyRevenue = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->whereMonth('rentals.created_at', Carbon::now()->month)
            ->whereYear('rentals.created_at', Carbon::now()->year)
            ->sum('rentals.total_price');
        
        // Calculate previous month revenue for growth percentage
        $previousMonthRevenue = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->whereMonth('rentals.created_at', Carbon::now()->subMonth()->month)
            ->whereYear('rentals.created_at', Carbon::now()->subMonth()->year)
            ->sum('rentals.total_price');
        
        // Calculate revenue growth percentage
        $revenueGrowth = 0;
        if ($previousMonthRevenue > 0) {
            $revenueGrowth = round((($monthlyRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1);
        } elseif ($monthlyRevenue > 0) {
            $revenueGrowth = 100; // 100% growth if no previous revenue
        }
        
        // Financial overview
        $overview = [
            'total_earnings' => $agency->total_earnings ?? 0,
            'pending_earnings' => $agency->pending_earnings ?? 0,
            'current_balance' => $agency->balance ?? 0,
            'monthly_revenue' => $monthlyRevenue,
            'previous_month_revenue' => $previousMonthRevenue,
            'revenue_growth' => $revenueGrowth,
            'commission_rate' => $agency->commission_rate ?? 0,
        ];
        
        // Recent transactions
        $recentTransactions = Transaction::where('transactions.agency_id', $agency->id)
            ->latest()
            ->take(10)
            ->get();
        
        // Revenue trends (last 12 months) - for chart
        $revenueTrendsData = [];
        $monthLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Rental::where('rentals.agency_id', $agency->id)
                ->whereIn('rentals.status', ['active', 'completed'])
                ->whereMonth('rentals.created_at', $date->month)
                ->whereYear('rentals.created_at', $date->year)
                ->sum('rentals.total_price');
            
            $revenueTrendsData[] = round($revenue, 0);
            // Format month in French
            $monthLabels[] = $date->locale('fr')->shortMonthName;
        }
        
        // Payment methods distribution - from payments table
        $paymentMethods = \App\Models\Payment::whereHas('rental', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->where('status', \App\Models\Payment::STATUS_COMPLETED)
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        // Calculate counts for each payment method category
        $totalPayments = 0;
        foreach ($paymentMethods as $method) {
            $totalPayments += (int) $method->count;
        }
        $paymentMethodsCounts = [
            'Carte Bancaire' => 0,
            'Virement' => 0,
            'Espèces' => 0,
            'Chèque' => 0,
        ];
        
        foreach ($paymentMethods as $method) {
            // Map payment methods to display names
            switch ($method->payment_method) {
                case 'stripe':
                    $paymentMethodsCounts['Carte Bancaire'] += $method->count;
                    break;
                case 'paypal':
                    $paymentMethodsCounts['Virement'] += $method->count; // PayPal can be considered as bank transfer
                    break;
                case 'bank_transfer':
                    $paymentMethodsCounts['Virement'] += $method->count;
                    break;
            }
        }
        
        // Calculate percentages from counts
        $paymentMethodsData = [
            'Carte Bancaire' => $totalPayments > 0 ? round(($paymentMethodsCounts['Carte Bancaire'] / $totalPayments) * 100, 1) : 0,
            'Virement' => $totalPayments > 0 ? round(($paymentMethodsCounts['Virement'] / $totalPayments) * 100, 1) : 0,
            'Espèces' => $totalPayments > 0 ? round(($paymentMethodsCounts['Espèces'] / $totalPayments) * 100, 1) : 0,
            'Chèque' => $totalPayments > 0 ? round(($paymentMethodsCounts['Chèque'] / $totalPayments) * 100, 1) : 0,
        ];
        
        return view('agence.finance.index', compact(
            'overview', 
            'recentTransactions', 
            'revenueTrendsData', 
            'monthLabels',
            'paymentMethodsData'
        ));
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
    
    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:revenue,expenses,profit_loss,tax,custom',
            'period' => 'required|in:today,week,month,quarter,year,custom',
            'format' => 'required|in:csv,excel,pdf',
            'start_date' => 'required_if:period,custom|date',
            'end_date' => 'required_if:period,custom|date|after_or_equal:start_date',
        ]);
        
        $agency = auth()->user()->agency;
        $reportType = $request->input('report_type');
        $period = $request->input('period');
        $format = $request->input('format');
        
        // Calculate date range based on period
        $dateRange = $this->getDateRange($period, $request->input('start_date'), $request->input('end_date'));
        
        // Get data based on report type
        $data = $this->getReportData($agency, $reportType, $dateRange);
        
        // Generate file based on format
        return $this->generateFile($data, $reportType, $format, $dateRange);
    }
    
    private function getDateRange($period, $startDate = null, $endDate = null)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                ];
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek(),
                ];
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                ];
            case 'quarter':
                return [
                    'start' => $now->copy()->startOfQuarter(),
                    'end' => $now->copy()->endOfQuarter(),
                ];
            case 'year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear(),
                ];
            case 'custom':
                return [
                    'start' => Carbon::parse($startDate)->startOfDay(),
                    'end' => Carbon::parse($endDate)->endOfDay(),
                ];
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                ];
        }
    }
    
    private function getReportData($agency, $reportType, $dateRange)
    {
        $query = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->whereBetween('rentals.created_at', [$dateRange['start'], $dateRange['end']]);
        
        switch ($reportType) {
            case 'revenue':
                return $query->select(
                    'rentals.id',
                    'rentals.created_at',
                    'rentals.total_price',
                    'rentals.status',
                    DB::raw('(SELECT brand FROM cars WHERE cars.id = rentals.car_id) as car_brand'),
                    DB::raw('(SELECT model FROM cars WHERE cars.id = rentals.car_id) as car_model')
                )->get();
                
            case 'expenses':
                return Transaction::where('agency_id', $agency->id)
                    ->whereIn('type', ['withdrawal', 'withdrawal_request', 'fee'])
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->get();
                    
            case 'profit_loss':
                $revenues = Rental::where('rentals.agency_id', $agency->id)
                    ->whereIn('rentals.status', ['active', 'completed'])
                    ->whereBetween('rentals.created_at', [$dateRange['start'], $dateRange['end']])
                    ->sum('total_price');
                    
                $expenses = Transaction::where('agency_id', $agency->id)
                    ->whereIn('type', ['withdrawal', 'withdrawal_request', 'fee'])
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->sum('amount');
                    
                return [
                    'revenue' => $revenues,
                    'expenses' => $expenses,
                    'profit' => $revenues - $expenses,
                ];
                
            case 'tax':
                return Rental::where('rentals.agency_id', $agency->id)
                    ->whereIn('rentals.status', ['active', 'completed'])
                    ->whereBetween('rentals.created_at', [$dateRange['start'], $dateRange['end']])
                    ->select(
                        'rentals.id',
                        'rentals.created_at',
                        'rentals.total_price',
                        DB::raw('rentals.total_price * 0.20 as tax_amount')
                    )
                    ->get();
                    
            default:
                return $query->get();
        }
    }
    
    private function generateFile($data, $reportType, $format, $dateRange)
    {
        $filename = 'rapport_' . $reportType . '_' . now()->format('Y-m-d_H-i-s');
        
        if ($format === 'csv') {
            return $this->generateCSV($data, $reportType, $filename, $dateRange);
        } elseif ($format === 'excel') {
            return $this->generateCSV($data, $reportType, $filename, $dateRange); // Excel as CSV for now
        } else {
            // Generate PDF
            return $this->generatePDF($data, $reportType, $filename, $dateRange);
        }
    }
    
    private function generatePDF($data, $reportType, $filename, $dateRange)
    {
        $agency = auth()->user()->agency;
        
        // Get report type label
        $reportTypeLabels = [
            'revenue' => 'Rapport de Revenus',
            'expenses' => 'Rapport de Dépenses',
            'profit_loss' => 'Bénéfices et Pertes',
            'tax' => 'Rapport Fiscal',
            'custom' => 'Rapport Personnalisé',
        ];
        
        $reportTypeLabel = $reportTypeLabels[$reportType] ?? 'Rapport Financier';
        
        // Generate PDF using DomPDF
        $pdf = Pdf::loadView('agence.finance.report-pdf', [
            'data' => $data,
            'reportType' => $reportType,
            'reportTypeLabel' => $reportTypeLabel,
            'dateRange' => $dateRange,
            'agency' => $agency,
        ]);
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');
        
        // Download the PDF
        return $pdf->download($filename . '.pdf');
    }
    
    private function generateCSV($data, $reportType, $filename, $dateRange)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];
        
        $callback = function() use ($data, $reportType, $dateRange) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers based on report type
            switch ($reportType) {
                case 'revenue':
                    fputcsv($file, ['Date', 'Véhicule', 'Montant (MAD)', 'Statut']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->created_at->format('d/m/Y H:i'),
                            ($item->car_brand ?? '') . ' ' . ($item->car_model ?? ''),
                            number_format($item->total_price, 2, ',', ' '),
                            ucfirst($item->status),
                        ]);
                    }
                    break;
                    
                case 'expenses':
                    fputcsv($file, ['Date', 'Type', 'Description', 'Montant (MAD)', 'Statut']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->created_at->format('d/m/Y H:i'),
                            ucfirst(str_replace('_', ' ', $item->type)),
                            $item->description ?? 'N/A',
                            number_format($item->amount, 2, ',', ' '),
                            ucfirst($item->status),
                        ]);
                    }
                    break;
                    
                case 'profit_loss':
                    fputcsv($file, ['Type', 'Montant (MAD)']);
                    fputcsv($file, ['Revenus', number_format($data['revenue'], 2, ',', ' ')]);
                    fputcsv($file, ['Dépenses', number_format($data['expenses'], 2, ',', ' ')]);
                    fputcsv($file, ['Bénéfice Net', number_format($data['profit'], 2, ',', ' ')]);
                    break;
                    
                case 'tax':
                    fputcsv($file, ['Date', 'Montant Total (MAD)', 'Montant TVA (20%)']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->created_at->format('d/m/Y H:i'),
                            number_format($item->total_price, 2, ',', ' '),
                            number_format($item->tax_amount, 2, ',', ' '),
                        ]);
                    }
                    break;
                    
                default:
                    fputcsv($file, ['Date', 'Description', 'Montant (MAD)']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->created_at->format('d/m/Y H:i'),
                            $item->description ?? 'N/A',
                            number_format($item->total_price ?? $item->amount ?? 0, 2, ',', ' '),
                        ]);
                    }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
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
            'amount' => 'required|numeric|min:100',
            'bank_name' => 'required|string|max:100',
            'rib_number' => 'required|string|max:34',
            'account_holder' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $transaction = null;

            DB::transaction(function () use ($request, $agency, &$transaction) {
                $agency->refresh();
                $availableBalance = $agency->balance ?? 0;

                if ($request->amount > $availableBalance) {
                    throw ValidationException::withMessages([
                        'amount' => 'Le montant demandé dépasse votre solde disponible.',
                    ]);
                }

                $balanceBefore = $availableBalance;
                $balanceAfter = $balanceBefore - $request->amount;

                $transaction = Transaction::create([
                    'agency_id' => $agency->id,
                    'rental_id' => null,
                    'type' => Transaction::TYPE_WITHDRAWAL_REQUEST,
                    'amount' => $request->amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'description' => 'Demande de retrait - Virement bancaire',
                    'status' => Transaction::STATUS_PENDING,
                    'metadata' => [
                        'payment_method' => 'bank_transfer',
                        'bank_name' => $request->bank_name,
                        'rib_number' => $request->rib_number,
                        'account_holder' => $request->account_holder,
                        'notes' => $request->notes,
                        'requested_at' => now()->toISOString(),
                    ],
                ]);

                // Update balances: move funds from available balance to pending earnings
                $agency->balance = $balanceAfter;
                $agency->pending_earnings = ($agency->pending_earnings ?? 0) + $request->amount;
                $agency->save();
            });

            if ($transaction) {
                // Notify all admins
                PaymentNotificationService::notifyAdminPayoutRequested($transaction);
            }

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de paiement a été envoyée avec succès.',
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Payment request failed', [
                'agency_id' => $agency->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la demande: ' . $e->getMessage(),
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
        // Total customers who have at least one rental with this agency
        $totalCustomers = Client::whereHas('rentals', function($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->count();
        
        // Repeat customers (clients with more than one rental)
        // Use a subquery to count rentals per client
        $repeatCustomers = Client::whereHas('rentals', function($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })
        ->whereRaw('(SELECT COUNT(*) FROM rentals WHERE rentals.user_id = clients.user_id AND rentals.agency_id = ?) > 1', [$agency->id])
        ->count();
        
        // Average rental value
        $averageRentalValue = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->avg('rentals.total_price');
        
        return [
            'total_customers' => $totalCustomers,
            'repeat_customers' => $repeatCustomers,
            'average_rental_value' => $averageRentalValue ?? 0,
        ];
    }
}
