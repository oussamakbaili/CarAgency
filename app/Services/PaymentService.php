<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Rental;
use App\Services\CMIService;
use App\Services\PayPalService;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    private CMIService $cmiService;
    private PayPalService $paypalService;

    public function __construct(CMIService $cmiService, PayPalService $paypalService)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->cmiService = $cmiService;
        $this->paypalService = $paypalService;
    }

    /**
     * Créer un paiement (Stripe, CMI ou PayPal selon la méthode choisie)
     */
    public function createPayment(string $gateway, array $data): array
    {
        if ($gateway === 'stripe') {
            return $this->createStripePayment($data);
        } elseif ($gateway === 'cmi') {
            return $this->cmiService->createPayment($data);
        } elseif ($gateway === 'paypal') {
            return $this->paypalService->createOrder($data);
        }

        return [
            'success' => false,
            'error' => 'Passerelle de paiement non supportée',
        ];
    }

    /**
     * Créer un PaymentIntent Stripe (méthode privée)
     */
    private function createStripePayment(array $data): array
    {
        return $this->createPaymentIntent(
            $data['amount'],
            $data['currency'] ?? 'eur',
            $data['metadata'] ?? []
        );
    }

    /**
     * Créer un PaymentIntent Stripe
     */
    public function createPaymentIntent(float $amount, string $currency = 'eur', array $metadata = [])
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToCents($amount),
                'currency' => $currency,
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe PaymentIntent creation failed', [
                'error' => $e->getMessage(),
                'amount' => $amount,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirmer un paiement après création de la réservation
     */
    public function confirmPayment(string $paymentIntentId, Rental $rental)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                // Créer l'enregistrement de paiement
                $payment = Payment::create([
                    'rental_id' => $rental->id,
                    'user_id' => $rental->user_id,
                    'amount' => $this->convertFromCents($paymentIntent->amount),
                    'currency' => $paymentIntent->currency,
                    'payment_method' => 'stripe',
                    'payment_intent_id' => $paymentIntent->id,
                    'status' => 'completed',
                    'metadata' => [
                        'stripe_payment_intent' => $paymentIntent->id,
                        'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                        'payment_method_type' => $paymentIntent->charges->data[0]->payment_method_details->type ?? null,
                    ],
                ]);

                // Mettre à jour le statut de la réservation
                $rental->update(['status' => 'confirmed']);

                return [
                    'success' => true,
                    'payment' => $payment,
                ];
            }

            return [
                'success' => false,
                'error' => 'Le paiement n\'a pas été confirmé',
                'status' => $paymentIntent->status,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment confirmation failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId,
                'rental_id' => $rental->id,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Traiter un paiement complet (créer réservation + confirmer paiement)
     */
    public function processPayment(Rental $rental, string $paymentIntentId, string $gateway = 'stripe')
    {
        if ($gateway === 'cmi') {
            // Pour CMI, le paiement est traité via callback
            // On crée juste un enregistrement pending
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'user_id' => $rental->user_id,
                'amount' => $rental->total_price,
                'currency' => 'MAD',
                'payment_method' => Payment::PAYMENT_METHOD_BANK_TRANSFER,
                'payment_intent_id' => $paymentIntentId,
                'status' => Payment::STATUS_PENDING,
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'redirect_required' => true,
            ];
        }

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

        // Traitement Stripe
        DB::beginTransaction();

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status !== 'succeeded') {
                DB::rollBack();
                return [
                    'success' => false,
                    'error' => 'Le paiement n\'a pas été confirmé',
                ];
            }

            // Créer l'enregistrement de paiement
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'user_id' => $rental->user_id,
                'amount' => $this->convertFromCents($paymentIntent->amount),
                'currency' => $paymentIntent->currency,
                'payment_method' => Payment::PAYMENT_METHOD_STRIPE,
                'payment_intent_id' => $paymentIntent->id,
                'status' => Payment::STATUS_COMPLETED,
                'paid_at' => now(),
                'metadata' => [
                    'stripe_payment_intent' => $paymentIntent->id,
                    'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                    'payment_method_type' => $paymentIntent->charges->data[0]->payment_method_details->type ?? null,
                ],
            ]);

            // Mettre à jour le statut de la réservation
            $rental->update(['status' => 'confirmed']);

            DB::commit();

            return [
                'success' => true,
                'payment' => $payment,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'rental_id' => $rental->id,
                'payment_intent_id' => $paymentIntentId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Rembourser un paiement
     */
    public function refundPayment(Payment $payment, float $amount = null)
    {
        if ($payment->payment_method === Payment::PAYMENT_METHOD_STRIPE) {
            return $this->refundStripePayment($payment, $amount);
        } elseif ($payment->payment_method === Payment::PAYMENT_METHOD_BANK_TRANSFER) {
            // CMI - remboursement manuel
            return $this->cmiService->refundPayment($payment, $amount);
        } elseif ($payment->payment_method === Payment::PAYMENT_METHOD_PAYPAL) {
            // PayPal - remboursement via API
            return $this->paypalService->refundPayment($payment, $amount);
        }

        return [
            'success' => false,
            'error' => 'Méthode de paiement non supportée pour le remboursement',
        ];
    }

    /**
     * Rembourser un paiement Stripe
     */
    private function refundStripePayment(Payment $payment, float $amount = null)
    {
        try {
            if (!$payment->payment_intent_id) {
                return [
                    'success' => false,
                    'error' => 'Aucun PaymentIntent associé à ce paiement',
                ];
            }

            $refundAmount = $amount ? $this->convertToCents($amount) : null;

            $refund = \Stripe\Refund::create([
                'payment_intent' => $payment->payment_intent_id,
                'amount' => $refundAmount,
            ]);

            // Mettre à jour le paiement
            $payment->update([
                'status' => 'refunded',
                'metadata' => array_merge($payment->metadata ?? [], [
                    'refund_id' => $refund->id,
                    'refund_amount' => $this->convertFromCents($refund->amount),
                    'refunded_at' => now()->toISOString(),
                ]),
            ]);

            return [
                'success' => true,
                'refund' => $refund,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe refund failed', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Récupérer les détails d'un PaymentIntent
     */
    public function getPaymentIntent(string $paymentIntentId)
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            Log::error('Failed to retrieve PaymentIntent', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId,
            ]);

            return null;
        }
    }

    /**
     * Convertir un montant en centimes (pour Stripe)
     */
    private function convertToCents(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Convertir des centimes en montant
     */
    private function convertFromCents(int $cents): float
    {
        return $cents / 100;
    }
}

