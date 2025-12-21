<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Agency;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Construire la requête de base
            $query = Car::where('status', Car::STATUS_AVAILABLE)
                ->whereHas('agency', function($q) {
                    $q->where('status', 'approved');
                })
                ->with(['agency.user', 'rentals' => function($q) {
                    // Charger seulement les réservations actives pour la vérification de disponibilité
                    $q->whereIn('status', ['pending', 'active'])
                      ->where('end_date', '>=', now()->startOfDay());
                }]);

            // Search filters
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('brand', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%")
                      ->orWhere('color', 'like', "%{$search}%");
                });
            }

            if ($request->filled('brand')) {
                $query->where('brand', $request->get('brand'));
            }

            if ($request->filled('max_price')) {
                $query->where('price_per_day', '<=', $request->get('max_price'));
            }

            if ($request->filled('year_from')) {
                $query->where('year', '>=', $request->get('year_from'));
            }

            if ($request->filled('fuel_type')) {
                $query->where('fuel_type', $request->get('fuel_type'));
            }

            $cars = $query->paginate(12);
            
            // Calculer la disponibilité manuellement pour chaque voiture (sans vérifier les sites externes dans la liste)
            // Cela évite les appels HTTP lents aux sites externes pour chaque voiture
            $cars->getCollection()->transform(function($car) {
                // Désactiver temporairement l'accesseur is_available pour éviter les appels externes
                $car->setAppends([]);
                
                // Vérification simple de disponibilité sans appels externes
                $car->is_available = $this->checkSimpleAvailability($car);
                return $car;
            });
            
            // Get available brands for filter (optimisé)
            $brands = Car::where('status', Car::STATUS_AVAILABLE)
                ->whereHas('agency', function($q) {
                    $q->where('status', 'approved');
                })
                ->distinct()
                ->pluck('brand')
                ->filter()
                ->sort()
                ->values();

            // Get available fuel types for filter (optimisé)
            $fuelTypes = Car::where('status', Car::STATUS_AVAILABLE)
                ->whereHas('agency', function($q) {
                    $q->where('status', 'approved');
                })
                ->whereNotNull('fuel_type')
                ->distinct()
                ->pluck('fuel_type')
                ->filter()
                ->sort()
                ->values();

            return view('client.cars.index', compact('cars', 'brands', 'fuelTypes'));
            
        } catch (\Exception $e) {
            \Log::error('CarController::index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->route('client.dashboard')
                ->with('error', 'Une erreur est survenue lors du chargement des véhicules. Veuillez réessayer.');
        }
    }
    
    /**
     * Vérification simple de disponibilité sans appels aux sites externes
     * Utilisé dans la liste pour éviter les appels HTTP lents
     */
    private function checkSimpleAvailability($car)
    {
        // Vérifier le statut de base
        if ($car->status !== Car::STATUS_AVAILABLE) {
            return false;
        }

        // Vérifier le stock si suivi
        if ($car->track_stock && $car->available_stock <= 0) {
            return false;
        }

        // Vérifier les réservations actives (utilise la relation déjà chargée)
        $hasActiveReservations = $car->rentals->isNotEmpty();

        return !$hasActiveReservations;
    }

    public function show(Request $request, Car $car)
    {
        try {
            // Load necessary relationships
            $car->load(['agency.user']);
            
            // Basic validation - just check if car exists
            if (!$car->exists) {
                abort(404, 'Car not found');
            }

            // If book parameter is present, redirect to booking flow
            if ($request->has('book') && $request->get('book') == '1') {
                // Redirect to booking step1 with the car
                return redirect()->route('booking.step1', $car);
            }

            return view('client.cars.show', compact('car'));
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Car show error: ' . $e->getMessage());
            
            // Return a simple debug view
            return response()->view('client.cars.debug', [
                'error' => $e->getMessage(),
                'car_id' => $car->id ?? 'unknown',
                'car_exists' => $car->exists ?? false
            ]);
        }
    }
}
