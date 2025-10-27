<?php

namespace App\Services;

use App\Models\Rental;
use App\Models\Transaction;
use App\Models\Agency;
use Illuminate\Support\Facades\DB;
use Exception;

class CommissionService
{
    // Commission fixe de 15% pour l'admin
    const ADMIN_COMMISSION_RATE = 15.0;
    
    // Frais de plateforme de 5% (existants)
    const PLATFORM_FEE_RATE = 5.0;

    /**
     * Calcule la répartition des prix pour une réservation
     */
    public function calculatePricingBreakdown($totalPrice, $agencyCommissionRate = null)
    {
        $breakdown = [
            'total_price' => $totalPrice,
            'platform_fee' => $totalPrice * (self::PLATFORM_FEE_RATE / 100),
            'admin_commission' => $totalPrice * (self::ADMIN_COMMISSION_RATE / 100),
            'agency_commission_rate' => $agencyCommissionRate ?? 0,
            'agency_commission' => 0,
            'agency_amount' => 0,
            'net_amount' => 0
        ];

        // Calculer la commission agence si spécifiée
        if ($agencyCommissionRate !== null) {
            $breakdown['agency_commission'] = $totalPrice * ($agencyCommissionRate / 100);
        }

        // Calculer le montant net pour l'agence
        $breakdown['agency_amount'] = $totalPrice - $breakdown['admin_commission'] - $breakdown['agency_commission'];
        
        // Montant net total (après toutes les commissions)
        $breakdown['net_amount'] = $totalPrice - $breakdown['admin_commission'] - $breakdown['platform_fee'];

        return $breakdown;
    }

    /**
     * Traite les commissions pour une réservation approuvée
     */
    public function processCommissions(Rental $rental)
    {
        return DB::transaction(function () use ($rental) {
            // Charger les relations nécessaires
            $rental->load(['car', 'agency']);
            
            // Calculer la répartition des prix
            $breakdown = $this->calculatePricingBreakdown(
                $rental->total_price, 
                $rental->agency->commission_rate
            );

            // 1. Transaction pour la commission admin (15%)
            Transaction::create([
                'agency_id' => $rental->agency_id,
                'rental_id' => $rental->id,
                'type' => 'admin_commission',
                'amount' => $breakdown['admin_commission'],
                'description' => "Commission admin (15%) pour location #{$rental->id}",
                'status' => 'completed',
                'metadata' => [
                    'commission_rate' => self::ADMIN_COMMISSION_RATE,
                    'original_amount' => $rental->total_price,
                    'breakdown' => $breakdown
                ],
                'processed_at' => now()
            ]);

            // 2. Transaction pour la commission agence (si applicable)
            if ($breakdown['agency_commission'] > 0) {
                Transaction::create([
                    'agency_id' => $rental->agency_id,
                    'rental_id' => $rental->id,
                    'type' => 'agency_commission',
                    'amount' => -$breakdown['agency_commission'], // Négatif car déduction
                    'description' => "Commission agence ({$rental->agency->commission_rate}%) pour location #{$rental->id}",
                    'status' => 'completed',
                    'metadata' => [
                        'commission_rate' => $rental->agency->commission_rate,
                        'original_amount' => $rental->total_price,
                        'breakdown' => $breakdown
                    ],
                    'processed_at' => now()
                ]);
            }

            // 3. Transaction pour le paiement à l'agence (montant net)
            Transaction::create([
                'agency_id' => $rental->agency_id,
                'rental_id' => $rental->id,
                'type' => 'rental_payment',
                'amount' => $breakdown['agency_amount'],
                'description' => "Paiement net pour location #{$rental->id} - {$rental->car->brand} {$rental->car->model}",
                'status' => 'completed',
                'metadata' => [
                    'original_amount' => $rental->total_price,
                    'admin_commission' => $breakdown['admin_commission'],
                    'agency_commission' => $breakdown['agency_commission'],
                    'platform_fee' => $breakdown['platform_fee'],
                    'breakdown' => $breakdown
                ],
                'processed_at' => now()
            ]);

            // Mettre à jour les gains de l'agence
            $rental->agency->increment('total_earnings', $breakdown['agency_amount']);

            return $breakdown;
        });
    }

    /**
     * Récupère les statistiques de commission pour l'admin
     */
    public function getAdminCommissionStats($period = 'month')
    {
        if ($period === 'month') {
            $stats = Transaction::where('type', 'admin_commission')
                ->selectRaw("
                    MONTH(created_at) as month,
                    YEAR(created_at) as year,
                    COUNT(*) as transaction_count,
                    SUM(amount) as total_commission,
                    AVG(amount) as avg_commission
                ")
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
        } else {
            $stats = Transaction::where('type', 'admin_commission')
                ->selectRaw("
                    DATE(created_at) as period,
                    COUNT(*) as transaction_count,
                    SUM(amount) as total_commission,
                    AVG(amount) as avg_commission
                ")
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('period')
                ->orderBy('period', 'desc')
                ->get();
        }

        return $stats;
    }

    /**
     * Récupère le total des commissions admin
     */
    public function getTotalAdminCommission($startDate = null, $endDate = null)
    {
        $query = Transaction::where('type', 'admin_commission');
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    /**
     * Valide les calculs de commission pour une réservation
     */
    public function validateCommissionCalculation(Rental $rental)
    {
        $breakdown = $this->calculatePricingBreakdown(
            $rental->total_price, 
            $rental->agency->commission_rate
        );

        $expectedTotal = $breakdown['admin_commission'] + 
                        $breakdown['agency_commission'] + 
                        $breakdown['agency_amount'];

        $isValid = abs($expectedTotal - $rental->total_price) < 0.01; // Tolérance de 1 centime

        return [
            'is_valid' => $isValid,
            'breakdown' => $breakdown,
            'expected_total' => $expectedTotal,
            'actual_total' => $rental->total_price,
            'difference' => $expectedTotal - $rental->total_price
        ];
    }
}
