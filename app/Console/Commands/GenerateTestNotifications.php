<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agency;
use App\Models\Notification;
use Carbon\Carbon;

class GenerateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:generate-test {agency_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate test notifications for an agency';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $agencyId = $this->argument('agency_id');
        
        if (!$agencyId) {
            $agency = Agency::where('status', 'approved')->first();
            if (!$agency) {
                $this->error('No approved agency found!');
                return 1;
            }
            $agencyId = $agency->id;
        }

        $this->info("Generating test notifications for agency ID: {$agencyId}");

        $notifications = [
            [
                'type' => 'booking',
                'title' => 'Nouvelle réservation',
                'message' => 'Ahmed Bennani a réservé Toyota Camry du 15/10/2025 au 20/10/2025',
                'icon' => 'calendar',
                'icon_color' => 'blue',
                'action_url' => '/agence/bookings/pending',
                'created_at' => Carbon::now()->subMinutes(5),
            ],
            [
                'type' => 'payment',
                'title' => 'Paiement reçu',
                'message' => 'Paiement de 2,500 DH reçu pour la réservation #1234',
                'icon' => 'money',
                'icon_color' => 'green',
                'action_url' => '/agence/finance',
                'created_at' => Carbon::now()->subHour(1),
                'is_read' => true,
                'read_at' => Carbon::now()->subMinutes(45),
            ],
            [
                'type' => 'booking',
                'title' => 'Nouvelle réservation',
                'message' => 'Fatima Zahra a réservé BMW X5 du 18/10/2025 au 25/10/2025',
                'icon' => 'calendar',
                'icon_color' => 'blue',
                'action_url' => '/agence/bookings/pending',
                'created_at' => Carbon::now()->subMinutes(15),
            ],
            [
                'type' => 'maintenance',
                'title' => 'Maintenance nécessaire',
                'message' => 'Mercedes Classe C nécessite une révision des 10,000 km',
                'icon' => 'alert',
                'icon_color' => 'orange',
                'action_url' => '/agence/fleet/maintenance',
                'created_at' => Carbon::now()->subHours(3),
            ],
            [
                'type' => 'review',
                'title' => 'Nouvel avis client',
                'message' => 'Mohammed Alami a laissé un avis sur Renault Clio - 5/5 étoiles',
                'icon' => 'user',
                'icon_color' => 'purple',
                'action_url' => '/agence/customers/reviews',
                'created_at' => Carbon::now()->subHours(5),
                'is_read' => true,
                'read_at' => Carbon::now()->subHours(4),
            ],
            [
                'type' => 'reminder',
                'title' => 'Location commence demain',
                'message' => 'La location de Audi A4 par Youssef Idrissi commence demain',
                'icon' => 'calendar',
                'icon_color' => 'blue',
                'action_url' => '/agence/bookings',
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'type' => 'stock',
                'title' => 'Stock faible',
                'message' => 'Volkswagen Golf - Stock: 1 véhicule(s) disponible(s)',
                'icon' => 'alert',
                'icon_color' => 'orange',
                'action_url' => '/agence/cars',
                'created_at' => Carbon::now()->subDay(1),
                'is_read' => true,
                'read_at' => Carbon::now()->subDay(1)->addHours(2),
            ],
            [
                'type' => 'cancellation',
                'title' => 'Réservation annulée',
                'message' => 'Karim El Amrani a annulé la réservation de Peugeot 308',
                'icon' => 'alert',
                'icon_color' => 'red',
                'action_url' => '/agence/bookings',
                'created_at' => Carbon::now()->subDays(2),
                'is_read' => true,
                'read_at' => Carbon::now()->subDays(2)->addHours(1),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create([
                'agency_id' => $agencyId,
                'type' => $notification['type'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'icon' => $notification['icon'],
                'icon_color' => $notification['icon_color'],
                'action_url' => $notification['action_url'],
                'is_read' => $notification['is_read'] ?? false,
                'read_at' => $notification['read_at'] ?? null,
                'created_at' => $notification['created_at'],
                'updated_at' => $notification['created_at'],
            ]);
        }

        $this->info('✅ ' . count($notifications) . ' test notifications generated successfully!');
        
        $unreadCount = count(array_filter($notifications, fn($n) => !isset($n['is_read']) || !$n['is_read']));
        $this->info("📬 Unread notifications: {$unreadCount}");
        
        return 0;
    }
}

