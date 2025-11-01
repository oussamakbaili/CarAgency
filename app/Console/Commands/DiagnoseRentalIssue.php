<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Agency;
use App\Models\Notification;

class DiagnoseRentalIssue extends Command
{
    protected $signature = 'rentals:diagnose';
    protected $description = 'Diagnose why rentals are not appearing for agencies';

    public function handle()
    {
        $this->info('=== DIAGNOSTIC DES RÉSERVATIONS ===');
        $this->newLine();
        
        // 1. Check all pending rentals
        $this->info('1. Analyse des réservations en attente...');
        $pendingRentals = Rental::where('status', 'pending')
            ->with(['car', 'user', 'agency'])
            ->get();
            
        $this->info("   Total de réservations en attente: " . $pendingRentals->count());
        
        $issues = [];
        foreach ($pendingRentals as $rental) {
            $rentalIssues = [];
            
            // Check agency_id
            if (!$rental->agency_id) {
                $rentalIssues[] = "agency_id est NULL";
            } else {
                $agency = Agency::find($rental->agency_id);
                if (!$agency) {
                    $rentalIssues[] = "agency_id {$rental->agency_id} n'existe pas";
                }
            }
            
            // Check car relation
            if (!$rental->car) {
                $rentalIssues[] = "Pas de voiture associée (car_id: {$rental->car_id})";
            } else {
                if ($rental->car->agency_id !== $rental->agency_id) {
                    $rentalIssues[] = "agency_id mismatch: rental={$rental->agency_id}, car={$rental->car->agency_id}";
                }
            }
            
            // Check user
            if (!$rental->user) {
                $rentalIssues[] = "Pas d'utilisateur associé";
            }
            
            // Check notification
            $notification = Notification::where('agency_id', $rental->agency_id)
                ->where('related_id', $rental->id)
                ->where('type', 'booking')
                ->first();
                
            if (!$notification) {
                $rentalIssues[] = "Pas de notification créée";
            }
            
            if (!empty($rentalIssues)) {
                $issues[] = [
                    'rental_id' => $rental->id,
                    'agency_id' => $rental->agency_id,
                    'car_id' => $rental->car_id,
                    'issues' => $rentalIssues
                ];
                
                $this->warn("   ⚠️  Réservation #{$rental->id}:");
                foreach ($rentalIssues as $issue) {
                    $this->line("      - {$issue}");
                }
            }
        }
        
        // 2. Test query for each agency
        $this->newLine();
        $this->info('2. Test des requêtes par agence...');
        $agencies = Agency::all();
        
        foreach ($agencies as $agency) {
            $countDirect = Rental::where('rentals.agency_id', $agency->id)
                ->where('status', 'pending')
                ->count();
                
            $countViaCar = Rental::whereHas('car', function($q) use ($agency) {
                    $q->where('agency_id', $agency->id);
                })
                ->where('status', 'pending')
                ->count();
                
            $countCombined = Rental::where(function($q) use ($agency) {
                    $q->where('rentals.agency_id', $agency->id)
                      ->orWhereHas('car', function($carQuery) use ($agency) {
                          $carQuery->where('agency_id', $agency->id);
                      });
                })
                ->where('status', 'pending')
                ->count();
                
            $this->info("   Agence #{$agency->id} ({$agency->agency_name}):");
            $this->line("      - Via agency_id direct: {$countDirect}");
            $this->line("      - Via relation car: {$countViaCar}");
            $this->line("      - Requête combinée: {$countCombined}");
        }
        
        // 3. Summary
        $this->newLine();
        $this->info('3. Résumé:');
        if (empty($issues)) {
            $this->info('   ✅ Aucun problème détecté dans les données');
        } else {
            $this->error('   ❌ ' . count($issues) . ' réservation(s) avec des problèmes détectés');
        }
        
        return Command::SUCCESS;
    }
}

