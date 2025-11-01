<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Agency;
use App\Models\Notification;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\DB;

class FixAllRentalsAndNotifications extends Command
{
    protected $signature = 'rentals:fix-all';
    protected $description = 'Fix all rentals agency_id and create missing notifications';

    public function handle()
    {
        $this->info('=== CORRECTION COMPLÈTE DES RÉSERVATIONS ===');
        $this->newLine();
        
        DB::beginTransaction();
        try {
            // Step 1: Fix all rentals agency_id
            $this->info('1. Correction des agency_id...');
            $rentals = Rental::with('car')->get();
            $fixedCount = 0;
            $alreadyCorrectCount = 0;
            
            foreach ($rentals as $rental) {
                if (!$rental->car) {
                    $this->warn("   ⚠️  Réservation #{$rental->id}: Pas de voiture associée");
                    continue;
                }
                
                $carAgencyId = $rental->car->agency_id;
                
                if ($rental->agency_id !== $carAgencyId) {
                    $oldAgencyId = $rental->agency_id ?? 'NULL';
                    $rental->agency_id = $carAgencyId;
                    $rental->save();
                    
                    $fixedCount++;
                    if ($fixedCount <= 10) { // Only show first 10
                        $this->info("   ✅ Réservation #{$rental->id}: {$oldAgencyId} → {$carAgencyId}");
                    }
                } else {
                    $alreadyCorrectCount++;
                }
            }
            
            $this->info("   - Corrigées: {$fixedCount}");
            $this->info("   - Déjà correctes: {$alreadyCorrectCount}");
            $this->newLine();
            
            // Step 2: Create missing notifications for pending rentals
            $this->info('2. Création des notifications manquantes...');
            $pendingRentals = Rental::where('status', 'pending')
                ->with(['car', 'user', 'agency'])
                ->get();
                
            $createdCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            
            foreach ($pendingRentals as $rental) {
                if (!$rental->car || !$rental->user) {
                    $this->warn("   ⚠️  Réservation #{$rental->id}: Données incomplètes");
                    $skippedCount++;
                    continue;
                }
                
                $agencyId = $rental->agency_id ?? $rental->car->agency_id;
                
                if (!$agencyId) {
                    $this->warn("   ⚠️  Réservation #{$rental->id}: Pas d'agency_id");
                    $skippedCount++;
                    continue;
                }
                
                // Check if notification exists
                $existingNotification = Notification::where('agency_id', $agencyId)
                    ->where('related_id', $rental->id)
                    ->where('type', 'booking')
                    ->first();
                    
                if ($existingNotification) {
                    $skippedCount++;
                    continue;
                }
                
                // Create notification
                try {
                    NotificationHelper::notifyNewBooking($agencyId, $rental, $rental->car, $rental->user);
                    $createdCount++;
                    if ($createdCount <= 10) { // Only show first 10
                        $this->info("   ✅ Notification créée pour réservation #{$rental->id} (Agence: {$agencyId})");
                    }
                } catch (\Exception $e) {
                    $this->error("   ❌ Erreur réservation #{$rental->id}: " . $e->getMessage());
                    $errorCount++;
                }
            }
            
            $this->info("   - Notifications créées: {$createdCount}");
            $this->info("   - Ignorées: {$skippedCount}");
            $this->info("   - Erreurs: {$errorCount}");
            $this->newLine();
            
            // Step 3: Verify fix
            $this->info('3. Vérification...');
            $testAgencies = Agency::all();
            foreach ($testAgencies as $agency) {
                $count = Rental::forAgency($agency->id)
                    ->where('status', 'pending')
                    ->count();
                $this->info("   Agence #{$agency->id} ({$agency->agency_name}): {$count} réservation(s) en attente");
            }
            
            DB::commit();
            
            $this->newLine();
            $this->info('✅ Correction terminée avec succès!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erreur lors de la correction: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}

