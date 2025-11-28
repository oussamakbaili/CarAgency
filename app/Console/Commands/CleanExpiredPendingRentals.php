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
    protected $signature = 'rentals:clean-expired-pending {--hours=24 : Nombre d\'heures avant d\'expirer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les réservations pending qui n\'ont pas été complétées après un certain délai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $expirationTime = Carbon::now()->subHours($hours);

        $this->info("Nettoyage des réservations pending créées avant {$expirationTime->format('Y-m-d H:i:s')}...");

        // Trouver les réservations pending qui n'ont pas de paiement complété
        $expiredRentals = Rental::where('status', 'pending')
            ->where('created_at', '<', $expirationTime)
            ->whereDoesntHave('payments', function($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->with(['car', 'payments'])
            ->get();

        $cleanedCount = 0;

        foreach ($expiredRentals as $rental) {
            // Vérifier si le paiement est toujours pending ou a échoué
            $hasFailedPayment = $rental->payments()
                ->whereIn('status', [Payment::STATUS_FAILED, Payment::STATUS_CANCELLED])
                ->exists();

            // Si le paiement a échoué ou n'existe pas, annuler la réservation
            if ($hasFailedPayment || $rental->payments()->count() === 0) {
                $rental->update(['status' => 'rejected']);
                
                Log::info('Expired pending rental cleaned', [
                    'rental_id' => $rental->id,
                    'car_id' => $rental->car_id,
                    'created_at' => $rental->created_at,
                    'reason' => 'Payment not completed within time limit',
                ]);

                $cleanedCount++;
            }
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

