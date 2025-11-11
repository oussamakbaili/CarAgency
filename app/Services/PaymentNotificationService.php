<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Transaction;

class PaymentNotificationService
{
    /**
     * Create payment received notification for admin
     */
    public static function notifyPaymentReceived($transaction)
    {
        $admins = User::where('role', 'admin')->get();
        if ($admins->isEmpty()) return;

        foreach ($admins as $admin) {
            Notification::createPaymentNotification(
                $admin->id,
                $transaction,
                'received'
            );
        }
    }

    /**
     * Create payment failed notification for admin
     */
    public static function notifyPaymentFailed($transaction)
    {
        $admins = User::where('role', 'admin')->get();
        if ($admins->isEmpty()) return;

        foreach ($admins as $admin) {
            Notification::createPaymentNotification(
                $admin->id,
                $transaction,
                'failed'
            );
        }
    }

    /**
     * Create refund notification for admin
     */
    public static function notifyRefundProcessed($transaction)
    {
        $admins = User::where('role', 'admin')->get();
        if ($admins->isEmpty()) return;

        foreach ($admins as $admin) {
            Notification::createPaymentNotification(
                $admin->id,
                $transaction,
                'refunded'
            );
        }
    }

    /**
     * Create payment notification for client
     */
    public static function notifyClientPaymentReceived($transaction)
    {
        if (!$transaction->client_id) return;

        return Notification::create([
            'client_id' => $transaction->client_id,
            'category' => 'payment',
            'priority' => 'medium',
            'type' => 'payment_received',
            'title' => 'Paiement reçu',
            'message' => "Votre paiement de {$transaction->amount} MAD a été reçu avec succès.",
            'icon' => 'money',
            'icon_color' => 'green',
            'action_url' => route('client.transactions.show', $transaction->id),
            'related_id' => $transaction->id,
            'data' => [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
            ],
        ]);
    }

    /**
     * Create payment notification for agency
     */
    public static function notifyAgencyPaymentReceived($transaction)
    {
        if (!$transaction->rental_id) return;
        
        $rental = $transaction->rental;
        if (!$rental || !$rental->agency_id) return;

        return Notification::create([
            'agency_id' => $rental->agency_id,
            'category' => 'payment',
            'priority' => 'medium',
            'type' => 'payment_received',
            'title' => 'Paiement reçu',
            'message' => "Paiement de {$transaction->amount} MAD reçu pour la location #{$rental->id}.",
            'icon' => 'money',
            'icon_color' => 'green',
            'action_url' => route('agence.rentals.show', $rental->id),
            'related_id' => $transaction->id,
            'data' => [
                'transaction_id' => $transaction->id,
                'rental_id' => $rental->id,
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
                'client_name' => $transaction->client->user->name,
            ],
        ]);
    }

    /**
     * Create payment failed notification for client
     */
    public static function notifyClientPaymentFailed($transaction)
    {
        if (!$transaction->client_id) return;

        return Notification::create([
            'client_id' => $transaction->client_id,
            'category' => 'payment',
            'priority' => 'high',
            'type' => 'payment_failed',
            'title' => 'Échec de paiement',
            'message' => "Le paiement de {$transaction->amount} MAD a échoué. Veuillez réessayer.",
            'icon' => 'alert',
            'icon_color' => 'red',
            'action_url' => route('client.transactions.show', $transaction->id),
            'related_id' => $transaction->id,
            'data' => [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
                'error_message' => $transaction->data['error_message'] ?? 'Erreur inconnue',
            ],
        ]);
    }

    /**
     * Create payment failed notification for agency
     */
    public static function notifyAgencyPaymentFailed($transaction)
    {
        if (!$transaction->rental_id) return;
        
        $rental = $transaction->rental;
        if (!$rental || !$rental->agency_id) return;

        return Notification::create([
            'agency_id' => $rental->agency_id,
            'category' => 'payment',
            'priority' => 'high',
            'type' => 'payment_failed',
            'title' => 'Échec de paiement',
            'message' => "Le paiement de {$transaction->amount} MAD pour la location #{$rental->id} a échoué.",
            'icon' => 'alert',
            'icon_color' => 'red',
            'action_url' => route('agence.rentals.show', $rental->id),
            'related_id' => $transaction->id,
            'data' => [
                'transaction_id' => $transaction->id,
                'rental_id' => $rental->id,
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
                'client_name' => $transaction->client->user->name,
                'error_message' => $transaction->data['error_message'] ?? 'Erreur inconnue',
            ],
        ]);
    }

    /**
     * Create refund notification for client
     */
    public static function notifyClientRefundProcessed($transaction)
    {
        if (!$transaction->client_id) return;

        return Notification::create([
            'client_id' => $transaction->client_id,
            'category' => 'payment',
            'priority' => 'medium',
            'type' => 'refund_processed',
            'title' => 'Remboursement effectué',
            'message' => "Votre remboursement de {$transaction->amount} MAD a été traité avec succès.",
            'icon' => 'money',
            'icon_color' => 'orange',
            'action_url' => route('client.transactions.show', $transaction->id),
            'related_id' => $transaction->id,
            'data' => [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
            ],
        ]);
    }

    /**
     * Create refund notification for agency
     */
    public static function notifyAgencyRefundProcessed($transaction)
    {
        if (!$transaction->rental_id) return;
        
        $rental = $transaction->rental;
        if (!$rental || !$rental->agency_id) return;

        return Notification::create([
            'agency_id' => $rental->agency_id,
            'category' => 'payment',
            'priority' => 'medium',
            'type' => 'refund_processed',
            'title' => 'Remboursement effectué',
            'message' => "Remboursement de {$transaction->amount} MAD effectué pour la location #{$rental->id}.",
            'icon' => 'money',
            'icon_color' => 'orange',
            'action_url' => route('agence.rentals.show', $rental->id),
            'related_id' => $transaction->id,
            'data' => [
                'transaction_id' => $transaction->id,
                'rental_id' => $rental->id,
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
                'client_name' => $transaction->client->user->name,
            ],
        ]);
    }

    /**
     * Notify admins when an agency submits a payout request.
     */
    public static function notifyAdminPayoutRequested(Transaction $transaction)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::createPayoutRequestNotification($admin->id, $transaction);
        }
    }

    /**
     * Notify agency once payout has been processed.
     */
    public static function notifyAgencyPayoutProcessed(Transaction $transaction)
    {
        if (!$transaction->agency_id || !$transaction->agency) {
            return;
        }

        Notification::createAgencyPayoutProcessedNotification($transaction->agency, $transaction);
    }

    /**
     * Notify agency if payout request has been rejected.
     */
    public static function notifyAgencyPayoutRejected(Transaction $transaction)
    {
        if (!$transaction->agency_id || !$transaction->agency) {
            return;
        }

        Notification::createAgencyPayoutRejectedNotification($transaction->agency, $transaction);
    }
}