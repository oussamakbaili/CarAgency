<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rental;
use App\Models\Notification;
use App\Helpers\NotificationHelper;

class CreateMissingBookingNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:create-missing-booking {--agency_id= : Specific agency ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing notifications for pending bookings that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating missing booking notifications...');
        
        $agencyId = $this->option('agency_id');
        
        // Find all pending rentals
        $query = Rental::where('status', 'pending')
            ->with(['car', 'user', 'agency']);
            
        if ($agencyId) {
            $query->where(function($q) use ($agencyId) {
                $q->where('rentals.agency_id', $agencyId)
                  ->orWhereHas('car', function($carQuery) use ($agencyId) {
                      $carQuery->where('agency_id', $agencyId);
                  });
            });
        }
        
        $rentals = $query->get();
        
        $createdCount = 0;
        $skippedCount = 0;
        $errorCount = 0;
        
        foreach ($rentals as $rental) {
            // Skip if rental has no car or user
            if (!$rental->car || !$rental->user) {
                $this->warn("Rental #{$rental->id} has no car or user. Skipping...");
                $skippedCount++;
                continue;
            }
            
            // Determine the correct agency_id
            $agencyId = $rental->agency_id ?? $rental->car->agency_id;
            
            if (!$agencyId) {
                $this->warn("Rental #{$rental->id} has no agency_id. Skipping...");
                $skippedCount++;
                continue;
            }
            
            // Fix rental agency_id if needed
            if ($rental->agency_id !== $agencyId) {
                $rental->agency_id = $agencyId;
                $rental->save();
                $this->info("Fixed Rental #{$rental->id}: agency_id set to {$agencyId}");
            }
            
            // Check if notification already exists
            $existingNotification = Notification::where('agency_id', $agencyId)
                ->where('type', 'booking')
                ->where('related_id', $rental->id)
                ->first();
                
            if ($existingNotification) {
                $this->line("Rental #{$rental->id} already has a notification. Skipping...");
                $skippedCount++;
                continue;
            }
            
            // Create notification
            try {
                NotificationHelper::notifyNewBooking($agencyId, $rental, $rental->car, $rental->user);
                $this->info("✅ Created notification for Rental #{$rental->id} (Agency: {$agencyId})");
                $createdCount++;
            } catch (\Exception $e) {
                $this->error("❌ Error creating notification for Rental #{$rental->id}: " . $e->getMessage());
                $errorCount++;
            }
        }
        
        $this->info("\n✅ Processing complete!");
        $this->info("   - Notifications created: {$createdCount}");
        $this->info("   - Skipped: {$skippedCount}");
        $this->info("   - Errors: {$errorCount}");
        $this->info("   - Total processed: " . $rentals->count() . " rental(s)");
        
        return Command::SUCCESS;
    }
}

