<?php

namespace App\Http\Controllers;

use App\Services\PayPalService;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    public function __construct(private PayPalService $paypalService)
    {
        //
    }

    /**
     * URL de succès - Redirection après paiement réussi
     */
    public function success(Request $request)
    {
        try {
            Log::info('PayPal success callback received', [
                'query_params' => $request->query(),
                'all_params' => $request->all(),
            ]);

            // PayPal renvoie le token dans l'URL
            $token = $request->query('token');
            $PayerID = $request->query('PayerID');

            if (!$token) {
                Log::error('PayPal success missing token', [
                    'request' => $request->all(),
                    'query' => $request->query(),
                ]);
                return redirect()->route('public.home')
                    ->with('error', 'Erreur lors du traitement du paiement PayPal. Token manquant.');
            }

            // Essayer de trouver le paiement par token (qui peut être l'order ID)
            $payment = Payment::where('payment_intent_id', $token)->first();

            // Si pas trouvé, essayer dans metadata
            if (!$payment) {
                $payment = Payment::whereJsonContains('metadata->paypal_order_id', $token)->first();
            }
            
            if (!$payment) {
                $payment = Payment::whereJsonContains('metadata->paypal_token', $token)->first();
            }

            // Si toujours pas trouvé, utiliser le token comme order ID
            $orderId = $payment ? $payment->payment_intent_id : $token;

            Log::info('PayPal payment lookup', [
                'token' => $token,
                'order_id' => $orderId,
                'payment_found' => $payment !== null,
                'payment_id' => $payment->id ?? null,
            ]);

            // Corriger: vérifier que $payment existe avant d'accéder à ->rental
            $rental = null;
            if ($payment) {
                $rental = $payment->rental;
            }

            // Si pas de payment trouvé, essayer de trouver la réservation par session
            if (!$rental) {
                $rentalId = session('booking_data.rental_id');
                if ($rentalId) {
                    $rental = Rental::find($rentalId);
                    Log::info('PayPal rental found from session', [
                        'rental_id' => $rentalId,
                        'rental_found' => $rental !== null,
                    ]);
                }
            }

            // Si toujours pas de rental trouvé, essayer de récupérer les détails de l'ordre PayPal
            // et trouver la réservation via le reference_id ou d'autres métadonnées
            if (!$rental) {
                $orderDetails = $this->paypalService->getOrder($orderId);
                
                if ($orderDetails && isset($orderDetails['purchase_units'][0]['reference_id'])) {
                    $referenceId = $orderDetails['purchase_units'][0]['reference_id'];
                    
                    // Le reference_id peut contenir l'ID de la réservation
                    // Format possible: PAYPAL_{rental_id}_{timestamp}
                    if (preg_match('/PAYPAL_(\d+)_/', $referenceId, $matches)) {
                        $rentalId = $matches[1];
                        $rental = Rental::find($rentalId);
                        
                        if ($rental) {
                            Log::info('PayPal rental found via order reference_id', [
                                'order_id' => $orderId,
                                'reference_id' => $referenceId,
                                'rental_id' => $rentalId,
                            ]);
                        }
                    }
                }
            }

            if (!$rental) {
                Log::error('PayPal success: No rental found', [
                    'token' => $token,
                    'order_id' => $orderId,
                    'payment_id' => $payment->id ?? null,
                ]);
                return redirect()->route('public.home')
                    ->with('error', 'Réservation introuvable. Veuillez contacter le support avec le numéro de commande: ' . $orderId);
            }

            // Capturer et traiter le paiement
            Log::info('PayPal processing payment', [
                'order_id' => $orderId,
                'rental_id' => $rental->id,
            ]);

            $result = $this->paypalService->processPayment($orderId, $rental);

            if ($result['success']) {
                // Stocker l'ID de réservation et payment_intent_id en session pour la confirmation
                session([
                    'booking_data.rental_id' => $rental->id,
                    'booking_data.payment_intent_id' => $orderId,
                ]);

                Log::info('PayPal payment successful', [
                    'order_id' => $orderId,
                    'rental_id' => $rental->id,
                ]);

                // Rediriger vers la page de confirmation avec le paramètre de succès
                $car = $rental->car;
                if ($car) {
                    $startDate = session('booking_data.start_date') ?? $rental->start_date->format('Y-m-d');
                    $endDate = session('booking_data.end_date') ?? $rental->end_date->format('Y-m-d');
                    
                    $redirectUrl = route('client.rentals.confirm', ['car' => $car->id]);
                    $redirectUrl .= '?start_date=' . $startDate . '&end_date=' . $endDate . '&payment_success=paypal';
                    
                    return redirect($redirectUrl)
                        ->with('success', 'Paiement PayPal effectué avec succès !');
                }
                
                // Si pas de car, rediriger vers la page d'accueil
                return redirect()->route('public.home')
                    ->with('success', 'Paiement PayPal effectué avec succès !');
            }

            Log::error('PayPal payment processing failed', [
                'order_id' => $orderId,
                'rental_id' => $rental->id,
                'error' => $result['error'] ?? 'Unknown error',
            ]);

            // Si le paiement échoue, annuler la réservation pour libérer la disponibilité
            if ($rental && $rental->status === 'pending') {
                $rental->update(['status' => 'rejected']);
                Log::info('Rental cancelled due to PayPal payment failure', [
                    'rental_id' => $rental->id,
                    'order_id' => $orderId,
                ]);
            }

            // Rediriger vers la page de confirmation avec les paramètres nécessaires
            $car = $rental->car;
            if ($car) {
                $startDate = session('booking_data.start_date');
                $endDate = session('booking_data.end_date');
                
                $redirectUrl = route('client.rentals.confirm', ['car' => $car->id]);
                if ($startDate && $endDate) {
                    $redirectUrl .= '?start_date=' . $startDate . '&end_date=' . $endDate;
                }
                
                return redirect($redirectUrl)
                    ->with('error', 'Le paiement PayPal n\'a pas été confirmé. Veuillez réessayer.');
            }
            
            // Si pas de car, rediriger vers la page d'accueil
            return redirect()->route('public.home')
                ->with('error', 'Le paiement PayPal n\'a pas été confirmé. Veuillez réessayer.');
        } catch (\Exception $e) {
            Log::error('PayPal success redirect error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->route('public.home')
                ->with('error', 'Une erreur est survenue lors du traitement: ' . $e->getMessage());
        }
    }

    /**
     * URL d'annulation - Redirection après annulation du paiement
     */
    public function cancel(Request $request)
    {
        try {
            $token = $request->query('token');
            $orderId = $token;

            if ($orderId) {
                $payment = Payment::where('payment_intent_id', $orderId)->first();
                
                // Si pas trouvé, essayer dans metadata
                if (!$payment) {
                    $payment = Payment::whereJsonContains('metadata->paypal_order_id', $orderId)
                        ->orWhereJsonContains('metadata->paypal_token', $orderId)
                        ->first();
                }
                
                if ($payment && $payment->rental) {
                    $payment->update([
                        'status' => Payment::STATUS_CANCELLED,
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'cancelled_at' => now()->toISOString(),
                            'cancelled_by' => 'user',
                        ]),
                    ]);

                    // Annuler la réservation si le paiement est annulé
                    // Cela libère la disponibilité de la voiture
                    if ($payment->rental->status === 'pending') {
                        $payment->rental->update(['status' => 'rejected']);
                        Log::info('Rental cancelled due to PayPal payment cancellation', [
                            'rental_id' => $payment->rental->id,
                            'payment_id' => $payment->id,
                        ]);
                    }
                }
            }

            return redirect()->route('booking.step4')
                ->with('paypal_not_completed', true)
                ->with('info', 'Le paiement n\'a pas été effectué. Vous pouvez réessayer ou choisir une autre méthode de paiement.');
        } catch (\Exception $e) {
            Log::error('PayPal cancel redirect error', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('booking.step4')
                ->with('paypal_not_completed', true)
                ->with('error', 'Une erreur est survenue lors de l\'annulation.');
        }
    }
}
