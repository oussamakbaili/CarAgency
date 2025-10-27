<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    /**
     * Create a booking notification for an agency.
     */
    public static function notifyNewBooking($agencyId, $rental, $car, $customer)
    {
        return Notification::create([
            'agency_id' => $agencyId,
            'type' => 'booking',
            'title' => 'Nouvelle réservation',
            'message' => $customer->name . ' a réservé ' . $car->brand . ' ' . $car->model 
                         . ' du ' . $rental->start_date->format('d/m/Y') 
                         . ' au ' . $rental->end_date->format('d/m/Y'),
            'icon' => 'calendar',
            'icon_color' => 'blue',
            'action_url' => route('agence.bookings.pending'),
            'related_id' => $rental->id,
        ]);
    }

    /**
     * Create a booking approval notification for a customer.
     */
    public static function notifyBookingApproved($rental, $car, $agency)
    {
        // For now, this would be for client notifications (future implementation)
        // You could create a similar notifications table for clients
        return null;
    }

    /**
     * Create a booking rejection notification for a customer.
     */
    public static function notifyBookingRejected($rental, $car, $agency)
    {
        // For now, this would be for client notifications (future implementation)
        return null;
    }

    /**
     * Create a payment received notification for an agency.
     */
    public static function notifyPaymentReceived($agencyId, $payment, $rental)
    {
        return Notification::create([
            'agency_id' => $agencyId,
            'type' => 'payment',
            'title' => 'Paiement reçu',
            'message' => 'Paiement de ' . number_format($payment->amount, 0) . ' DH reçu pour la réservation #' . $rental->id,
            'icon' => 'money',
            'icon_color' => 'green',
            'action_url' => route('agence.finance.index'),
            'related_id' => $payment->id,
        ]);
    }

    /**
     * Create a maintenance reminder notification for an agency.
     */
    public static function notifyMaintenanceRequired($agencyId, $car, $message = null)
    {
        return Notification::create([
            'agency_id' => $agencyId,
            'type' => 'maintenance',
            'title' => 'Maintenance nécessaire',
            'message' => $message ?? $car->brand . ' ' . $car->model . ' nécessite une maintenance',
            'icon' => 'alert',
            'icon_color' => 'orange',
            'action_url' => route('agence.fleet.maintenance'),
            'related_id' => $car->id,
        ]);
    }

    /**
     * Create a low stock notification for an agency.
     */
    public static function notifyLowStock($agencyId, $car)
    {
        return Notification::create([
            'agency_id' => $agencyId,
            'type' => 'stock',
            'title' => 'Stock faible',
            'message' => $car->brand . ' ' . $car->model . ' - Stock: ' . $car->available_stock . ' véhicule(s) disponible(s)',
            'icon' => 'alert',
            'icon_color' => 'orange',
            'action_url' => route('agence.cars.index'),
            'related_id' => $car->id,
        ]);
    }

    /**
     * Create a new review notification for an agency.
     */
    public static function notifyNewReview($agencyId, $review, $customer, $car = null)
    {
        $message = $customer->name . ' a laissé un avis';
        if ($car) {
            $message .= ' sur ' . $car->brand . ' ' . $car->model;
        }
        $message .= ' - ' . $review->rating . '/5 étoiles';

        return Notification::create([
            'agency_id' => $agencyId,
            'type' => 'review',
            'title' => 'Nouvel avis client',
            'message' => $message,
            'icon' => 'user',
            'icon_color' => 'purple',
            'action_url' => route('agence.customers.reviews'),
            'related_id' => $review->id,
        ]);
    }

    /**
     * Create a rental start reminder notification.
     */
    public static function notifyRentalStartingSoon($agencyId, $rental, $car, $customer)
    {
        return Notification::create([
            'agency_id' => $agencyId,
            'type' => 'reminder',
            'title' => 'Location commence demain',
            'message' => 'La location de ' . $car->brand . ' ' . $car->model 
                         . ' par ' . $customer->name . ' commence demain',
            'icon' => 'calendar',
            'icon_color' => 'blue',
            'action_url' => route('agence.bookings.show', $rental->id),
            'related_id' => $rental->id,
        ]);
    }

    /**
     * Create a rental end reminder notification.
     */
    public static function notifyRentalEndingSoon($agencyId, $rental, $car, $customer)
    {
        return Notification::create([
            'agency_id' => $agencyId,
            'type' => 'reminder',
            'title' => 'Location se termine demain',
            'message' => 'La location de ' . $car->brand . ' ' . $car->model 
                         . ' par ' . $customer->name . ' se termine demain',
            'icon' => 'calendar',
            'icon_color' => 'orange',
            'action_url' => route('agence.bookings.show', $rental->id),
            'related_id' => $rental->id,
        ]);
    }

    /**
     * Create a booking cancellation notification.
     */
    public static function notifyBookingCancelled($agencyId, $rental, $car, $customer, $cancelledBy = 'client')
    {
        $message = $customer->name . ' a annulé la réservation de ' 
                   . $car->brand . ' ' . $car->model;
        
        if ($cancelledBy === 'admin') {
            $message = 'L\'administrateur a annulé la réservation de ' 
                       . $car->brand . ' ' . $car->model;
        }

        return Notification::create([
            'agency_id' => $agencyId,
            'type' => 'cancellation',
            'title' => 'Réservation annulée',
            'message' => $message,
            'icon' => 'alert',
            'icon_color' => 'red',
            'action_url' => route('agence.bookings.index'),
            'related_id' => $rental->id,
        ]);
    }

    /**
     * Delete old notifications (cleanup).
     */
    public static function cleanupOldNotifications($daysOld = 30)
    {
        return Notification::where('created_at', '<', now()->subDays($daysOld))
            ->where('is_read', true)
            ->delete();
    }
}

