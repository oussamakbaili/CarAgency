<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::where('user_id', auth()->id())
            ->with(['car.agency.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.rentals.index', compact('rentals'));
    }

    public function show(Rental $rental)
    {
        // Make sure the rental belongs to the authenticated client
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        $rental->load(['car.agency.user']);
        return view('client.rentals.show', compact('rental'));
    }

    public function create(Car $car)
    {
        // Make sure car is available and from approved agency
        if ($car->status !== 'available' || $car->agency->status !== 'approved') {
            return redirect()->back()->with('error', 'This car is not available for rental.');
        }

        return view('client.rentals.create', compact('car'));
    }

    public function store(Request $request, Car $car)
    {
        // Validate the car is still available
        if ($car->status !== 'available' || $car->agency->status !== 'approved') {
            return redirect()->back()->with('error', 'This car is no longer available for rental.');
        }

        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate);
        
        // Check for conflicts
        $conflictingRentals = Rental::where('car_id', $car->id)
            ->where('status', 'approved')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        if ($conflictingRentals) {
            return redirect()->back()->with('error', 'The car is already booked for the selected dates.');
        }

        $totalPrice = $days * $car->price_per_day;

        Rental::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'agency_id' => $car->agency_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        return redirect()->route('client.rentals.index')
            ->with('success', 'Rental request submitted successfully! The agency will review your request.');
    }

    public function cancel(Rental $rental)
    {
        // Make sure the rental belongs to the authenticated client
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        // Only allow cancellation of pending rentals
        if ($rental->status !== 'pending') {
            return redirect()->back()->with('error', 'You can only cancel pending rental requests.');
        }

        $rental->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Rental request cancelled successfully.');
    }
}
