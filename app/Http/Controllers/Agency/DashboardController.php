<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Activity;
use App\Models\Transaction;
use App\Models\Client;
use App\Services\RentalService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(RentalService $rentalService)
    {
        // Get the authenticated agency
        $agency = auth()->user()->agency;

        if (!$agency || $agency->status !== 'approved') {
            return view('agence.dashboard');
        }

        // Get comprehensive dashboard data
        $dashboardData = $this->getDashboardData($agency);

        return view('agence.dashboard', $dashboardData);
    }

    private function getDashboardData($agency)
    {
        // Business Overview Cards
        $businessOverview = $this->getBusinessOverview($agency);
        
        // Recent Activity Feed
        $recentActivity = $this->getRecentActivity($agency);
        
        // Performance Charts Data
        $performanceCharts = $this->getPerformanceCharts($agency);
        
        // Data Tables
        $dataTables = $this->getDataTables($agency);
        
        // Quick Actions Data
        $quickActions = $this->getQuickActionsData($agency);

        return array_merge($businessOverview, [
            'recentActivity' => $recentActivity,
            'performanceCharts' => $performanceCharts,
            'dataTables' => $dataTables,
            'quickActions' => $quickActions,
        ]);
    }

    private function getBusinessOverview($agency)
    {
        // Cache key for business overview (5 minutes cache)
        $cacheKey = "agency.{$agency->id}.business_overview";
        
        return Cache::remember($cacheKey, 300, function() use ($agency) {
            // Fleet Statistics
            $totalCars = Car::where('agency_id', $agency->id)->sum('stock_quantity');
            $availableCars = Car::where('agency_id', $agency->id)
                ->where('available_stock', '>', 0)
                ->sum('available_stock');
            $carsInMaintenance = Car::where('agency_id', $agency->id)
                ->where('status', 'maintenance')
                ->count();
        
        // Rental Statistics
        $activeRentals = Rental::where('rentals.agency_id', $agency->id)
            ->where('rentals.status', 'active')
            ->count();
        $pendingBookings = Rental::where('rentals.agency_id', $agency->id)
            ->where('rentals.status', 'pending')
            ->count();
        
        // Revenue Statistics
        $monthlyRevenue = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->whereMonth('rentals.created_at', Carbon::now()->month)
            ->sum('rentals.total_price');
        
        $previousMonthRevenue = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->whereMonth('rentals.created_at', Carbon::now()->subMonth()->month)
            ->sum('rentals.total_price');
        
        $revenueGrowth = $previousMonthRevenue > 0 
            ? (($monthlyRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 
            : 0;
        
        // Performance Metrics
        $totalBookings = Rental::where('rentals.agency_id', $agency->id)->count();
        $completedBookings = Rental::where('rentals.agency_id', $agency->id)
            ->where('rentals.status', 'completed')
            ->count();
        $conversionRate = $totalBookings > 0 ? ($completedBookings / $totalBookings) * 100 : 0;
        
        // Customer Satisfaction - Real calculation from reviews
        $reviews = \App\Models\Avis::where('agency_id', $agency->id)
            ->where('is_public', true)
            ->get();
        
        $reviewCount = $reviews->count();
        $customerRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;
        
            return [
                'totalCars' => $totalCars,
                'availableCars' => $availableCars,
                'carsInMaintenance' => $carsInMaintenance,
                'activeRentals' => $activeRentals,
                'pendingBookings' => $pendingBookings,
                'monthlyRevenue' => $monthlyRevenue,
                'revenueGrowth' => $revenueGrowth,
                'conversionRate' => $conversionRate,
                'customerRating' => $customerRating,
                'reviewCount' => $reviewCount,
            ];
        });
    }

    private function getRecentActivity($agency)
    {
        $activities = collect();
        
        // Recent bookings - Optimized with eager loading
        $recentBookings = Rental::where('rentals.agency_id', $agency->id)
            ->with(['car:id,brand,model', 'client:id,user_id', 'user:id,name'])
            ->select('id', 'car_id', 'user_id', 'agency_id', 'status', 'created_at')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($rental) {
                return [
                    'type' => 'booking',
                    'title' => 'Nouvelle demande de location',
                    'description' => "Demande pour {$rental->car->brand} {$rental->car->model}",
                    'time' => $rental->created_at,
                    'status' => $rental->status,
                    'data' => $rental
                ];
            });
        
        // Recent transactions - Optimized
        $recentTransactions = Transaction::where('transactions.agency_id', $agency->id)
            ->select('id', 'agency_id', 'amount', 'description', 'status', 'created_at')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'type' => 'transaction',
                    'title' => 'Nouvelle transaction',
                    'description' => "Montant: {$transaction->amount}€ - {$transaction->description}",
                    'time' => $transaction->created_at,
                    'status' => $transaction->status,
                    'data' => $transaction
                ];
            });
        
        // System activities - Optimized
        $systemActivities = Activity::where('activities.agency_id', $agency->id)
            ->select('id', 'agency_id', 'description', 'created_at')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($activity) {
                return [
                    'type' => 'system',
                    'title' => 'Activité système',
                    'description' => $activity->description,
                    'time' => $activity->created_at,
                    'status' => 'info',
                    'data' => $activity
                ];
            });
        
        return $activities->merge($recentBookings)
            ->merge($recentTransactions)
            ->merge($systemActivities)
            ->sortByDesc('time')
            ->take(10);
    }

    private function getPerformanceCharts($agency)
    {
        // Revenue trends (last 12 months)
        $revenueTrends = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->select(
                DB::raw('MONTH(rentals.created_at) as month'),
                DB::raw('YEAR(rentals.created_at) as year'),
                DB::raw('SUM(rentals.total_price) as revenue')
            )
            ->where('rentals.created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Booking patterns by car type
        $bookingPatterns = Rental::where('rentals.agency_id', $agency->id)
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->select(
                'cars.brand',
                DB::raw('COUNT(*) as booking_count'),
                DB::raw('SUM(rentals.total_price) as total_revenue')
            )
            ->groupBy('cars.brand')
            ->get();
        
        // Fleet utilization
        $fleetUtilization = Car::where('agency_id', $agency->id)
            ->select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();
        
        return [
            'revenueTrends' => $revenueTrends,
            'bookingPatterns' => $bookingPatterns,
            'fleetUtilization' => $fleetUtilization,
        ];
    }

    private function getDataTables($agency)
    {
        // Recent bookings - Optimized with select
        $recentBookings = Rental::where('rentals.agency_id', $agency->id)
            ->with([
                'car:id,brand,model,registration_number',
                'client:id,phone,user_id',
                'user:id,name,email'
            ])
            ->select('id', 'car_id', 'user_id', 'agency_id', 'start_date', 'end_date', 'status', 'total_price', 'created_at')
            ->latest()
            ->take(10)
            ->get();
        
        // Fleet status overview - Optimized
        $fleetStatus = Car::where('agency_id', $agency->id)
            ->with(['rentals' => function($query) {
                $query->where('status', 'active')
                    ->select('id', 'car_id', 'start_date', 'end_date', 'status');
            }])
            ->select('id', 'agency_id', 'brand', 'model', 'status', 'stock_quantity', 'available_stock')
            ->get();
        
        // Upcoming maintenance - Real data with optimized query
        $upcomingMaintenance = \App\Models\Maintenance::where('agency_id', $agency->id)
            ->whereIn('status', ['scheduled', 'pending'])
            ->where('scheduled_date', '>=', Carbon::now())
            ->where('scheduled_date', '<=', Carbon::now()->addDays(30))
            ->with('car:id,brand,model,registration_number')
            ->select('id', 'agency_id', 'car_id', 'type', 'status', 'scheduled_date', 'description')
            ->orderBy('scheduled_date', 'asc')
            ->take(5)
            ->get();
        
        return [
            'recentBookings' => $recentBookings,
            'fleetStatus' => $fleetStatus,
            'upcomingMaintenance' => $upcomingMaintenance,
        ];
    }

    private function getQuickActionsData($agency)
    {
        return [
            'pendingBookingsCount' => Rental::where('rentals.agency_id', $agency->id)
                ->where('rentals.status', 'pending')
                ->count(),
            'availableCarsCount' => Car::where('cars.agency_id', $agency->id)
                ->where('cars.available_stock', '>', 0)
                ->count(),
            'maintenanceAlerts' => Car::where('cars.agency_id', $agency->id)
                ->where('cars.status', 'maintenance')
                ->count(),
        ];
    }
} 