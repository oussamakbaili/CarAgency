<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CommissionService;
use App\Models\Transaction;
use App\Models\Rental;
use App\Models\Agency;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceDashboardController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Dashboard financier unifié
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        
        // Statistiques générales financières
        $financialStats = [
            // Revenus totaux
            'total_revenue' => Rental::whereIn('status', ['active', 'completed'])->sum('total_price'),
            'monthly_revenue' => Rental::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price'),
            'today_revenue' => Rental::whereIn('status', ['active', 'completed'])
                ->whereDate('created_at', Carbon::today())
                ->sum('total_price'),
            
            // Commissions admin
            'total_admin_commission' => $this->commissionService->getTotalAdminCommission(),
            'monthly_admin_commission' => $this->commissionService->getTotalAdminCommission(
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ),
            'today_admin_commission' => $this->commissionService->getTotalAdminCommission(
                Carbon::today(),
                Carbon::tomorrow()
            ),
            
            // Métriques de performance
            'total_bookings' => Rental::whereIn('status', ['active', 'completed'])->count(),
            'monthly_bookings' => Rental::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
            'average_booking_value' => Rental::whereIn('status', ['active', 'completed'])->avg('total_price'),
            
            // Agences
            'total_agencies' => Agency::where('status', 'approved')->count(),
            'active_agencies' => Agency::where('status', 'approved')
                ->whereHas('rentals', function($query) {
                    $query->whereIn('status', ['active', 'completed']);
                })
                ->count()
        ];

        // Tendances des revenus
        $revenueTrends = $this->getRevenueTrends($period);

        // Tendances des commissions
        $commissionTrends = $this->commissionService->getAdminCommissionStats($period);

        // Top agences par revenus
        $topAgencies = Agency::where('status', 'approved')
            ->withSum(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }], 'total_price')
            ->withCount(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }])
            ->orderBy('rentals_sum_total_price', 'desc')
            ->take(10)
            ->get();

        // Top agences par commissions générées
        $topAgenciesByCommission = Transaction::where('type', 'admin_commission')
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
        $recentTransactions = Transaction::whereIn('type', ['admin_commission', 'rental_payment'])
            ->with(['agency:id,agency_name', 'rental:id,car_id', 'rental.car:id,brand,model'])
            ->latest()
            ->take(20)
            ->get();

        // Statistiques par type de transaction
        $transactionStats = [
            'admin_commission' => Transaction::where('type', 'admin_commission')->sum('amount'),
            'rental_payment' => Transaction::where('type', 'rental_payment')->sum('amount'),
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count()
        ];

        return view('admin.finance.dashboard', compact(
            'financialStats',
            'revenueTrends',
            'commissionTrends',
            'topAgencies',
            'topAgenciesByCommission',
            'recentTransactions',
            'transactionStats',
            'period'
        ));
    }

    /**
     * Récupère les tendances des revenus
     */
    private function getRevenueTrends($period)
    {
        if ($period === 'month') {
            return Rental::whereIn('status', ['active', 'completed'])
                ->selectRaw("
                    MONTH(created_at) as month,
                    YEAR(created_at) as year,
                    SUM(total_price) as revenue,
                    COUNT(*) as bookings_count
                ")
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
        } else {
            return Rental::whereIn('status', ['active', 'completed'])
                ->selectRaw("
                    DATE(created_at) as period,
                    SUM(total_price) as revenue,
                    COUNT(*) as bookings_count
                ")
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('period')
                ->orderBy('period', 'desc')
                ->get();
        }
    }

    /**
     * Export des données financières
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        $data = [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'revenue' => [
                'total' => Rental::whereIn('status', ['active', 'completed'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('total_price'),
                'bookings_count' => Rental::whereIn('status', ['active', 'completed'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count()
            ],
            'commissions' => [
                'total_admin_commission' => Transaction::where('type', 'admin_commission')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('amount'),
                'transactions_count' => Transaction::where('type', 'admin_commission')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count()
            ]
        ];

        if ($format === 'csv') {
            return $this->exportToCsv($data, $startDate, $endDate);
        }

        return response()->json($data);
    }

    private function exportToCsv($data, $startDate, $endDate)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dashboard_financier_' . date('Y-m-d') . '.csv"'
        ];

        $callback = function() use ($data, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Période',
                'Revenus Totaux (MAD)',
                'Nombre de Réservations',
                'Commissions Admin (MAD)',
                'Nombre de Transactions',
                'Date de Génération'
            ]);

            // Data
            fputcsv($file, [
                $startDate . ' - ' . $endDate,
                $data['revenue']['total'],
                $data['revenue']['bookings_count'],
                $data['commissions']['total_admin_commission'],
                $data['commissions']['transactions_count'],
                now()->format('Y-m-d H:i:s')
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
