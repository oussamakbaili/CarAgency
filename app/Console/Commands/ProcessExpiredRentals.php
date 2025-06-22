<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RentalService;

class ProcessExpiredRentals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:process-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired rentals and mark them as completed';

    /**
     * Execute the console command.
     */
    public function handle(RentalService $rentalService)
    {
        $this->info('Processing expired rentals...');
        
        $processedCount = $rentalService->processExpiredRentals();
        
        if ($processedCount > 0) {
            $this->info("Successfully processed {$processedCount} expired rental(s).");
        } else {
            $this->info('No expired rentals found.');
        }
        
        return Command::SUCCESS;
    }
}
