<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Agency;
use App\Models\Rental;
use Carbon\Carbon;

class CommissionTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques agences et locations existantes
        $agencies = Agency::take(5)->get();
        $rentals = Rental::take(20)->get();

        if ($agencies->isEmpty() || $rentals->isEmpty()) {
            $this->command->warn('Aucune agence ou location trouvée. Créez d\'abord des données de base.');
            return;
        }

        // Créer des transactions de commission admin pour les 6 derniers mois
        for ($i = 0; $i < 6; $i++) {
            $date = Carbon::now()->subMonths($i);
            
            // 5-10 transactions par mois
            $transactionCount = rand(5, 10);
            
            for ($j = 0; $j < $transactionCount; $j++) {
                $agency = $agencies->random();
                $rental = $rentals->random();
                
                // Montant de location aléatoire entre 300 et 1200 MAD
                $rentalAmount = rand(300, 1200);
                $adminCommission = $rentalAmount * 0.15; // 15% commission admin
                
                Transaction::create([
                    'agency_id' => $agency->id,
                    'rental_id' => $rental->id,
                    'type' => 'admin_commission',
                    'amount' => $adminCommission,
                    'balance_before' => 0,
                    'balance_after' => $adminCommission,
                    'description' => "Commission admin (15%) pour location #{$rental->id}",
                    'status' => 'completed',
                    'metadata' => [
                        'commission_rate' => 15.0,
                        'original_amount' => $rentalAmount,
                        'breakdown' => [
                            'admin_commission' => $adminCommission,
                            'agency_amount' => $rentalAmount - $adminCommission,
                            'platform_fee' => $rentalAmount * 0.05
                        ]
                    ],
                    'processed_at' => $date->copy()->addDays(rand(0, 29)),
                    'created_at' => $date->copy()->addDays(rand(0, 29)),
                    'updated_at' => $date->copy()->addDays(rand(0, 29))
                ]);
            }
        }

        $this->command->info('Transactions de commission admin créées avec succès !');
    }
}