<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class ReservationNotificationService
{
    /**
     * Create new reservation notification for admin
     */
    public static function notifyAdminNewReservation($rental)
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        return Notification::createReservationNotification(
            $admin->id,
            $rental,
            'new'
        );
    }

    /**
     * Create cancelled reservation notification for admin
     */
    public static function notifyAdminReservationCancelled($rental)
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        return Notification::createReservationNotification(
            $admin->id,
            $rental,
            'cancelled'
        );
    }

    /**
     * Create completed reservation notification for admin
     */
    public static function notifyAdminReservationCompleted($rental)
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        return Notification::createReservationNotification(
            $admin->id,
            $rental,
            'completed'
        );
    }

    /**
     * Create reservation confirmed notification for client
     */
    public static function notifyClientReservationConfirmed($rental)
    {
        return Notification::create([
            'client_id' => $rental->client_id,
            'rental_id' => $rental->id,
            'category' => 'reservation',
            'priority' => 'medium',
            'type' => 'reservation_confirmed',
            'title' => 'Réservation confirmée',
            'message' => "Votre réservation pour {$rental->car->marque} {$rental->car->modele} a été confirmée.",
            'icon' => 'check',
            'icon_color' => 'green',
            'action_url' => route('client.rentals.show', $rental->id),
            'related_id' => $rental->id,
            'data' => [
                'rental_id' => $rental->id,
                'car_info' => "{$rental->car->marque} {$rental->car->modele}",
                'total_amount' => $rental->total_amount,
                'start_date' => $rental->start_date,
                'end_date' => $rental->end_date,
                'status' => $rental->status,
            ],
        ]);
    }

    /**
     * Create reservation cancelled notification for client
     */
    public static function notifyClientReservationCancelled($rental)
    {
        return Notification::create([
            'client_id' => $rental->client_id,
            'rental_id' => $rental->id,
            'category' => 'reservation',
            'priority' => 'medium',
            'type' => 'reservation_cancelled',
            'title' => 'Réservation annulée',
            'message' => "Votre réservation pour {$rental->car->marque} {$rental->car->modele} a été annulée.",
            'icon' => 'alert',
            'icon_color' => 'red',
            'action_url' => route('client.rentals.show', $rental->id),
            'related_id' => $rental->id,
            'data' => [
                'rental_id' => $rental->id,
                'car_info' => "{$rental->car->marque} {$rental->car->modele}",
                'total_amount' => $rental->total_amount,
                'start_date' => $rental->start_date,
                'end_date' => $rental->end_date,
                'status' => $rental->status,
                'cancellation_reason' => $rental->cancellation_reason,
            ],
        ]);
    }

    /**
     * Create new reservation notification for agency
     */
    public static function notifyAgencyNewReservation($rental)
    {
        return Notification::create([
            'agency_id' => $rental->agency_id,
            'rental_id' => $rental->id,
            'category' => 'reservation',
            'priority' => 'high',
            'type' => 'reservation_new',
            'title' => 'Nouvelle réservation',
            'message' => "Nouvelle réservation pour {$rental->car->marque} {$rental->car->modele} par {$rental->client->user->name}.",
            'icon' => 'calendar',
            'icon_color' => 'blue',
            'action_url' => route('agence.rentals.show', $rental->id),
            'related_id' => $rental->id,
            'data' => [
                'rental_id' => $rental->id,
                'client_name' => $rental->client->user->name,
                'client_email' => $rental->client->user->email,
                'car_info' => "{$rental->car->marque} {$rental->car->modele}",
                'total_amount' => $rental->total_amount,
                'start_date' => $rental->start_date,
                'end_date' => $rental->end_date,
                'status' => $rental->status,
            ],
        ]);
    }

    /**
     * Create reservation cancelled notification for agency
     */
    public static function notifyAgencyReservationCancelled($rental)
    {
        return Notification::create([
            'agency_id' => $rental->agency_id,
            'rental_id' => $rental->id,
            'category' => 'reservation',
            'priority' => 'medium',
            'type' => 'reservation_cancelled',
            'title' => 'Réservation annulée',
            'message' => "Réservation annulée pour {$rental->car->marque} {$rental->car->modele} par {$rental->client->user->name}.",
            'icon' => 'alert',
            'icon_color' => 'orange',
            'action_url' => route('agence.rentals.show', $rental->id),
            'related_id' => $rental->id,
            'data' => [
                'rental_id' => $rental->id,
                'client_name' => $rental->client->user->name,
                'client_email' => $rental->client->user->email,
                'car_info' => "{$rental->car->marque} {$rental->car->modele}",
                'total_amount' => $rental->total_amount,
                'start_date' => $rental->start_date,
                'end_date' => $rental->end_date,
                'status' => $rental->status,
                'cancellation_reason' => $rental->cancellation_reason,
            ],
        ]);
    }

    /**
     * Create reservation completed notification for agency
     */
    public static function notifyAgencyReservationCompleted($rental)
    {
        return Notification::create([
            'agency_id' => $rental->agency_id,
            'rental_id' => $rental->id,
            'category' => 'reservation',
            'priority' => 'medium',
            'type' => 'reservation_completed',
            'title' => 'Réservation terminée',
            'message' => "La réservation pour {$rental->car->marque} {$rental->car->modele} par {$rental->client->user->name} est terminée.",
            'icon' => 'check',
            'icon_color' => 'green',
            'action_url' => route('agence.rentals.show', $rental->id),
            'related_id' => $rental->id,
            'data' => [
                'rental_id' => $rental->id,
                'client_name' => $rental->client->user->name,
                'client_email' => $rental->client->user->email,
                'car_info' => "{$rental->car->marque} {$rental->car->modele}",
                'total_amount' => $rental->total_amount,
                'start_date' => $rental->start_date,
                'end_date' => $rental->end_date,
                'status' => $rental->status,
                'completion_date' => now(),
            ],
        ]);
    }

    /**
     * Create reservation completed notification for client
     */
    public static function notifyClientReservationCompleted($rental)
    {
        return Notification::create([
            'client_id' => $rental->client_id,
            'rental_id' => $rental->id,
            'category' => 'reservation',
            'priority' => 'low',
            'type' => 'reservation_completed',
            'title' => 'Réservation terminée',
            'message' => "Votre réservation pour {$rental->car->marque} {$rental->car->modele} est terminée. Merci d'avoir utilisé nos services !",
            'icon' => 'check',
            'icon_color' => 'green',
            'action_url' => route('client.rentals.show', $rental->id),
            'related_id' => $rental->id,
            'data' => [
                'rental_id' => $rental->id,
                'car_info' => "{$rental->car->marque} {$rental->car->modele}",
                'total_amount' => $rental->total_amount,
                'start_date' => $rental->start_date,
                'end_date' => $rental->end_date,
                'status' => $rental->status,
                'completion_date' => now(),
            ],
        ]);
    }
}
