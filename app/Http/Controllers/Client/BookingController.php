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

        $bookingData = session('booking_data');

        // If user has a pending PayPal (rental_id in session) and lands on main with same car, send them to step4 first
        // (before availability check: their own pending rental may make the car appear unavailable)
        if (Auth::check() && $bookingData && ($bookingData['car_id'] ?? null) == $car->id && !empty($bookingData['rental_id'])) {
            return redirect()->route('booking.step4')
                ->with('paypal_not_completed', true)
                ->with('info', 'Le paiement n\'a pas été effectué. Vous pouvez réessayer ou choisir une autre méthode de paiement.');
        }

        // Vérifier uniquement le statut statique (pas les réservations) pour ne pas bloquer les dates libres
        $carBlocked = $car->status !== Car::STATUS_AVAILABLE || !$car->agency || $car->agency->status !== 'approved';

        if ($carBlocked) {
            // Fallback: if user just came back from PayPal without paying, session may be lost but they have a pending rental for this car
            if (Auth::check()) {
                $pendingRental = Rental::where('car_id', $car->id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'pending')
                    ->where('created_at', '>=', now()->subMinutes(15))
                    ->with('payments')
                    ->first();
                if ($pendingRental) {
                    $startDate = $pendingRental->start_date;
                    $endDate = $pendingRental->end_date;
                    $days = $startDate->diffInDays($endDate);
                    $payment = $pendingRental->payments()->where('status', \App\Models\Payment::STATUS_PENDING)->first();
                    $restoredBookingData = [
                        'car_id' => $car->id,
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'days' => $days,
                        'price_per_day' => $days > 0 ? (float) $pendingRental->total_price / $days : (float) $pendingRental->total_price,
                        'total_price' => (float) $pendingRental->total_price,
                        'platform_fee' => 0,
                        'total_with_fees' => (float) $pendingRental->total_price,
                        'rental_id' => $pendingRental->id,
                        'payment_intent_id' => $payment ? $payment->payment_intent_id : null,
                    ];
                    session(['booking_data' => $restoredBookingData]);
                    return redirect()->route('booking.step4')
                        ->with('paypal_not_completed', true)
                        ->with('info', 'Le paiement n\'a pas été effectué. Vous pouvez réessayer ou choisir une autre méthode de paiement.');
                }
            }
            return redirect()->route('public.home')->with('error', 'Cette voiture n\'est pas disponible pour la location.');
        }

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

        // Si l'utilisateur est connecté, vérifier qu'il est un client
        if (Auth::check()) {
            // Si l'utilisateur n'est pas un client, le déconnecter et rediriger vers l'étape 2
            if (!Auth::user()->isClient()) {
                Auth::logout();
                return redirect()->route('booking.step2')
                    ->with('error', 'Seuls les clients peuvent réserver des véhicules. Veuillez vous connecter avec un compte client.');
            }
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
        // Si l'utilisateur est connecté, vérifier qu'il est un client
        if (Auth::check()) {
            // Si l'utilisateur n'est pas un client, le déconnecter et afficher un message
            if (!Auth::user()->isClient()) {
                Auth::logout();
                return redirect()->route('booking.step2')
                    ->with('error', 'Seuls les clients peuvent réserver des véhicules. Veuillez vous connecter avec un compte client.');
            }
            // Si c'est un client, rediriger vers l'étape 3
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
        // Vérifier que l'utilisateur est un client
        if (!Auth::check() || !Auth::user()->isClient()) {
            Auth::logout();
            return redirect()->route('booking.step2')
                ->with('error', 'Seuls les clients peuvent réserver des véhicules. Veuillez vous connecter avec un compte client.');
        }

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
        // Vérifier que l'utilisateur est un client
        if (!Auth::check() || !Auth::user()->isClient()) {
            Auth::logout();
            return redirect()->route('booking.step2')
                ->with('error', 'Seuls les clients peuvent réserver des véhicules. Veuillez vous connecter avec un compte client.');
        }

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
        // PayPal: amount in EUR (converted from MAD) for display
        $madToEurRate = (float) config('services.paypal.mad_to_eur_rate', 11);
        $totalPayPalEur = round(((float) ($bookingData['total_with_fees'] ?? 0)) / $madToEurRate, 2);

        $response = view('client.booking.step4', compact('car', 'bookingData', 'totalPayPalEur'));
        
        // Inject PayPal button fix script directly into the response
        $paypalButtonFix = '<script>
document.addEventListener("DOMContentLoaded", function() {
    const findPayPalButton = () => {
        return document.querySelector("button[data-paypal-button]") ||
               document.querySelector("button[data-payment-method=\"paypal\"]") ||
               Array.from(document.querySelectorAll("button")).find(btn => 
                   btn.textContent && (
                       btn.textContent.includes("PayPal") || 
                       btn.textContent.includes("Paypal") ||
                       btn.textContent.includes("paypal")
                   )
               );
    };
    
    const paypalButton = findPayPalButton();
    
    if (paypalButton) {
        const newButton = paypalButton.cloneNode(true);
        paypalButton.parentNode.replaceChild(newButton, paypalButton);
        
        newButton.addEventListener("click", async function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("Payment button clicked");
            
            try {
                this.disabled = true;
                const originalText = this.textContent;
                this.textContent = "Chargement...";
                
                const csrfToken = document.querySelector("meta[name=\"csrf-token\"]")?.content;
                
                if (!csrfToken) {
                    console.error("CSRF token not found");
                    alert("Erreur: Token de sécurité manquant. Veuillez rafraîchir la page.");
                    this.disabled = false;
                    this.textContent = originalText;
                    return;
                }
                
                console.log("Calling /booking/paypal/init");
                const response = await fetch("/booking/paypal/init", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({})
                });
                
                console.log("Response status:", response.status);
                const data = await response.json();
                console.log("PayPal response:", data);
                
                if (data.success && data.approve_url) {
                    console.log("Redirecting to PayPal:", data.approve_url);
                    window.location.href = data.approve_url;
                } else {
                    console.error("PayPal init failed:", data.error);
                    alert(data.error || "Erreur lors de l\'initialisation du paiement PayPal");
                    this.disabled = false;
                    this.textContent = originalText;
                }
            } catch (error) {
                console.error("PayPal init error:", error);
                alert("Une erreur est survenue. Veuillez réessayer.");
                this.disabled = false;
                this.textContent = originalText || "Payer avec PayPal";
            }
        });
        
        console.log("PayPal button click handler attached");
    } else {
        console.warn("PayPal button not found on page");
    }
});
</script>';

        // Append script before closing body tag, or at end if no body tag found
        $content = $response->render();
        if (strpos($content, '</body>') !== false) {
            $content = str_replace('</body>', $paypalButtonFix . '</body>', $content);
        } else {
            $content .= $paypalButtonFix;
        }
        
        return response($content);
    }

    /**
     * Initialiser un paiement PayPal
     */
    public function initPayPalPayment(Request $request)
    {
        // Vérifier que l'utilisateur est un client
        if (!Auth::check() || !Auth::user()->isClient()) {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Seuls les clients peuvent réserver des véhicules.'
            ], 403);
        }

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

            // Convert MAD to EUR for PayPal (prices in app are MAD; PayPal is charged in EUR)
            $amountMad = (float) $bookingData['total_with_fees'];
            $madToEurRate = (float) config('services.paypal.mad_to_eur_rate', 11);
            $amountEur = round($amountMad / $madToEurRate, 2);

            // Créer la commande PayPal (amount in EUR)
            $paypalData = [
                'amount' => $amountEur,
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
                    'paypal_result' => $paypalResult,
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $paypalResult['error'] ?? 'Impossible d\'initialiser le paiement PayPal. Veuillez vérifier votre configuration PayPal ou réessayer plus tard.',
                    'debug' => config('app.debug') ? $paypalResult : null,
                ], 400);
            }

            // Vérifier que l'approve_url existe
            if (empty($paypalResult['approve_url'])) {
                $rental->delete();
                \Log::error('PayPal payment creation succeeded but approve_url is missing', [
                    'user_id' => Auth::id(),
                    'rental_id' => $rental->id,
                    'paypal_result' => $paypalResult,
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Erreur lors de la création du lien de paiement PayPal. Veuillez réessayer.',
                ], 500);
            }

            // Créer l'enregistrement de paiement pending (amount charged in EUR)
            $payment = \App\Models\Payment::create([
                'rental_id' => $rental->id,
                'user_id' => $rental->user_id,
                'amount' => $amountEur,
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

            \Log::info('PayPal payment initialized successfully', [
                'user_id' => Auth::id(),
                'rental_id' => $rental->id,
                'order_id' => $paypalResult['order_id'],
                'approve_url' => $paypalResult['approve_url'],
            ]);

            return response()->json([
                'success' => true,
                'approve_url' => $paypalResult['approve_url'],
                'order_id' => $paypalResult['order_id'],
                'redirect' => true, // Indicate that frontend should redirect
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
     * Initialiser un paiement Stripe (carte bancaire).
     * Retourne un client_secret pour Stripe Elements. Si Stripe n'est pas configuré, retourne une erreur.
     */
    public function initStripePayment(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isClient()) {
            return response()->json(['success' => false, 'error' => 'Non autorisé.'], 403);
        }
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return response()->json(['success' => false, 'error' => 'Session expirée. Veuillez recommencer votre réservation.'], 400);
        }
        // Stripe n'est pas encore intégré : seul PayPal est disponible
        return response()->json([
            'success' => false,
            'error' => 'Le paiement par carte n\'est pas disponible pour le moment. Veuillez utiliser PayPal.',
        ], 400);
    }

    /**
     * Traiter le paiement et créer la réservation
     */
    public function processPayment(Request $request)
    {
        // Vérifier que l'utilisateur est un client
        if (!Auth::check() || !Auth::user()->isClient()) {
            Auth::logout();
            return redirect()->route('booking.step2')
                ->with('error', 'Seuls les clients peuvent réserver des véhicules. Veuillez vous connecter avec un compte client.');
        }

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
        // Vérifier que l'utilisateur est un client
        if (!Auth::check() || !Auth::user()->isClient()) {
            Auth::logout();
            return redirect()->route('booking.step2')
                ->with('error', 'Seuls les clients peuvent réserver des véhicules. Veuillez vous connecter avec un compte client.');
        }

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
