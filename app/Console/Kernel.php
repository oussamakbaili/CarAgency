<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Libérer les voitures : réservations non payées après 15 min (toutes les 5 min)
        $schedule->command('rentals:clean-expired-pending --minutes=15')->everyFiveMinutes();

        // Nettoyer aussi les réservations pending très anciennes (24h) toutes les heures
        $schedule->command('rentals:clean-expired-pending --hours=24')->hourly();

        // Traiter les réservations expirées
        $schedule->command('rentals:process-expired')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
