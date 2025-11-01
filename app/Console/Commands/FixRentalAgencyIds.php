<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rental;

class FixRentalAgencyIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:fix-agency-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix rentals that have incorrect or missing agency_id by using the car relationship';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing rental agency_ids...');
        
        // Find all rentals with their car relationship
        $rentals = Rental::with('car')->get();
        
        $fixedCount = 0;
        $alreadyCorrectCount = 0;
        
        foreach ($rentals as $rental) {
            if (!$rental->car) {
                $this->warn("Rental #{$rental->id} has no associated car. Skipping...");
                continue;
            }
            
            $carAgencyId = $rental->car->agency_id;
            
            // Check if rental agency_id is missing or incorrect
            if ($rental->agency_id !== $carAgencyId) {
                $oldAgencyId = $rental->agency_id ?? 'NULL';
                $rental->agency_id = $carAgencyId;
                $rental->save();
                
                $this->info("Fixed Rental #{$rental->id}: agency_id changed from {$oldAgencyId} to {$carAgencyId}");
                $fixedCount++;
            } else {
                $alreadyCorrectCount++;
            }
        }
        
        $this->info("\n✅ Processing complete!");
        $this->info("   - Fixed: {$fixedCount} rental(s)");
        $this->info("   - Already correct: {$alreadyCorrectCount} rental(s)");
        $this->info("   - Total processed: " . $rentals->count() . " rental(s)");
        
        return Command::SUCCESS;
    }
}

