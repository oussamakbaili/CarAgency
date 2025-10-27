<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Car;
use App\Models\Agency;
use App\Models\User;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les véhicules et agences
        $cars = Car::all();
        $agencies = Agency::all();
        $users = User::where('role', 'client')->get();
        
        if ($cars->isEmpty() || $agencies->isEmpty() || $users->isEmpty()) {
            $this->command->info('Pas assez de données pour créer des avis. Exécutez d\'abord les autres seeders.');
            return;
        }

        $this->command->info('Création des avis pour les véhicules...');
        
        // Créer des avis pour les véhicules
        foreach ($cars as $car) {
            $reviewsCount = rand(3, 8); // 3-8 avis par véhicule
            
            for ($i = 0; $i < $reviewsCount; $i++) {
                $user = $users->random();
                $rating = $this->getRealisticRating();
                $comment = $this->getCarComment($rating);
                
                Review::create([
                    'user_id' => $user->id,
                    'car_id' => $car->id,
                    'review_type' => 'car',
                    'rating' => $rating,
                    'comment' => $comment,
                    'status' => 'approved',
                    'approved_at' => Carbon::now()->subDays(rand(1, 90)),
                    'created_at' => Carbon::now()->subDays(rand(1, 90)),
                ]);
            }
        }
        
        $this->command->info('Création des avis pour les agences...');
        
        // Créer des avis pour les agences
        foreach ($agencies as $agency) {
            $reviewsCount = rand(5, 12); // 5-12 avis par agence
            
            for ($i = 0; $i < $reviewsCount; $i++) {
                $user = $users->random();
                $rating = $this->getRealisticRating();
                $comment = $this->getAgencyComment($rating);
                
                Review::create([
                    'user_id' => $user->id,
                    'agency_id' => $agency->id,
                    'review_type' => 'agency',
                    'rating' => $rating,
                    'comment' => $comment,
                    'status' => 'approved',
                    'approved_at' => Carbon::now()->subDays(rand(1, 90)),
                    'created_at' => Carbon::now()->subDays(rand(1, 90)),
                ]);
            }
        }
        
        // Créer quelques avis en attente pour tester la modération
        $this->command->info('Création d\'avis en attente...');
        
        for ($i = 0; $i < 5; $i++) {
            $car = $cars->random();
            $user = $users->random();
            $rating = rand(1, 5);
            
            Review::create([
                'user_id' => $user->id,
                'car_id' => $car->id,
                'review_type' => 'car',
                'rating' => $rating,
                'comment' => 'Avis en attente de modération...',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(rand(1, 7)),
            ]);
        }
        
        $this->command->info('Avis créés avec succès !');
        $this->command->info('Total avis approuvés: ' . Review::where('status', 'approved')->count());
        $this->command->info('Total avis en attente: ' . Review::where('status', 'pending')->count());
    }
    
    private function getRealisticRating()
    {
        // Distribution réaliste des notes (plus de 4-5 étoiles)
        $weights = [5, 5, 4, 4, 4, 3, 3, 2, 1]; // Plus de chances pour les bonnes notes
        return $weights[array_rand($weights)];
    }
    
    private function getCarComment($rating)
    {
        $comments = [
            5 => [
                'Excellent véhicule ! Très propre et confortable.',
                'Parfait pour un voyage en famille. Je recommande vivement.',
                'Véhicule en excellent état, très agréable à conduire.',
                'Service impeccable et voiture de qualité. 5 étoiles !',
                'Très satisfait de cette location. Véhicule parfaitement entretenu.'
            ],
            4 => [
                'Très bon véhicule, petit bémol sur l\'état général mais rien de grave.',
                'Bonne expérience dans l\'ensemble, je recommande.',
                'Véhicule confortable et fiable pour mes déplacements.',
                'Service correct, véhicule propre et fonctionnel.',
                'Bonne qualité pour le prix, satisfait de ma location.'
            ],
            3 => [
                'Véhicule correct mais pourrait être mieux entretenu.',
                'Expérience moyenne, quelques petits problèmes mineurs.',
                'Acceptable pour un usage ponctuel.',
                'Véhicule fonctionnel mais pas exceptionnel.',
                'Correct sans plus, correspond aux attentes de base.'
            ],
            2 => [
                'Véhicule en mauvais état, plusieurs problèmes mécaniques.',
                'Déçu par l\'état du véhicule et le service.',
                'Problèmes de propreté et d\'entretien.',
                'Véhicule vieillissant, pas à la hauteur du prix.',
                'Service client décevant, véhicule en mauvais état.'
            ],
            1 => [
                'Très décevant, véhicule en très mauvais état.',
                'Problèmes majeurs, je ne recommande absolument pas.',
                'Véhicule dangereux, problèmes de sécurité.',
                'Service client inexistant, véhicule non conforme.',
                'Expérience catastrophique, à éviter.'
            ]
        ];
        
        $ratingComments = $comments[$rating] ?? $comments[3];
        return $ratingComments[array_rand($ratingComments)];
    }
    
    private function getAgencyComment($rating)
    {
        $comments = [
            5 => [
                'Agence exceptionnelle ! Service client au top.',
                'Très professionnelle, je recommande vivement.',
                'Excellent accueil et suivi personnalisé.',
                'Agence de confiance, service impeccable.',
                'Très satisfait du service, équipe très compétente.'
            ],
            4 => [
                'Bonne agence, service client correct.',
                'Professionnelle dans l\'ensemble, je recommande.',
                'Service de qualité, équipe sympathique.',
                'Bonne expérience, quelques améliorations possibles.',
                'Agence fiable, bon rapport qualité-prix.'
            ],
            3 => [
                'Service moyen, rien d\'exceptionnel.',
                'Correcte sans plus, pourrait mieux faire.',
                'Agence standard, service basique.',
                'Acceptable pour un usage ponctuel.',
                'Ni bon ni mauvais, dans la moyenne.'
            ],
            2 => [
                'Service client décevant, manque de professionnalisme.',
                'Problèmes de communication et de suivi.',
                'Agence peu réactive, service médiocre.',
                'Déçu par le service, pas à la hauteur.',
                'Problèmes d\'organisation et de gestion.'
            ],
            1 => [
                'Service client inexistant, très décevant.',
                'Agence à éviter, manque total de professionnalisme.',
                'Expérience catastrophique, service nul.',
                'Très déçu, ne correspond pas du tout à mes attentes.',
                'Service client déplorable, à éviter absolument.'
            ]
        ];
        
        $ratingComments = $comments[$rating] ?? $comments[3];
        return $ratingComments[array_rand($ratingComments)];
    }
}