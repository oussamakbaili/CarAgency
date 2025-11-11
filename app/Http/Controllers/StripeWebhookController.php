<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\PaymentService;

class StripeWebhookController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
        // Désactiver la vérification CSRF pour les webhooks
        $this->middleware(\Illuminate\Routing\Middleware\ValidatePostSize::class);
    }

    /**
     * Gérer les webhooks Stripe
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (!$webhookSecret) {
            Log::warning('Stripe webhook secret not configured');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid Stripe webhook signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Traiter l'événement
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            case 'charge.refunded':
                $this->handleRefund($event->data->object);
                break;

            default:
                Log::info('Unhandled Stripe webhook event', ['type' => $event->type]);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Gérer un paiement réussi
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        try {
            $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();

            if ($payment) {
                $payment->update([
                    'status' => Payment::STATUS_COMPLETED,
                    'paid_at' => now(),
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'webhook_processed_at' => now()->toISOString(),
                        'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                    ]),
                ]);

                // Mettre à jour le statut de la réservation si nécessaire
                if ($payment->rental && $payment->rental->status === 'pending') {
                    $payment->rental->update(['status' => 'confirmed']);
                }

                Log::info('Payment confirmed via webhook', [
                    'payment_id' => $payment->id,
                    'rental_id' => $payment->rental_id,
                ]);
            } else {
                Log::warning('Payment not found for webhook', [
                    'payment_intent_id' => $paymentIntent->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling payment succeeded webhook', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * Gérer un paiement échoué
     */
    private function handlePaymentFailed($paymentIntent)
    {
        try {
            $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();

            if ($payment) {
                $payment->update([
                    'status' => Payment::STATUS_FAILED,
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'webhook_processed_at' => now()->toISOString(),
                        'failure_reason' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
                    ]),
                ]);

                Log::info('Payment failed via webhook', [
                    'payment_id' => $payment->id,
                    'rental_id' => $payment->rental_id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling payment failed webhook', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * Gérer un remboursement
     */
    private function handleRefund($charge)
    {
        try {
            $paymentIntentId = $charge->payment_intent;
            $payment = Payment::where('payment_intent_id', $paymentIntentId)->first();

            if ($payment) {
                $payment->update([
                    'status' => Payment::STATUS_REFUNDED,
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'refund_id' => $charge->refunds->data[0]->id ?? null,
                        'refund_amount' => $charge->refunds->data[0]->amount / 100 ?? null,
                        'refunded_at' => now()->toISOString(),
                    ]),
                ]);

                Log::info('Payment refunded via webhook', [
                    'payment_id' => $payment->id,
                    'rental_id' => $payment->rental_id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling refund webhook', [
                'error' => $e->getMessage(),
                'charge_id' => $charge->id,
            ]);
        }
    }
}
