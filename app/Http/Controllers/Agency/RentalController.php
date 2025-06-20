<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Activity;

class RentalController extends Controller
{
    public function pending()
    {
        $agency = auth()->user()->agency;
        
        $pendingRentals = Rental::where('agency_id', $agency->id)
            ->where('status', 'pending')
            ->with(['car', 'user'])
            ->latest()
            ->paginate(10);

        return view('agence.rentals.pending', compact('pendingRentals'));
    }

    public function approve(Rental $rental)
    {
        $this->authorize('update', $rental);

        $rental->update(['status' => 'active']);

        // Record activity
        Activity::create([
            'agency_id' => $rental->agency_id,
            'type' => 'rental',
            'description' => "Location approuvée pour {$rental->car->brand} {$rental->car->model}",
            'data' => [
                'rental_id' => $rental->id,
                'car_id' => $rental->car_id,
                'user_id' => $rental->user_id
            ]
        ]);

        return back()->with('success', 'La demande de location a été approuvée.');
    }

    public function reject(Rental $rental)
    {
        $this->authorize('update', $rental);

        $rental->update(['status' => 'rejected']);

        // Record activity
        Activity::create([
            'agency_id' => $rental->agency_id,
            'type' => 'rental',
            'description' => "Location rejetée pour {$rental->car->brand} {$rental->car->model}",
            'data' => [
                'rental_id' => $rental->id,
                'car_id' => $rental->car_id,
                'user_id' => $rental->user_id
            ]
        ]);

        return back()->with('success', 'La demande de location a été rejetée.');
    }
} 