<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Rental;
use App\Models\Car;
use App\Models\User;
use App\Models\Client;
use App\Models\Transaction;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private CommissionService $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function index(Request $request)
    {
        // Overview Statistics
        $stats = $this->getOverviewStatistics();
        
        // Recent Activity
        $recentActivity = $this->getRecentActivity();
        
        // Charts Data
        $chartsData = $this->getChartsData();
        
        // Recent Applications
        $recentApplications = $this->getRecentApplications();
        
        // System Health
        $systemHealth = $this->getSystemHealth();
        
        // Quick Actions Data
        $quickActionsData = $this->getQuickActionsData();

        // Financial additions (merged from financial dashboard)
        $period = $request->get('period', 'month');
        $financialStats = $this->getFinancialStats();
        $revenueTrends = $this->getRevenueTrends($period);
        $commissionTrends = $this->commissionService->getAdminCommissionStats($period);
        $topAgencies = $this->getTopAgenciesByRevenue();
        $topAgenciesByCommission = $this->getTopAgenciesByCommission();
        $recentTransactions = $this->getRecentTransactions();

        return view('admin.dashboard', compact(
            'stats',
            'recentActivity',
            'chartsData',
            'recentApplications',
            'systemHealth',
            'quickActionsData',
            'financialStats',
            'revenueTrends',
            'commissionTrends',
            'topAgencies',
            'topAgenciesByCommission',
            'recentTransactions',
            'period'
        ));
    }

    private function getOverviewStatistics()
    {
        return [
            'totalAgencies' => Agency::count(),
            'pendingAgencies' => Agency::where('status', 'pending')->count(),
            'approvedAgencies' => Agency::where('status', 'approved')->count(),
            'rejectedAgencies' => Agency::where('status', 'rejected')->count(),
            'totalCustomers' => Client::count(),
            'totalCars' => Car::count(),
            'activeRentals' => Rental::where('status', 'active')->count(),
            'monthlyRevenue' => $this->getMonthlyRevenue(),
            'pendingApprovals' => Agency::where('status', 'pending')->count(),
        ];
    }

    private function getMonthlyRevenue()
    {
        return Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
            ->where('status', Transaction::STATUS_COMPLETED)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
    }

    private function getRecentActivity()
    {
        $activities = collect();

        // Recent agency registrations
        $recentAgencies = Agency::latest()->take(5)->get();
        foreach ($recentAgencies as $agency) {
            $activities->push([
                'type' => 'agency_registration',
                'title' => 'Nouvelle inscription d\'agence',
                'description' => $agency->agency_name . ' s\'est inscrite',
                'time' => $agency->created_at,
                'status' => $agency->status,
                'icon' => 'building',
                'color' => $this->getStatusColor($agency->status)
            ]);
        }

        // Recent customer registrations
        $recentClients = Client::with('user')->latest()->take(5)->get();
        foreach ($recentClients as $client) {
            $activities->push([
                'type' => 'customer_registration',
                'title' => 'Nouveau client',
                'description' => $client->name . ' s\'est inscrit',
                'time' => $client->created_at,
                'status' => 'success',
                'icon' => 'user',
                'color' => 'green'
            ]);
        }

        // Recent bookings
        $recentBookings = Rental::with(['user', 'car'])->latest()->take(5)->get();
        foreach ($recentBookings as $booking) {
            $activities->push([
                'type' => 'booking',
                'title' => 'Nouvelle réservation',
                'description' => 'Réservation pour ' . $booking->car->brand . ' ' . $booking->car->model,
                'time' => $booking->created_at,
                'status' => $booking->status,
                'icon' => 'calendar',
                'color' => $this->getStatusColor($booking->status)
            ]);
        }

        return $activities->sortByDesc('time')->take(10);
    }

    public function getChartsData()
    {
        // Revenue trends (last 12 months)
        $revenueData = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            
            $revenueData[] = $revenue ?? 0;
            $labels[] = $date->format('M Y');
        }

        // Booking patterns (last 30 days)
        $bookingData = [];
        $bookingLabels = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $bookings = Rental::whereDate('created_at', $date)->count();
            
            $bookingData[] = $bookings ?? 0;
            $bookingLabels[] = $date->format('M d');
        }

        // Popular car categories
        $carCategories = Car::select('brand', DB::raw('count(*) as count'))
            ->groupBy('brand')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        return [
            'revenue' => [
                'data' => $revenueData,
                'labels' => $labels
            ],
            'bookings' => [
                'data' => $bookingData,
                'labels' => $bookingLabels
            ],
            'carCategories' => $carCategories
        ];
    }

    private function getRecentApplications()
    {
        return Agency::where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();
    }

    private function getSystemHealth()
    {
        return [
            'database' => 'healthy',
            'storage' => 'healthy',
            'email' => 'healthy',
            'api' => 'healthy'
        ];
    }

    private function getQuickActionsData()
    {
        return [
            'pendingAgencies' => Agency::where('status', 'pending')->count(),
            'pendingDocuments' => Agency::where('status', 'pending')->count(),
            'systemAlerts' => 0,
            'maintenanceRequired' => false
        ];
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return 'yellow';
            case 'approved':
            case 'active':
            case 'completed':
                return 'green';
            case 'rejected':
            case 'cancelled':
                return 'red';
            default:
                return 'gray';
        }
    }

    // API endpoints for AJAX requests
    public function getStats()
    {
        return response()->json($this->getOverviewStatistics());
    }

    public function getActivity()
    {
        return response()->json($this->getRecentActivity());
    }

    public function getCharts()
    {
        return response()->json($this->getChartsData());
    }

    private function getFinancialStats(): array
    {
        return [
            'total_revenue' => Rental::whereIn('status', ['active', 'completed'])->sum('total_price'),
            'monthly_revenue' => Rental::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price'),
            'total_admin_commission' => $this->commissionService->getTotalAdminCommission(),
            'monthly_admin_commission' => $this->commissionService->getTotalAdminCommission(
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ),
            'total_bookings' => Rental::whereIn('status', ['active', 'completed'])->count(),
            'monthly_bookings' => Rental::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
            'average_booking_value' => Rental::whereIn('status', ['active', 'completed'])->avg('total_price'),
        ];
    }

    private function getRevenueTrends(string $period)
    {
        if ($period === 'month') {
            return Rental::whereIn('status', ['active', 'completed'])
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as revenue, COUNT(*) as bookings_count')
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
        }

        return Rental::whereIn('status', ['active', 'completed'])
            ->selectRaw('DATE(created_at) as period, SUM(total_price) as revenue, COUNT(*) as bookings_count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('period')
            ->orderBy('period', 'desc')
            ->get();
    }

    private function getTopAgenciesByRevenue()
    {
        return Agency::where('status', 'approved')
            ->withSum(['rentals' => function ($query) {
                $query->whereIn('status', ['active', 'completed']);
            }], 'total_price')
            ->withCount(['rentals' => function ($query) {
                $query->whereIn('status', ['active', 'completed']);
            }])
            ->orderBy('rentals_sum_total_price', 'desc')
            ->take(10)
            ->get();
    }

    private function getTopAgenciesByCommission()
    {
        return Transaction::where('type', 'admin_commission')
            ->join('agencies', 'transactions.agency_id', '=', 'agencies.id')
            ->select('agencies.agency_name', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(transactions.amount) as total_commission'))
            ->where('transactions.created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('agencies.id', 'agencies.agency_name')
            ->orderBy('total_commission', 'desc')
            ->take(10)
            ->get();
    }

    private function getRecentTransactions()
    {
        return Transaction::whereIn('type', ['admin_commission', 'rental_payment'])
            ->with(['agency:id,agency_name', 'rental:id,car_id', 'rental.car:id,brand,model'])
            ->latest()
            ->take(20)
            ->get();
    }
} 