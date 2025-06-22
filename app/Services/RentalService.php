<?php

namespace App\Services;

use App\Models\Rental;
use App\Models\Car;
use App\Models\Agency;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class RentalService
{
    /**
     * Approve a rental with smart stock and payment management
     */
    public function approveRental(Rental $rental)
    {
        return DB::transaction(function () use ($rental) {
            // Load required relationships
            $rental->load(['car', 'agency']);
            
            // Check if car is still available
            if (!$rental->car->hasStock()) {
                throw new Exception('Cette voiture n\'est plus disponible en stock.');
            }

            // Reserve the car stock
            if (!$rental->car->reserveStock()) {
                throw new Exception('Impossible de réserver cette voiture.');
            }

            // Calculate commission
            $commissionRate = $rental->agency->commission_rate / 100;
            $commissionAmount = $rental->total_price * $commissionRate;
            $agencyAmount = $rental->total_price - $commissionAmount;

            // Create payment transaction for agency
            Transaction::createTransaction(
                $rental->agency_id,
                Transaction::TYPE_RENTAL_PAYMENT,
                $agencyAmount,
                "Paiement pour location #{$rental->id} - {$rental->car->brand} {$rental->car->model}",
                $rental->id,
                [
                    'commission_rate' => $rental->agency->commission_rate,
                    'commission_amount' => $commissionAmount,
                    'original_amount' => $rental->total_price
                ]
            );

            // Update agency earnings
            $rental->agency->increment('total_earnings', $agencyAmount);

            // Update rental status
            $rental->update(['status' => Rental::STATUS_APPROVED]);

            // Create activity log
            \App\Models\Activity::create([
                'agency_id' => $rental->agency_id,
                'type' => 'rental',
                'description' => "Location approuvée pour {$rental->car->brand} {$rental->car->model} - Montant: {$agencyAmount} DH",
                'data' => [
                    'rental_id' => $rental->id,
                    'car_id' => $rental->car_id,
                    'user_id' => $rental->user_id,
                    'amount' => $agencyAmount,
                    'commission' => $commissionAmount
                ]
            ]);

            return $rental;
        });
    }

    /**
     * Reject a rental and restore stock
     */
    public function rejectRental(Rental $rental)
    {
        return DB::transaction(function () use ($rental) {
            // Load required relationships
            $rental->load(['car', 'agency']);
            
            // Update rental status
            $rental->update(['status' => Rental::STATUS_REJECTED]);

            // Create activity log
            \App\Models\Activity::create([
                'agency_id' => $rental->agency_id,
                'type' => 'rental',
                'description' => "Location rejetée pour {$rental->car->brand} {$rental->car->model}",
                'data' => [
                    'rental_id' => $rental->id,
                    'car_id' => $rental->car_id,
                    'user_id' => $rental->user_id
                ]
            ]);

            return $rental;
        });
    }

    /**
     * Cancel a rental and restore stock + refund if needed
     */
    public function cancelRental(Rental $rental)
    {
        return DB::transaction(function () use ($rental) {
            // Load required relationships
            $rental->load(['car', 'agency']);
            
            // If rental was approved, release the stock
            if ($rental->status === 'active') {
                $rental->car->releaseStock();

                // Calculate refund (could be partial based on business rules)
                $refundAmount = $this->calculateRefund($rental);
                
                if ($refundAmount > 0) {
                    // Create refund transaction (negative amount to deduct from agency)
                    Transaction::createTransaction(
                        $rental->agency_id,
                        Transaction::TYPE_REFUND,
                        -$refundAmount,
                        "Remboursement pour annulation de location #{$rental->id}",
                        $rental->id
                    );

                    // Update agency earnings
                    $rental->agency->decrement('total_earnings', $refundAmount);
                }
            }

            // Update rental status
            $rental->update(['status' => Rental::STATUS_CANCELLED]);

            return $rental;
        });
    }

    /**
     * Complete a rental when the rental period ends
     */
    public function completeRental(Rental $rental)
    {
        return DB::transaction(function () use ($rental) {
            // Load required relationships
            $rental->load(['car', 'agency']);
            
            // Release the car stock
            $rental->car->releaseStock();

            // Update rental status
            $rental->update(['status' => Rental::STATUS_COMPLETED]);

            // Create activity log
            \App\Models\Activity::create([
                'agency_id' => $rental->agency_id,
                'type' => 'rental',
                'description' => "Location terminée pour {$rental->car->brand} {$rental->car->model}",
                'data' => [
                    'rental_id' => $rental->id,
                    'car_id' => $rental->car_id,
                    'user_id' => $rental->user_id
                ]
            ]);

            return $rental;
        });
    }

    /**
     * Check for rentals that should be automatically completed
     */
    public function processExpiredRentals()
    {
        $expiredRentals = Rental::where('status', 'active')
            ->where('end_date', '<', now())
            ->with(['car', 'agency'])
            ->get();

        foreach ($expiredRentals as $rental) {
            $this->completeRental($rental);
        }

        return $expiredRentals->count();
    }

    /**
     * Check car availability for rental period
     */
    public function checkAvailability(Car $car, $startDate, $endDate, $excludeRentalId = null)
    {
        // Check if car has stock
        if (!$car->hasStock()) {
            return false;
        }

        // Count conflicting rentals
        $conflictingRentals = Rental::where('car_id', $car->id)
            ->whereIn('status', ['active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            });

        if ($excludeRentalId) {
            $conflictingRentals->where('id', '!=', $excludeRentalId);
        }

        $conflictCount = $conflictingRentals->count();

        // Check if we have enough stock for the period
        return $car->available_stock > $conflictCount;
    }

    /**
     * Calculate refund amount based on business rules
     */
    private function calculateRefund(Rental $rental)
    {
        // Simple refund logic - you can make this more sophisticated
        $daysSinceApproval = now()->diffInDays($rental->updated_at);
        
        if ($daysSinceApproval <= 1) {
            // Full refund if cancelled within 24 hours
            return $rental->total_price * 0.9; // 90% refund (10% processing fee)
        } elseif ($daysSinceApproval <= 7) {
            // 50% refund if cancelled within a week
            return $rental->total_price * 0.5;
        }
        
        // No refund after a week
        return 0;
    }

    /**
     * Get rental statistics for an agency
     */
    public function getAgencyStatistics(Agency $agency)
    {
        return [
            'total_rentals' => $agency->rentals()->count(),
            'pending_rentals' => $agency->rentals()->where('status', Rental::STATUS_PENDING)->count(),
            'approved_rentals' => $agency->rentals()->where('status', Rental::STATUS_APPROVED)->count(),
            'completed_rentals' => $agency->rentals()->where('status', Rental::STATUS_COMPLETED)->count(),
            'cancelled_rentals' => $agency->rentals()->where('status', Rental::STATUS_CANCELLED)->count(),
            'total_earnings' => $agency->total_earnings,
            'current_balance' => $agency->balance,
            'available_cars' => $agency->cars()->where('available_stock', '>', 0)->count(),
        ];
    }
} 