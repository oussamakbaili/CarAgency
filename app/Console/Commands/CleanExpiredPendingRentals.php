<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanExpiredPendingRentals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:clean-expired-pending {--hours= : Nombre d\'heures avant d\'expirer} {--minutes=15 : Nombre de minutes (utilisé si --hours non fourni, défaut 15)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les réservations pending non payées après 15 min (ou --hours) pour libérer les voitures';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoursOption = $this->option('hours');
        if ($hoursOption !== null && $hoursOption !== '') {
            $expirationTime = Carbon::now()->subHours((int) $hoursOption);
        } else {
            $minutes = (int) $this->option('minutes');
            $expirationTime = Carbon::now()->subMinutes($minutes);
        }

        $this->info("Nettoyage des réservations pending créées avant {$expirationTime->format('Y-m-d H:i:s')} (sans paiement complété)...");

        // Trouver les réservations pending qui n'ont pas de paiement complété
        $expiredRentals = Rental::where('status', 'pending')
            ->where('created_at', '<', $expirationTime)
            ->whereDoesntHave('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->with(['car', 'payments'])
            ->get();

        $cleanedCount = 0;

        foreach ($expiredRentals as $rental) {
            // Marquer les paiements pending de cette réservation comme annulés
            foreach ($rental->payments()->where('status', Payment::STATUS_PENDING)->get() as $payment) {
                $meta = $payment->metadata ?? [];
                $meta['expired_at'] = now()->toIso8601String();
                $meta['expired_reason'] = 'Unpaid pending rental expired';
                $payment->update(['status' => Payment::STATUS_CANCELLED, 'metadata' => $meta]);
            }

            $rental->update(['status' => 'rejected']);

            Log::info('Expired pending rental cleaned', [
                'rental_id' => $rental->id,
                'car_id' => $rental->car_id,
                'created_at' => $rental->created_at,
                'reason' => 'Payment not completed within time limit',
            ]);

            $cleanedCount++;
        }

        if ($cleanedCount > 0) {
            $this->info("✅ {$cleanedCount} réservation(s) pending expirée(s) ont été annulée(s).");
            $this->info("Les voitures sont maintenant disponibles à nouveau.");
        } else {
            $this->info("Aucune réservation pending expirée trouvée.");
        }

        return Command::SUCCESS;
    }
}

