<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Activity;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated agency
        $agency = auth()->user()->agency;

        if (!$agency || $agency->status !== 'approved') {
            return view('agence.dashboard');
        }

        // Get total cars
        $totalCars = Car::where('agency_id', $agency->id)->count();

        // Get active rentals
        $activeRentals = Rental::where('agency_id', $agency->id)
            ->where('status', 'active')
            ->count();

        // Get pending rentals
        $pendingRentals = Rental::where('agency_id', $agency->id)
            ->where('status', 'pending')
            ->count();

        // Calculate monthly revenue
        $monthlyRevenue = Rental::where('agency_id', $agency->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');

        // Get recent activities
        $recentActivities = Activity::where('agency_id', $agency->id)
            ->latest()
            ->take(5)
            ->get();

        return view('agence.dashboard', compact(
            'totalCars',
            'activeRentals',
            'pendingRentals',
            'monthlyRevenue',
            'recentActivities'
        ));
    }
} 