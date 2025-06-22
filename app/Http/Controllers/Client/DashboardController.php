<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        // If no client record exists, create one
        if (!$client) {
            $client = Client::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'cin' => 'TEMP_' . $user->id, // Temporary CIN, user should update
                'birthday' => '1990-01-01', // Default birthday, user should update
                'phone' => $user->phone ?? '0000000000',
                'address' => 'Address not provided'
            ]);
        }

        // Get client's rental statistics
        $totalRentals = Rental::where('user_id', $user->id)->count();
        
        $activeRentals = Rental::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->count();
        
        $pendingRentals = Rental::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $completedRentals = Rental::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Calculate total spent
        $totalSpent = Rental::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('total_price');

        // Get recent rentals with proper relationships
        $recentRentals = Rental::where('user_id', $user->id)
            ->with(['car' => function($query) {
                $query->with(['agency' => function($q) {
                    $q->with('user');
                }]);
            }])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get available cars count from approved agencies
        $availableCars = Car::availableFromApprovedAgencies()->count();

        return view('client.dashboard', compact(
            'totalRentals',
            'activeRentals', 
            'pendingRentals',
            'completedRentals',
            'totalSpent',
            'recentRentals',
            'availableCars'
        ));
    }
}
