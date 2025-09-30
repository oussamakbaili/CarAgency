<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agency;
use App\Models\Rental;
use App\Services\AgencyCancellationService;

class TestCancellationSystem extends Command
{
    protected $signature = 'test:cancellation {agency_id}';
    protected $description = 'Test the cancellation system for an agency';

    public function handle()
    {
        $agencyId = $this->argument('agency_id');
        $agency = Agency::find($agencyId);
        
        if (!$agency) {
            $this->error("Agency with ID {$agencyId} not found.");
            return;
        }

        $this->info("Testing cancellation system for agency: {$agency->agency_name}");
        $this->info("Current cancellation count: {$agency->cancellation_count}");
        $this->info("Max cancellations: {$agency->max_cancellations}");
        $this->info("Is suspended: " . ($agency->isSuspended() ? 'Yes' : 'No'));
        
        $warningMessage = $agency->getCancellationWarningMessage();
        if ($warningMessage) {
            $this->warn("Warning message: {$warningMessage}");
        } else {
            $this->info("No warning message.");
        }

        // Test incrementing cancellation count
        $this->info("\nTesting cancellation increment...");
        $agency->incrementCancellationCount(999, 'test_rejection', 'Test rejection for system testing');
        
        $agency->refresh();
        $this->info("New cancellation count: {$agency->cancellation_count}");
        $this->info("Is suspended: " . ($agency->isSuspended() ? 'Yes' : 'No'));
        
        $warningMessage = $agency->getCancellationWarningMessage();
        if ($warningMessage) {
            $this->warn("New warning message: {$warningMessage}");
        } else {
            $this->info("No warning message.");
        }
    }
}