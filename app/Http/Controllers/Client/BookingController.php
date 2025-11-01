<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use App\Helpers\NotificationHelper;
use App\Services\RentalService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(private RentalService $rentalService)
    {
        $this->middleware('auth')->except(['step1', 'showLoginModal']);
    }

    /**
     * Étape 1: Sélection des dates (accessible sans connexion)
     */
    public function step1(Car $car)
    {
        // Vérifier que la voiture est disponible
        if (!$car->is_available || $car->agency->status !== 'approved') {
            return redirect()->back()->with('error', 'Cette voiture n\'est pas disponible pour la location.');
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

        // Stocker les données en session
        session([
            'booking_data' => [
                'car_id' => $car->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'days' => $startDate->diffInDays($endDate),
                'price_per_day' => $car->price_per_day,
                'total_price' => $startDate->diffInDays($endDate) * $car->price_per_day,
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

        $car = Car::with(['agency.user', 'category'])->find($bookingData['car_id']);
        
        if (!$car) {
            return redirect()->route('public.home')->with('error', 'Voiture non trouvée.');
        }

        // Calculer les frais supplémentaires avec la nouvelle logique
        $commissionService = new \App\Services\CommissionService();
        $breakdown = $commissionService->calculatePricingBreakdown($bookingData['total_price']);
        
        $bookingData['platform_fee'] = $breakdown['platform_fee'];
        $bookingData['admin_commission'] = $breakdown['admin_commission'];
        $bookingData['total_with_fees'] = $bookingData['total_price'] + $bookingData['platform_fee'];

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

        return view('client.booking.step4', compact('car', 'bookingData'));
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
            'payment_method' => 'required|in:card,paypal,bank_transfer',
            'terms_accepted' => 'required|accepted',
            'privacy_policy_accepted' => 'required|accepted',
        ], [
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

        // Créer une notification pour l'agence
        try {
            NotificationHelper::notifyNewBooking($agencyId, $rental, $car, Auth::user());
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la création de la réservation
            \Log::error('Failed to create booking notification: ' . $e->getMessage(), [
                'rental_id' => $rental->id,
                'agency_id' => $agencyId,
                'error' => $e->getTraceAsString()
            ]);
        }

        // Stocker l'ID de réservation pour l'étape de confirmation
        session(['booking_data.rental_id' => $rental->id]);

        // Dans un vrai projet, ici vous feriez le traitement du paiement
        // Pour l'instant, on simule un paiement réussi
        
        // Rediriger vers la confirmation
        return redirect()->route('booking.step5');
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
