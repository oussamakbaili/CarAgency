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
        
        // Customer Satisfaction (placeholder - would need review system)
        $customerRating = 4.5; // This would come from reviews table
        $reviewCount = 0; // This would come from reviews table
        
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
    }

    private function getRecentActivity($agency)
    {
        $activities = collect();
        
        // Recent bookings
        $recentBookings = Rental::where('rentals.agency_id', $agency->id)
            ->with(['car', 'client'])
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
        
        // Recent transactions
        $recentTransactions = Transaction::where('transactions.agency_id', $agency->id)
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
        
        // System activities
        $systemActivities = Activity::where('activities.agency_id', $agency->id)
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
        // Recent bookings
        $recentBookings = Rental::where('rentals.agency_id', $agency->id)
            ->with(['car', 'client'])
            ->latest()
            ->take(10)
            ->get();
        
        // Fleet status overview
        $fleetStatus = Car::where('agency_id', $agency->id)
            ->with(['rentals' => function($query) {
                $query->where('status', 'active');
            }])
            ->get();
        
        // Upcoming maintenance (placeholder - would need maintenance table)
        $upcomingMaintenance = collect();
        
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