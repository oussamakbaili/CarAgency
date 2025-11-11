<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use App\Services\RentalService;
use App\Helpers\NotificationHelper;
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

    public function confirm(Request $request, Car $car, RentalService $rentalService)
    {
        // Si c'est un retour après paiement PayPal, utiliser les données de session
        $paymentSuccess = $request->query('payment_success');
        $rentalId = session('booking_data.rental_id');
        
        if ($paymentSuccess === 'paypal' && $rentalId) {
            // Récupérer la réservation depuis la session
            $rental = Rental::find($rentalId);
            if ($rental && $rental->car_id == $car->id) {
                $startDate = $rental->start_date;
                $endDate = $rental->end_date;
                $days = $startDate->diffInDays($endDate);
                
                // Récupérer les données de booking depuis la session
                $bookingData = session('booking_data', []);
                $totalPrice = $bookingData['total_price'] ?? ($days * $car->client_price_per_day);
                $platformFee = 0; // Commission already included in client_price_per_day
                $totalWithFees = $bookingData['total_with_fees'] ?? $totalPrice;
                
                $car->load(['agency.user', 'category']);
                
                return view('client.rentals.confirm', compact('car', 'startDate', 'endDate', 'days', 'totalPrice', 'platformFee', 'totalWithFees'));
            }
        }
        
        // Sinon, valider les dates normalement
        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Check availability
        if (!$rentalService->checkAvailability($car, $startDate, $endDate)) {
            return redirect()->back()->with('error', 'Ce véhicule n\'est pas disponible pour les dates sélectionnées.');
        }

        // Calculate pricing with client commission already included
        $days = $startDate->diffInDays($endDate);
        $clientPricePerDay = $car->client_price_per_day;
        $totalPrice = $days * $clientPricePerDay;
        
        // Store booking data in session
        session([
            'booking_data' => [
                'car_id' => $car->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'days' => $days,
                'price_per_day' => $clientPricePerDay,
                'total_price' => $totalPrice,
                'platform_fee' => 0, // Commission already included in client_price_per_day
                'total_with_fees' => $totalPrice,
            ]
        ]);

        $car->load(['agency.user', 'category']);
        
        $platformFee = 0; // Commission already included in client_price_per_day
        $totalWithFees = $totalPrice;
        
        return view('client.rentals.confirm', compact('car', 'startDate', 'endDate', 'days', 'totalPrice', 'platformFee', 'totalWithFees'));
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

        // S'assurer que l'agency_id est défini
        $agencyId = $car->agency_id;
        if (!$agencyId) {
            return redirect()->back()->with('error', 'La voiture n\'est associée à aucune agence.');
        }

        $rental = Rental::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'agency_id' => $agencyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        // Créer une notification pour l'agence
        try {
            NotificationHelper::notifyNewBooking($agencyId, $rental, $car, auth()->user());
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la création de la réservation
            \Log::error('Failed to create booking notification: ' . $e->getMessage(), [
                'rental_id' => $rental->id,
                'agency_id' => $agencyId,
                'error' => $e->getTraceAsString()
            ]);
        }

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

    /**
     * Check availability for specific dates
     */
    public function checkAvailability(Request $request, Car $car, RentalService $rentalService)
    {
        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $isAvailable = $rentalService->checkAvailability($car, $startDate, $endDate);
        
        $days = $startDate->diffInDays($endDate);
        $clientPricePerDay = $car->client_price_per_day;
        $totalPrice = $days * $clientPricePerDay;

        return response()->json([
            'available' => $isAvailable,
            'days' => $days,
            'total_price' => $totalPrice,
            'total_price_with_fees' => $totalPrice, // Commission already included
            'markup_rate' => config('app.client_markup_rate', 15),
            'price_per_day' => $clientPricePerDay,
            'message' => $isAvailable 
                ? 'Véhicule disponible pour ces dates' 
                : 'Véhicule non disponible pour ces dates'
        ]);
    }
}
