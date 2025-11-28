<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Rental;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private PayPalService $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    /**
     * Créer un paiement PayPal
     */
    public function createPayment(string $gateway, array $data): array
    {
        if ($gateway === 'paypal') {
            return $this->paypalService->createOrder($data);
        }

        return [
            'success' => false,
            'error' => 'Passerelle de paiement non supportée. Seul PayPal est disponible.',
        ];
    }



    /**
     * Traiter un paiement complet (créer réservation + confirmer paiement)
     */
    public function processPayment(Rental $rental, string $paymentIntentId, string $gateway = 'paypal')
    {
        if ($gateway === 'paypal') {
            // Pour PayPal, le paiement est traité via redirection
            // On crée juste un enregistrement pending
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'user_id' => $rental->user_id,
                'amount' => $rental->total_price,
                'currency' => 'EUR',
                'payment_method' => Payment::PAYMENT_METHOD_PAYPAL,
                'payment_intent_id' => $paymentIntentId,
                'status' => Payment::STATUS_PENDING,
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'redirect_required' => true,
            ];
        }

        return [
            'success' => false,
            'error' => 'Passerelle de paiement non supportée. Seul PayPal est disponible.',
        ];
    }

    /**
     * Rembourser un paiement
     */
    public function refundPayment(Payment $payment, float $amount = null)
    {
        if ($payment->payment_method === Payment::PAYMENT_METHOD_PAYPAL) {
            // PayPal - remboursement via API
            return $this->paypalService->refundPayment($payment, $amount);
        }

        return [
            'success' => false,
            'error' => 'Méthode de paiement non supportée pour le remboursement. Seul PayPal est disponible.',
        ];
    }

}

