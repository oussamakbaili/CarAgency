<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Avis;
use App\Models\Rental;
use App\Models\Client;
use App\Models\Agency;

class AvisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get agencies and their completed rentals
        $agencies = Agency::all();
        
        $reviews = [
            [
                'title' => 'Excellent service!',
                'comment' => 'Très satisfait de la location. Voiture en parfait état, service client impeccable. Je recommande vivement cette agence.',
                'rating' => 5
            ],
            [
                'title' => 'Très bon rapport qualité-prix',
                'comment' => 'Voiture propre et bien entretenue. Le processus de réservation était simple et rapide.',
                'rating' => 4
            ],
            [
                'title' => 'Service correct',
                'comment' => 'Rien à redire, tout s\'est bien passé. La voiture était conforme à la description.',
                'rating' => 4
            ],
            [
                'title' => 'Parfait pour un voyage d\'affaires',
                'comment' => 'Idéal pour mes déplacements professionnels. Voiture confortable et fiable.',
                'rating' => 5
            ],
            [
                'title' => 'Bon service client',
                'comment' => 'L\'équipe est très professionnelle et à l\'écoute. Je recommande cette agence.',
                'rating' => 4
            ],
            [
                'title' => 'Expérience positive',
                'comment' => 'Location sans problème, voiture propre et en bon état. Je reviendrai certainement.',
                'rating' => 4
            ],
            [
                'title' => 'Excellent rapport qualité-prix',
                'comment' => 'Très satisfait du service. Voiture récente et bien entretenue. Prix très compétitif.',
                'rating' => 5
            ],
            [
                'title' => 'Service professionnel',
                'comment' => 'Agence sérieuse et professionnelle. Processus de location fluide et rapide.',
                'rating' => 4
            ],
            [
                'title' => 'Très satisfait',
                'comment' => 'Parfait pour mes besoins. Voiture fiable et confortable. Je recommande vivement.',
                'rating' => 5
            ],
            [
                'title' => 'Bon service',
                'comment' => 'Rien à redire, tout s\'est bien passé. Voiture conforme à mes attentes.',
                'rating' => 3
            ],
            [
                'title' => 'Excellent accueil',
                'comment' => 'Personnel très accueillant et professionnel. Voiture en parfait état.',
                'rating' => 5
            ],
            [
                'title' => 'Service correct',
                'comment' => 'Location sans problème. Voiture propre et bien entretenue.',
                'rating' => 3
            ],
            [
                'title' => 'Très bon service',
                'comment' => 'Agence fiable et professionnelle. Je recommande pour vos locations.',
                'rating' => 4
            ],
            [
                'title' => 'Parfait!',
                'comment' => 'Excellente expérience de location. Voiture impeccable et service au top.',
                'rating' => 5
            ],
            [
                'title' => 'Service satisfaisant',
                'comment' => 'Bon service dans l\'ensemble. Voiture propre et fonctionnelle.',
                'rating' => 4
            ]
        ];

        foreach ($agencies as $agency) {
            // Get completed rentals for this agency
            $rentals = Rental::where('agency_id', $agency->id)
                ->where('status', 'completed')
                ->with('user')
                ->get();

            // Create reviews for some of the rentals
            if ($rentals->count() > 0) {
                $rentalsToReview = $rentals->random(min(8, $rentals->count()));
                
                foreach ($rentalsToReview as $rental) {
                    // Find the client associated with this user
                    $client = Client::where('user_id', $rental->user_id)->first();
                    
                    // Skip if no client found
                    if (!$client) {
                        continue;
                    }
                    
                    $review = $reviews[array_rand($reviews)];
                    
                    Avis::create([
                        'rental_id' => $rental->id,
                        'client_id' => $client->id,
                        'agency_id' => $agency->id,
                        'title' => $review['title'],
                        'comment' => $review['comment'],
                        'rating' => $review['rating'],
                        'is_verified' => rand(0, 1), // Random verification status
                        'is_public' => true,
                        'created_at' => $rental->end_date->addDays(rand(1, 30)), // Review after rental end
                    ]);
                }
            }
        }
    }
}
