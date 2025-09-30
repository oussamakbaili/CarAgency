<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use App\Services\RentalService;
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
        if (!$car->is_available || $car->agency->status !== 'approved') {
            return redirect()->back()->with('error', 'This car is not available for rental.');
        }

        return view('client.rentals.create', compact('car'));
    }

    public function store(Request $request, Car $car, RentalService $rentalService)
    {
        // Validate the car is still available
        if (!$car->is_available || $car->agency->status !== 'approved') {
            return redirect()->back()->with('error', 'This car is no longer available for rental.');
        }

        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'terms_accepted' => 'required|accepted',
        ], [
            'terms_accepted.required' => 'Vous devez accepter les conditions de non-responsabilité pour continuer.',
            'terms_accepted.accepted' => 'Vous devez accepter les conditions de non-responsabilité pour continuer.',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate);
        
        // Check availability using the smart service
        if (!$rentalService->checkAvailability($car, $startDate, $endDate)) {
            return redirect()->back()->with('error', 'The car is not available for the selected dates.');
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

    public function cancel(Rental $rental, RentalService $rentalService)
    {
        // Make sure the rental belongs to the authenticated client
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        // Allow cancellation of pending and approved rentals
        if (!in_array($rental->status, ['pending', 'active'])) {
            return redirect()->back()->with('error', 'This rental cannot be cancelled.');
        }

        try {
            $rentalService->cancelRental($rental);
            $message = $rental->status === 'active' 
                ? 'Rental cancelled successfully. Refund will be processed if applicable.'
                : 'Rental request cancelled successfully.';
                
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error cancelling rental: ' . $e->getMessage());
        }
    }

    /**
     * Get unavailable dates for a car
     */
    public function getUnavailableDates(Car $car)
    {
        // Get all rentals that are pending, approved, or active
        $unavailableRentals = Rental::where('car_id', $car->id)
            ->whereIn('status', ['pending', 'active'])
            ->where('end_date', '>=', now())
            ->get();

        $unavailableDates = [];
        
        foreach ($unavailableRentals as $rental) {
            $startDate = \Carbon\Carbon::parse($rental->start_date);
            $endDate = \Carbon\Carbon::parse($rental->end_date);
            
            // Add all dates in the rental period
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $unavailableDates[] = $currentDate->format('Y-m-d');
                $currentDate->addDay();
            }
        }

        return response()->json([
            'unavailable_dates' => array_unique($unavailableDates)
        ]);
    }
}
