<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Activity;
use App\Services\RentalService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(RentalService $rentalService)
    {
        // Get the authenticated agency
        $agency = auth()->user()->agency;

        if (!$agency || $agency->status !== 'approved') {
            return view('agence.dashboard');
        }

        // Get comprehensive statistics using the service
        $statistics = $rentalService->getAgencyStatistics($agency);

        // Get additional car statistics
        $availableCars = Car::where('agency_id', $agency->id)
            ->where('available_stock', '>', 0)
            ->count();

        $totalFleetSize = Car::where('agency_id', $agency->id)
            ->sum('stock_quantity');

        // Calculate monthly revenue
        $monthlyRevenue = Rental::where('agency_id', $agency->id)
            ->whereIn('status', ['active', 'completed'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');

        // Get recent transactions
        $recentTransactions = $agency->transactions()
            ->latest()
            ->take(5)
            ->get();

        // Get recent activities
        $recentActivities = Activity::where('agency_id', $agency->id)
            ->latest()
            ->take(5)
            ->get();

        return view('agence.dashboard', compact(
            'statistics',
            'availableCars',
            'totalFleetSize',
            'monthlyRevenue',
            'recentTransactions',
            'recentActivities'
        ));
    }
} 