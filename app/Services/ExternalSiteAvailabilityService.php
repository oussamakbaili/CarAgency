<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarExternalSite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ExternalSiteAvailabilityService
{
    /**
     * Vérifier la disponibilité d'un véhicule sur tous les sites externes
     * 
     * @param Car $car
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array ['available' => bool, 'sites' => array]
     */
    public function checkExternalSitesAvailability(Car $car, $startDate = null, $endDate = null): array
    {
        $externalSites = CarExternalSite::where('car_id', $car->id)
            ->where('is_active', true)
            ->get();

        if ($externalSites->isEmpty()) {
            return [
                'available' => true,
                'sites' => [],
                'message' => 'Aucun site externe configuré'
            ];
        }

        $results = [];
        $allAvailable = true;

        foreach ($externalSites as $site) {
            $availability = $this->checkSiteAvailability($site, $car, $startDate, $endDate);
            $results[] = [
                'site_id' => $site->id,
                'site_name' => $site->site_name,
                'site_url' => $site->site_url,
                'available' => $availability['available'],
                'error' => $availability['error'] ?? null,
            ];

            // Si un seul site indique que le véhicule est indisponible, le véhicule est indisponible
            if (!$availability['available']) {
                $allAvailable = false;
            }
        }

        return [
            'available' => $allAvailable,
            'sites' => $results,
            'message' => $allAvailable 
                ? 'Disponible sur tous les sites externes' 
                : 'Indisponible sur au moins un site externe'
        ];
    }

    /**
     * Vérifier la disponibilité sur un site externe spécifique
     * 
     * @param CarExternalSite $site
     * @param Car $car
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    private function checkSiteAvailability(CarExternalSite $site, Car $car, $startDate = null, $endDate = null): array
    {
        // Utiliser le cache pour éviter trop de requêtes
        $cacheKey = "external_site_availability_{$site->id}_{$car->id}_" . ($startDate ?? 'all') . "_" . ($endDate ?? 'all');
        
        return Cache::remember($cacheKey, 60, function () use ($site, $car, $startDate, $endDate) {
            try {
                // Construire l'URL de l'API du site externe
                $apiUrl = rtrim($site->api_url, '/') . '/api/cars/check-availability';
                
                // Préparer les données à envoyer
                $payload = [
                    'car_identifier' => $site->external_car_id ?? $car->registration_number,
                    'registration_number' => $car->registration_number,
                ];

                if ($startDate && $endDate) {
                    $payload['start_date'] = $startDate;
                    $payload['end_date'] = $endDate;
                }

                // Faire la requête HTTP
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $site->api_token,
                        'Accept' => 'application/json',
                    ])
                    ->post($apiUrl, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'available' => $data['available'] ?? false,
                        'error' => null,
                    ];
                } else {
                    Log::warning('External site availability check failed', [
                        'site_id' => $site->id,
                        'car_id' => $car->id,
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);

                    // En cas d'erreur, on considère le véhicule comme indisponible par sécurité
                    return [
                        'available' => false,
                        'error' => 'Erreur lors de la vérification: ' . $response->status(),
                    ];
                }
            } catch (\Exception $e) {
                Log::error('External site availability check exception', [
                    'site_id' => $site->id,
                    'car_id' => $car->id,
                    'error' => $e->getMessage(),
                ]);

                // En cas d'exception, on considère le véhicule comme indisponible par sécurité
                return [
                    'available' => false,
                    'error' => 'Erreur de connexion: ' . $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Vérifier la disponibilité globale d'un véhicule (local + sites externes)
     * 
     * @param Car $car
     * @param string|null $startDate
     * @param string|null $endDate
     * @return bool
     */
    public function checkGlobalAvailability(Car $car, $startDate = null, $endDate = null): bool
    {
        // Vérifier d'abord la disponibilité locale
        $localAvailable = $car->is_available;
        
        // Si le véhicule n'est pas disponible localement, il n'est pas disponible globalement
        if (!$localAvailable) {
            return false;
        }

        // Si des dates sont spécifiées, vérifier aussi les réservations locales
        if ($startDate && $endDate) {
            $rentalService = new RentalService();
            $localAvailable = $rentalService->checkAvailability($car, $startDate, $endDate);
            
            if (!$localAvailable) {
                return false;
            }
        }

        // Vérifier la disponibilité sur les sites externes
        $externalCheck = $this->checkExternalSitesAvailability($car, $startDate, $endDate);
        
        // Le véhicule est disponible seulement s'il est disponible localement ET sur tous les sites externes
        return $localAvailable && $externalCheck['available'];
    }

    /**
     * Invalider le cache pour un véhicule
     * 
     * @param Car $car
     */
    public function clearCache(Car $car): void
    {
        $externalSites = CarExternalSite::where('car_id', $car->id)->get();
        
        foreach ($externalSites as $site) {
            $cacheKey = "external_site_availability_{$site->id}_{$car->id}_*";
            // Note: Laravel ne supporte pas les wildcards, donc on doit utiliser un tag ou supprimer manuellement
            Cache::forget("external_site_availability_{$site->id}_{$car->id}_all_all");
        }
    }
}

