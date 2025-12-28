<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use App\Helpers\NotificationHelper;
use App\Services\RentalService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function __construct(
        private RentalService $rentalService,
        private PaymentService $paymentService
    ) {
        $this->middleware('auth')->except(['main', 'step1', 'showLoginModal']);
    }

    /**
     * Page principale de réservation (avec pré-remplissage via query string).
     */
    public function main(Request $request, Car $car)
    {
        // Charger la relation agency
        $car->load('agency');

        // Vérifier disponibilité de base
        if (!$car->is_available || !$car->agency || $car->agency->status !== 'approved') {
            return redirect()->back()->with('error', 'Cette voiture n\'est pas disponible pour la location.');
        }

        $bookingData = session('booking_data');
        $hasQueryDates = $request->filled('start_date') && $request->filled('end_date');

        if ($hasQueryDates) {
            try {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
            } catch (\Exception $e) {
                return view('client.booking.main', compact('car', 'bookingData'));
            }

            // Valider les dates de la query avant d'écraser la session
            if ($startDate->isFuture() && $endDate->gt($startDate)) {
                // Vérifier la dispo réelle pour éviter d'afficher des dates invalides
                if ($this->rentalService->checkAvailability($car, $startDate, $endDate)) {
                    $days = $startDate->diffInDays($endDate);
                    $totalPrice = $days * $car->client_price_per_day;

                    $bookingData = [
                        'car_id' => $car->id,
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'days' => $days,
                        'price_per_day' => $car->client_price_per_day,
                        'total_price' => $totalPrice,
                        'platform_fee' => 0,
                        'total_with_fees' => $totalPrice,
                        'step' => 1,
                    ];

                    session(['booking_data' => $bookingData]);
                }
            }
        } elseif (!$bookingData || ($bookingData['car_id'] ?? null) !== $car->id) {
            // Nettoyer la session si elle ne correspond pas à cette voiture
            session()->forget('booking_data');
            $bookingData = null;
        }

        return view('client.booking.main', compact('car', 'bookingData'));
    }

    /**
     * Étape 1: Sélection des dates (accessible sans connexion)
     */
    public function step1(Request $request, Car $car)
    {
        // Charger la relation agency
        $car->load('agency');
        
        // Vérifier que la voiture est disponible
        if (!$car->is_available || !$car->agency || $car->agency->status !== 'approved') {
            return redirect()->back()->with('error', 'Cette voiture n\'est pas disponible pour la location.');
        }

        // Si des dates sont fournies en paramètre, les pré-remplir dans la session
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            
            if ($startDate->isFuture() && $endDate->gt($startDate)) {
                $days = $startDate->diffInDays($endDate);
                $totalPrice = $days * $car->client_price_per_day;
                
                session(['booking_data' => [
                    'car_id' => $car->id,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'days' => $days,
                    'price_per_day' => $car->client_price_per_day,
                    'total_price' => $totalPrice,
                    'platform_fee' => 0,
                    'total_with_fees' => $totalPrice,
                ]]);
            }
        }

        return view('client.booking.step1', compact('car'));
    }

    /**
     * Traiter la sélection des dates et rediriger vers l'étape suivante
     */
    public function processStep1(Request $request, Car $car)
    {
        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Vérifier la disponibilité
        if (!$this->rentalService->checkAvailability($car, $startDate, $endDate)) {
            return redirect()->back()->with('error', 'Cette voiture n\'est pas disponible pour les dates sélectionnées.');
        }

        // Calculer les prix
        $days = $startDate->diffInDays($endDate);
        $totalPrice = $days * $car->client_price_per_day;
        
        // Stocker les données en session
        session([
            'booking_data' => [
                'car_id' => $car->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'days' => $days,
                'price_per_day' => $car->client_price_per_day,
                'total_price' => $totalPrice,
                'platform_fee' => 0, // Commission already included in client_price_per_day
                'total_with_fees' => $totalPrice, // Total already includes commission
                'step' => 1
            ]
        ]);

        // Si l'utilisateur est connecté, aller à l'étape 3
        if (Auth::check()) {
            return redirect()->route('booking.step3');
        }

        // Sinon, aller à l'étape 2 (connexion)
        return redirect()->route('booking.step2');
    }

    /**
     * Étape 2: Connexion/Authentification (si pas connecté)
     */
    public function step2()
    {
        if (Auth::check()) {
            return redirect()->route('booking.step3');
        }

        // Vérifier qu'il y a des données de réservation en session
        if (!session('booking_data')) {
            return redirect()->route('public.home')->with('error', 'Veuillez d\'abord sélectionner une voiture et des dates.');
        }

        return view('client.booking.step2');
    }

    /**
     * Étape 3: Résumé de la réservation
     */
    public function step3()
    {
        $bookingData = session('booking_data');
        
        if (!$bookingData) {
            return redirect()->route('public.home')->with('error', 'Session expirée. Veuillez recommencer votre réservation.');
        }

        $car = Car::with(['agency.user'])->find($bookingData['car_id']);
        
        if (!$car) {
            return redirect()->route('public.home')->with('error', 'Voiture non trouvée.');
        }

        // La commission est déjà incluse dans client_price_per_day, donc pas besoin de recalculer
        $bookingData['platform_fee'] = 0; // Commission already included in client_price_per_day
        $bookingData['total_with_fees'] = $bookingData['total_price']; // Total already includes commission

        session(['booking_data' => $bookingData]);

        return view('client.booking.step3', compact('car', 'bookingData'));
    }

    /**
     * Étape 4: Méthode de paiement
     */
    public function step4()
    {
        $bookingData = session('booking_data');
        
        if (!$bookingData) {
            return redirect()->route('public.home')->with('error', 'Session expirée. Veuillez recommencer votre réservation.');
        }

        $car = Car::with(['agency.user'])->find($bookingData['car_id']);
        
        if (!$car) {
            return redirect()->route('public.home')->with('error', 'Voiture non trouvée.');
        }

        // Préparer les données pour les deux passerelles
        // Stripe sera initialisé côté client si choisi
        // CMI sera initialisé si choisi

        return view('client.booking.step4', compact('car', 'bookingData'));
    }

    /**
     * Initialiser un paiement PayPal
     */
    public function initPayPalPayment(Request $request)
    {
        try {
            $bookingData = session('booking_data');
            
            // Si pas de session, utiliser les données de la requête
            if (!$bookingData) {
                // Vérifier que les données nécessaires sont dans la requête
                $carId = $request->input('car_id');
                $startDateStr = $request->input('start_date');
                $endDateStr = $request->input('end_date');
                
                if (!$carId || !$startDateStr || !$endDateStr) {
                    \Log::warning('PayPal payment init failed: Missing data in request', [
                        'user_id' => Auth::id(),
                        'request_data' => $request->all(),
                    ]);
                    return response()->json([
                        'success' => false, 
                        'error' => 'Données de réservation manquantes. Veuillez sélectionner des dates et réessayer.'
                    ], 400);
                }
                
                // Créer booking_data à partir de la requête
                $car = Car::find($carId);
                if (!$car) {
                    return response()->json([
                        'success' => false, 
                        'error' => 'Véhicule introuvable. Veuillez recommencer.'
                    ], 400);
                }
                
                // Valider les dates
                try {
                    $startDate = Carbon::parse($startDateStr);
                    $endDate = Carbon::parse($endDateStr);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false, 
                        'error' => 'Format de date invalide. Veuillez réessayer.'
                    ], 400);
                }
                
                // Vérifier que les dates sont valides
                if ($startDate->isPast() && !$startDate->isToday()) {
                    return response()->json([
                        'success' => false, 
                        'error' => 'La date de début doit être aujourd\'hui ou dans le futur.'
                    ], 400);
                }
                
                if ($endDate->lte($startDate)) {
                    return response()->json([
                        'success' => false, 
                        'error' => 'La date de fin doit être après la date de début.'
                    ], 400);
                }
                
                // Vérifier la disponibilité
                if (!$this->rentalService->checkAvailability($car, $startDate, $endDate)) {
                    return response()->json([
                        'success' => false, 
                        'error' => 'Cette voiture n\'est pas disponible pour les dates sélectionnées.'
                    ], 400);
                }
                
                $days = $startDate->diffInDays($endDate);
                $totalPrice = $days * $car->client_price_per_day;
                
                // Créer la session booking_data
                $bookingData = [
                    'car_id' => $car->id,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'days' => $days,
                    'price_per_day' => $car->client_price_per_day,
                    'total_price' => $totalPrice,
                    'platform_fee' => 0,
                    'total_with_fees' => $totalPrice,
                ];
                
                session(['booking_data' => $bookingData]);
            }

            if (!isset($bookingData['car_id'])) {
                \Log::warning('PayPal payment init failed: Missing car_id in booking data', [
                    'user_id' => Auth::id(),
                    'booking_data' => $bookingData,
                ]);
                return response()->json([
                    'success' => false, 
                    'error' => 'Données de réservation incomplètes. Veuillez recommencer.'
                ], 400);
            }

            $car = Car::find($bookingData['car_id']);
            if (!$car) {
                \Log::warning('PayPal payment init failed: Car not found', [
                    'user_id' => Auth::id(),
                    'car_id' => $bookingData['car_id'],
                ]);
                return response()->json([
                    'success' => false, 
                    'error' => 'Véhicule introuvable. Veuillez recommencer.'
                ], 400);
            }

            $user = Auth::user();

            // Créer la réservation d'abord (en pending)
            $startDate = Carbon::parse($bookingData['start_date']);
            $endDate = Carbon::parse($bookingData['end_date']);
            
            $rental = Rental::create([
                'user_id' => Auth::id(),
                'car_id' => $bookingData['car_id'],
                'agency_id' => $car->agency_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $bookingData['total_with_fees'],
                'status' => 'pending'
            ]);

            // Créer la commande PayPal
            $paypalData = [
                'amount' => $bookingData['total_with_fees'],
                'currency' => 'EUR',
                'order_id' => 'PAYPAL_' . $rental->id . '_' . time(),
                'description' => 'Réservation de véhicule - ' . $car->brand . ' ' . $car->model,
            ];

            $paypalResult = $this->paymentService->createPayment('paypal', $paypalData);

            if (!$paypalResult['success']) {
                $rental->delete(); // Supprimer la réservation si échec
                \Log::error('PayPal payment creation failed', [
                    'user_id' => Auth::id(),
                    'rental_id' => $rental->id,
                    'error' => $paypalResult['error'] ?? 'Unknown error',
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $paypalResult['error'] ?? 'Impossible d\'initialiser le paiement PayPal. Veuillez vérifier votre configuration PayPal ou réessayer plus tard.'
                ], 400);
            }

            // Créer l'enregistrement de paiement pending
            $payment = \App\Models\Payment::create([
                'rental_id' => $rental->id,
                'user_id' => $rental->user_id,
                'amount' => $bookingData['total_with_fees'],
                'currency' => 'EUR',
                'payment_method' => \App\Models\Payment::PAYMENT_METHOD_PAYPAL,
                'payment_intent_id' => $paypalResult['order_id'],
                'status' => \App\Models\Payment::STATUS_PENDING,
                'metadata' => [
                    'paypal_order_id' => $paypalResult['order_id'],
                    'paypal_token' => $paypalResult['token'] ?? $paypalResult['order_id'],
                ],
            ]);

            // Stocker en session
            session([
                'booking_data.rental_id' => $rental->id,
                'booking_data.payment_intent_id' => $paypalResult['order_id'],
            ]);

            return response()->json([
                'success' => true,
                'approve_url' => $paypalResult['approve_url'],
                'order_id' => $paypalResult['order_id'],
            ]);
        } catch (\Exception $e) {
            \Log::error('PayPal payment init exception', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors de l\'initialisation du paiement PayPal. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Traiter le paiement et créer la réservation
     */
    public function processPayment(Request $request)
    {
        $bookingData = session('booking_data');
        
        if (!$bookingData) {
            return redirect()->route('public.home')->with('error', 'Session expirée. Veuillez recommencer votre réservation.');
        }

        $request->validate([
            'payment_intent_id' => 'required|string',
            'payment_method' => 'required|in:paypal',
            'payment_gateway' => 'required|in:paypal',
            'terms_accepted' => 'required|accepted',
            'privacy_policy_accepted' => 'required|accepted',
        ], [
            'payment_intent_id.required' => 'Erreur de paiement. Veuillez réessayer.',
            'payment_gateway.required' => 'Veuillez sélectionner une méthode de paiement.',
            'terms_accepted.required' => 'Vous devez accepter les conditions générales.',
            'privacy_policy_accepted.required' => 'Vous devez accepter la politique de confidentialité.',
        ]);

        $car = Car::find($bookingData['car_id']);
        
        // Vérifier à nouveau la disponibilité
        $startDate = Carbon::parse($bookingData['start_date']);
        $endDate = Carbon::parse($bookingData['end_date']);
        
        if (!$this->rentalService->checkAvailability($car, $startDate, $endDate)) {
            return redirect()->back()->with('error', 'Cette voiture n\'est plus disponible pour les dates sélectionnées.');
        }

        // S'assurer que l'agency_id est défini
        $agencyId = $car->agency_id;
        if (!$agencyId) {
            return redirect()->back()->with('error', 'La voiture n\'est associée à aucune agence.');
        }

        DB::beginTransaction();

        try {
            // Créer la réservation
            $rental = Rental::create([
                'user_id' => Auth::id(),
                'car_id' => $bookingData['car_id'],
                'agency_id' => $agencyId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $bookingData['total_with_fees'],
                'status' => 'pending'
            ]);

            // Traiter le paiement PayPal
            $gateway = $request->payment_gateway ?? 'paypal';
            $paymentResult = $this->paymentService->processPayment($rental, $request->payment_intent_id, $gateway);

            if (!$paymentResult['success']) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Le paiement a échoué: ' . ($paymentResult['error'] ?? 'Erreur inconnue'));
            }

            // Si PayPal, redirection déjà gérée dans initPayPalPayment
            if (isset($paymentResult['redirect_required']) && $paymentResult['redirect_required']) {
                // PayPal redirige directement vers PayPal
                DB::rollBack();
                return redirect()->back()->with('error', 'Erreur de redirection PayPal');
            }

            // Créer une notification pour l'agence
            try {
                NotificationHelper::notifyNewBooking($agencyId, $rental, $car, Auth::user());
            } catch (\Exception $e) {
                // Log l'erreur mais ne bloque pas la création de la réservation
                Log::error('Failed to create booking notification: ' . $e->getMessage(), [
                    'rental_id' => $rental->id,
                    'agency_id' => $agencyId,
                    'error' => $e->getTraceAsString()
                ]);
            }

            DB::commit();

            // Stocker l'ID de réservation pour l'étape de confirmation
            session(['booking_data.rental_id' => $rental->id]);

            // Nettoyer la session après paiement réussi
            session()->forget('booking_data');

            // Rediriger vers la page d'accueil après paiement
            return redirect()->route('public.home')->with('success', 'Paiement effectué avec succès ! Votre réservation est confirmée.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors du traitement. Veuillez réessayer.');
        }
    }

    /**
     * Étape 5: Confirmation
     */
    public function step5()
    {
        $bookingData = session('booking_data');
        
        if (!$bookingData || !isset($bookingData['rental_id'])) {
            return redirect()->route('public.home')->with('error', 'Aucune réservation trouvée.');
        }

        $rental = Rental::with(['car.agency.user', 'user'])->find($bookingData['rental_id']);
        
        if (!$rental) {
            return redirect()->route('public.home')->with('error', 'Réservation non trouvée.');
        }

        // Nettoyer la session
        session()->forget('booking_data');

        return view('client.booking.step5', compact('rental'));
    }

    /**
     * Annuler le processus de réservation
     */
    public function cancel()
    {
        session()->forget('booking_data');
        return redirect()->route('public.home')->with('info', 'Réservation annulée.');
    }
}
