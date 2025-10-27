<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CommissionService;
use App\Models\Transaction;
use App\Models\Rental;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Dashboard des commissions admin
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        
        // Statistiques générales
        $stats = [
            'total_admin_commission' => $this->commissionService->getTotalAdminCommission(),
            'monthly_commission' => $this->commissionService->getTotalAdminCommission(
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ),
            'today_commission' => $this->commissionService->getTotalAdminCommission(
                Carbon::today(),
                Carbon::tomorrow()
            ),
            'commission_rate' => CommissionService::ADMIN_COMMISSION_RATE
        ];

        // Tendances des commissions
        $commissionTrends = $this->commissionService->getAdminCommissionStats($period);

        // Top agences par commission générée
        $topAgencies = Transaction::where('type', 'admin_commission')
            ->join('agencies', 'transactions.agency_id', '=', 'agencies.id')
            ->select(
                'agencies.agency_name',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(transactions.amount) as total_commission')
            )
            ->where('transactions.created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('agencies.id', 'agencies.agency_name')
            ->orderBy('total_commission', 'desc')
            ->take(10)
            ->get();

        // Transactions récentes
        $recentTransactions = Transaction::where('type', 'admin_commission')
            ->with(['agency:id,agency_name', 'rental:id,car_id', 'rental.car:id,brand,model'])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.commissions.index', compact(
            'stats', 
            'commissionTrends', 
            'topAgencies', 
            'recentTransactions',
            'period'
        ));
    }

    /**
     * Détails des commissions par agence
     */
    public function agencyDetails($agencyId)
    {
        $agency = \App\Models\Agency::findOrFail($agencyId);
        
        $stats = [
            'total_commission' => Transaction::where('agency_id', $agencyId)
                ->where('type', 'admin_commission')
                ->sum('amount'),
            'monthly_commission' => Transaction::where('agency_id', $agencyId)
                ->where('type', 'admin_commission')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
            'transaction_count' => Transaction::where('agency_id', $agencyId)
                ->where('type', 'admin_commission')
                ->count()
        ];

        $transactions = Transaction::where('agency_id', $agencyId)
            ->where('type', 'admin_commission')
            ->with('rental.car')
            ->latest()
            ->paginate(20);

        return view('admin.commissions.agency', compact('agency', 'stats', 'transactions'));
    }

    /**
     * Rapport détaillé des commissions
     */
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        $transactions = Transaction::where('type', 'admin_commission')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['agency', 'rental.car'])
            ->latest()
            ->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_commission' => $transactions->sum('amount'),
            'average_commission' => $transactions->avg('amount'),
            'total_revenue' => $transactions->sum(function($t) {
                return $t->metadata['original_amount'] ?? 0;
            })
        ];

        return view('admin.commissions.report', compact(
            'transactions', 
            'summary', 
            'startDate', 
            'endDate'
        ));
    }

    /**
     * Validation des calculs de commission
     */
    public function validateCalculations(Request $request)
    {
        $rentalId = $request->get('rental_id');
        
        if ($rentalId) {
            $rental = Rental::findOrFail($rentalId);
            $validation = $this->commissionService->validateCommissionCalculation($rental);
            
            return response()->json($validation);
        }

        // Validation globale des 100 dernières réservations
        $rentals = Rental::whereIn('status', ['active', 'completed'])
            ->latest()
            ->take(100)
            ->get();

        $validations = [];
        $invalidCount = 0;

        if ($rentals->count() > 0) {
            foreach ($rentals as $rental) {
                try {
                    $validation = $this->commissionService->validateCommissionCalculation($rental);
                    $validations[] = [
                        'rental_id' => $rental->id,
                        'is_valid' => $validation['is_valid'],
                        'difference' => $validation['difference']
                    ];
                    
                    if (!$validation['is_valid']) {
                        $invalidCount++;
                    }
                } catch (\Exception $e) {
                    // En cas d'erreur, marquer comme invalide
                    $validations[] = [
                        'rental_id' => $rental->id,
                        'is_valid' => false,
                        'difference' => 999.99 // Valeur d'erreur
                    ];
                    $invalidCount++;
                }
            }
        }

        // Convertir en collection pour pouvoir utiliser les méthodes where(), etc.
        $validations = collect($validations);
        
        return view('admin.commissions.validate', compact('validations', 'invalidCount'));
    }

    /**
     * Export des données de commission
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        $format = $request->get('format', 'csv');

        $transactions = Transaction::where('type', 'admin_commission')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['agency', 'rental.car'])
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($transactions);
        }

        // Export JSON
        return response()->json([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'summary' => [
                'total_commission' => $transactions->sum('amount'),
                'total_transactions' => $transactions->count()
            ],
            'transactions' => $transactions->map(function($t) {
                return [
                    'id' => $t->id,
                    'agency_name' => $t->agency->agency_name,
                    'car' => $t->rental->car->brand . ' ' . $t->rental->car->model,
                    'original_amount' => $t->metadata['original_amount'] ?? 0,
                    'commission_amount' => $t->amount,
                    'commission_rate' => $t->metadata['commission_rate'] ?? 0,
                    'date' => $t->created_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    }

    private function exportToCsv($transactions)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="commissions_admin_' . date('Y-m-d') . '.csv"'
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID Transaction',
                'Agence',
                'Voiture',
                'Montant Original',
                'Commission Admin',
                'Taux Commission',
                'Date'
            ]);

            // Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->agency->agency_name,
                    $transaction->rental->car->brand . ' ' . $transaction->rental->car->model,
                    $transaction->metadata['original_amount'] ?? 0,
                    $transaction->amount,
                    $transaction->metadata['commission_rate'] ?? 0,
                    $transaction->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
