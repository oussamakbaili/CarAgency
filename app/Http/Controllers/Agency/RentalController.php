<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Activity;
use App\Services\RentalService;

class RentalController extends Controller
{
    public function pending()
    {
        $agency = auth()->user()->agency;
        
        $pendingRentals = Rental::where('agency_id', $agency->id)
            ->where('status', 'pending')
            ->with(['car', 'user', 'agency'])
            ->latest()
            ->paginate(10);

        return view('agence.rentals.pending', compact('pendingRentals'));
    }

    public function approve(Rental $rental, RentalService $rentalService)
    {
        $this->authorize('update', $rental);

        try {
            $rentalService->approveRental($rental);
            return back()->with('success', 'La demande de location a été approuvée et le paiement a été traité.');
        } catch (\Exception $e) {
            \Log::error('Rental approval error: ' . $e->getMessage(), [
                'rental_id' => $rental->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors de l\'approbation: ' . $e->getMessage());
        }
    }

    public function reject(Rental $rental, RentalService $rentalService)
    {
        $this->authorize('update', $rental);

        try {
            // Simple rejection without the service for debugging
            $rental->update(['status' => Rental::STATUS_REJECTED]);
            
            // Log the successful rejection
            \Log::info('Rental rejected successfully', ['rental_id' => $rental->id]);
            
            return back()->with('success', 'La demande de location a été rejetée (version simple).');
            
        } catch (\Exception $e) {
            \Log::error('Rental rejection error: ' . $e->getMessage(), [
                'rental_id' => $rental->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors du rejet: ' . $e->getMessage());
        }
    }
} 