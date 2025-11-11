<?php

namespace App\Http\Controllers;

use App\Services\CMIService;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CMIController extends Controller
{
    public function __construct(private CMIService $cmiService)
    {
        // Désactiver la vérification CSRF pour les callbacks CMI
        $this->middleware(\Illuminate\Routing\Middleware\ValidatePostSize::class);
    }

    /**
     * Callback CMI (POST) - Appelé par CMI après le paiement
     */
    public function callback(Request $request)
    {
        try {
            $params = $request->all();
            
            Log::info('CMI callback received', ['params' => $params]);

            // Récupérer l'order_id depuis les paramètres
            $orderId = $params['oid'] ?? '';
            
            if (!$orderId) {
                Log::error('CMI callback missing order ID');
                return response()->json(['error' => 'Missing order ID'], 400);
            }

            // Trouver le paiement par order_id
            $payment = Payment::where('payment_intent_id', $orderId)->first();
            
            if (!$payment) {
                Log::error('CMI payment not found', ['order_id' => $orderId]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            $rental = $payment->rental;

            // Traiter le callback
            $result = $this->cmiService->processCallback($params, $rental);

            if ($result['success']) {
                Log::info('CMI payment processed successfully', [
                    'payment_id' => $payment->id,
                    'rental_id' => $rental->id ?? null,
                ]);
            } else {
                Log::warning('CMI payment processing failed', [
                    'payment_id' => $payment->id,
                    'error' => $result['error'] ?? 'Unknown error',
                ]);
            }

            // CMI attend une réponse spécifique
            return response('OK', 200)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            Log::error('CMI callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('ERROR', 500)->header('Content-Type', 'text/plain');
        }
    }

    /**
     * URL de succès - Redirection après paiement réussi
     */
    public function success(Request $request)
    {
        try {
            $params = $request->all();
            $orderId = $params['oid'] ?? '';

            if (!$orderId) {
                return redirect()->route('public.home')
                    ->with('error', 'Erreur lors du traitement du paiement.');
            }

            $payment = Payment::where('payment_intent_id', $orderId)->first();

            if (!$payment || !$payment->rental) {
                return redirect()->route('public.home')
                    ->with('error', 'Paiement non trouvé.');
            }

            // Vérifier si le paiement a réussi
            if ($payment->status === Payment::STATUS_COMPLETED) {
                // Stocker l'ID de réservation en session pour la confirmation
                session(['booking_data.rental_id' => $payment->rental_id]);

                return redirect()->route('booking.step5')
                    ->with('success', 'Paiement effectué avec succès !');
            }

            return redirect()->route('booking.step4')
                ->with('error', 'Le paiement n\'a pas été confirmé. Veuillez réessayer.');
        } catch (\Exception $e) {
            Log::error('CMI success redirect error', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('public.home')
                ->with('error', 'Une erreur est survenue lors du traitement.');
        }
    }

    /**
     * URL d'échec - Redirection après paiement échoué
     */
    public function failure(Request $request)
    {
        try {
            $params = $request->all();
            $orderId = $params['oid'] ?? '';

            $errorMessage = $params['ErrMsg'] ?? 'Le paiement a été refusé.';

            if ($orderId) {
                $payment = Payment::where('payment_intent_id', $orderId)->first();
                
                if ($payment && $payment->rental) {
                    // Supprimer la réservation si le paiement a échoué
                    // ou la laisser en pending selon votre logique métier
                    // $payment->rental->delete();
                }
            }

            return redirect()->route('booking.step4')
                ->with('error', $errorMessage);
        } catch (\Exception $e) {
            Log::error('CMI failure redirect error', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('booking.step4')
                ->with('error', 'Une erreur est survenue lors du paiement.');
        }
    }
}
