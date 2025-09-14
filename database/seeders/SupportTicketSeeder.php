<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupportTicket;
use App\Models\Client;
use App\Models\Agency;
use App\Models\Rental;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agencies = Agency::all();
        
        $ticketTemplates = [
            [
                'subject' => 'Problème avec la réservation',
                'description' => 'Je n\'arrive pas à modifier ma réservation en ligne. Le système me donne une erreur à chaque tentative.',
                'category' => 'booking',
                'priority' => 'medium'
            ],
            [
                'subject' => 'Facture incorrecte',
                'description' => 'Ma facture ne correspond pas au prix convenu lors de la réservation. Il y a des frais supplémentaires non expliqués.',
                'category' => 'billing',
                'priority' => 'high'
            ],
            [
                'subject' => 'Voiture en panne',
                'description' => 'La voiture que j\'ai louée a un problème de freinage. Je ne me sens pas en sécurité pour conduire.',
                'category' => 'technical',
                'priority' => 'urgent'
            ],
            [
                'subject' => 'Service client décevant',
                'description' => 'L\'accueil à l\'agence était très désagréable. Le personnel n\'était pas professionnel.',
                'category' => 'complaint',
                'priority' => 'medium'
            ],
            [
                'subject' => 'Question sur l\'assurance',
                'description' => 'J\'aimerais savoir quelles sont les options d\'assurance disponibles et leurs tarifs.',
                'category' => 'general',
                'priority' => 'low'
            ],
            [
                'subject' => 'Problème de paiement',
                'description' => 'Mon paiement a été refusé mais l\'argent a été débité de mon compte. Que faire ?',
                'category' => 'billing',
                'priority' => 'high'
            ],
            [
                'subject' => 'Retard de livraison',
                'description' => 'Ma voiture n\'était pas prête à l\'heure convenue. J\'ai dû attendre 2 heures supplémentaires.',
                'category' => 'booking',
                'priority' => 'medium'
            ],
            [
                'subject' => 'Climatisation défaillante',
                'description' => 'La climatisation de la voiture ne fonctionne pas correctement. Il fait très chaud à l\'intérieur.',
                'category' => 'technical',
                'priority' => 'medium'
            ],
            [
                'subject' => 'Demande de remboursement',
                'description' => 'Je souhaite annuler ma réservation et obtenir un remboursement complet.',
                'category' => 'billing',
                'priority' => 'high'
            ],
            [
                'subject' => 'Problème avec l\'application',
                'description' => 'L\'application mobile ne se connecte pas à mon compte. Je ne peux pas accéder à mes réservations.',
                'category' => 'technical',
                'priority' => 'medium'
            ],
            [
                'subject' => 'Question sur les frais',
                'description' => 'Pouvez-vous m\'expliquer les frais de carburant et les options de retour ?',
                'category' => 'general',
                'priority' => 'low'
            ],
            [
                'subject' => 'Voiture sale',
                'description' => 'La voiture que j\'ai récupérée était très sale à l\'intérieur. Ce n\'est pas acceptable.',
                'category' => 'complaint',
                'priority' => 'medium'
            ],
            [
                'subject' => 'Problème de navigation GPS',
                'description' => 'Le GPS de la voiture ne fonctionne pas. J\'ai eu du mal à me repérer.',
                'category' => 'technical',
                'priority' => 'low'
            ],
            [
                'subject' => 'Modification de dates',
                'description' => 'Je voudrais prolonger ma location de 2 jours supplémentaires. Est-ce possible ?',
                'category' => 'booking',
                'priority' => 'medium'
            ],
            [
                'subject' => 'Frais de retard',
                'description' => 'J\'ai reçu une facture pour des frais de retard mais j\'ai rendu la voiture à l\'heure.',
                'category' => 'billing',
                'priority' => 'high'
            ]
        ];

        $statuses = ['open', 'in_progress', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        foreach ($agencies as $agency) {
            // Get clients for this agency
            $clients = Client::whereHas('rentals', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->get();

            if ($clients->count() > 0) {
                // Create 8-12 tickets per agency
                $ticketCount = rand(8, 12);
                
                for ($i = 0; $i < $ticketCount; $i++) {
                    $client = $clients->random();
                    $template = $ticketTemplates[array_rand($ticketTemplates)];
                    
                    // Get a random rental for this client and agency
                    $rental = Rental::where('user_id', $client->user_id)
                        ->where('agency_id', $agency->id)
                        ->inRandomOrder()
                        ->first();

                    // Determine status and set resolved/closed dates
                    $status = $statuses[array_rand($statuses)];
                    $resolvedAt = null;
                    $closedAt = null;
                    
                    if (in_array($status, ['resolved', 'closed'])) {
                        $resolvedAt = now()->subDays(rand(1, 30));
                        if ($status === 'closed') {
                            $closedAt = $resolvedAt->addDays(rand(1, 7));
                        }
                    }

                    SupportTicket::create([
                        'client_id' => $client->id,
                        'agency_id' => $agency->id,
                        'rental_id' => $rental ? $rental->id : null,
                        'ticket_number' => SupportTicket::generateTicketNumber(),
                        'subject' => $template['subject'],
                        'description' => $template['description'],
                        'priority' => $template['priority'],
                        'status' => $status,
                        'category' => $template['category'],
                        'resolved_at' => $resolvedAt,
                        'closed_at' => $closedAt,
                        'created_at' => now()->subDays(rand(1, 60)),
                    ]);
                }
            }
        }
    }
}
