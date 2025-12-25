<?php

namespace App\Services;

use App\Models\Rental;
use App\Models\Car;
use App\Models\Agency;
use App\Models\Transaction;
use Illuminate\Support\Facades\Schema;
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

            // Process commissions using the new CommissionService
            $commissionService = new \App\Services\CommissionService();
            $breakdown = $commissionService->processCommissions($rental);

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
     * Une voiture est indisponible si :
     * - Elle a une réservation pending, active ou confirmed pour les dates demandées
     * - Les réservations rejected, cancelled ou completed ne bloquent pas la disponibilité
     * - Elle n'est pas disponible sur un des sites externes (si configuré)
     */
    public function checkAvailability(Car $car, $startDate, $endDate, $excludeRentalId = null)
    {
        // Vérifier d'abord le statut de base de la voiture
        if ($car->status !== Car::STATUS_AVAILABLE) {
            return false;
        }

        // Vérifier le stock si le suivi de stock est activé
        if ($car->track_stock && $car->available_stock <= 0) {
            return false;
        }

        // Compter les réservations conflictuelles (pending, active)
        // Les réservations rejected, cancelled ou completed ne bloquent pas la disponibilité
        $conflictingRentals = Rental::where('car_id', $car->id)
            ->whereIn('status', ['pending', 'active'])
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

        // Si le stock est suivi, vérifier qu'il y a assez de stock
        if ($car->track_stock) {
            $localAvailable = $car->available_stock > $conflictCount;
        } else {
            // Si le stock n'est pas suivi, une seule réservation conflictuelle rend la voiture indisponible
            $localAvailable = $conflictCount === 0;
        }

        // Si le véhicule n'est pas disponible localement, il n'est pas disponible globalement
        if (!$localAvailable) {
            return false;
        }

        // Vérifier la disponibilité sur les sites externes si le véhicule est partagé (seulement si la table existe)
        if (Schema::hasTable('car_external_sites')) {
            try {
                if ($car->activeExternalSites()->exists()) {
                    $externalAvailabilityService = new \App\Services\ExternalSiteAvailabilityService();
                    $externalCheck = $externalAvailabilityService->checkExternalSitesAvailability($car, $startDate, $endDate);
                    
                    // Le véhicule est disponible seulement s'il est disponible localement ET sur tous les sites externes
                    return $externalCheck['available'];
                }
            } catch (\Exception $e) {
                \Log::warning('External availability check failed for car ' . $car->id . ': ' . $e->getMessage());
                // En cas d'erreur sur les sites externes, on reste sur la dispo locale
                return $localAvailable;
            }
        }

        // Si pas de sites externes, la disponibilité locale suffit
        return $localAvailable;
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