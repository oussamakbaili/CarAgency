<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarExternalSite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ExternalSiteDiscoveryService
{
    /**
     * Découvrir automatiquement les sites externes qui contiennent un véhicule
     * 
     * @param Car $car
     * @param array $knownSites Liste des sites connus à vérifier
     * @return array Sites découverts
     */
    public function discoverSites(Car $car, array $knownSites = []): array
    {
        $discoveredSites = [];

        // Si aucune liste de sites connus n'est fournie, utiliser une liste par défaut
        if (empty($knownSites)) {
            $knownSites = $this->getDefaultKnownSites();
        }

        foreach ($knownSites as $siteConfig) {
            $result = $this->checkIfCarExistsOnSite($car, $siteConfig);
            
            if ($result['exists']) {
                $discoveredSites[] = [
                    'site_name' => $siteConfig['name'],
                    'site_url' => $siteConfig['url'],
                    'api_url' => $siteConfig['api_url'],
                    'api_token' => $siteConfig['api_token'] ?? null,
                    'external_car_id' => $result['external_car_id'] ?? null,
                    'verified' => $result['verified'],
                ];
            }
        }

        return $discoveredSites;
    }

    /**
     * Vérifier si un véhicule existe sur un site externe
     * 
     * @param Car $car
     * @param array $siteConfig
     * @return array
     */
    private function checkIfCarExistsOnSite(Car $car, array $siteConfig): array
    {
        try {
            // Essayer d'appeler l'API de vérification de disponibilité
            // Si le véhicule existe, l'API retournera des informations
            $apiUrl = rtrim($siteConfig['api_url'], '/') . '/api/cars/check-availability';
            
            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . ($siteConfig['api_token'] ?? ''),
                    'Accept' => 'application/json',
                ])
                ->post($apiUrl, [
                    'registration_number' => $car->registration_number,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Si l'API retourne des informations sur le véhicule, il existe
                if (isset($data['car']) || isset($data['available'])) {
                    return [
                        'exists' => true,
                        'verified' => true,
                        'external_car_id' => $data['car']['id'] ?? null,
                    ];
                }
            } elseif ($response->status() === 404) {
                // 404 signifie que le véhicule n'existe pas sur ce site
                return [
                    'exists' => false,
                    'verified' => true,
                ];
            }
        } catch (\Exception $e) {
            Log::debug('Error checking car on external site', [
                'car_id' => $car->id,
                'site' => $siteConfig['name'],
                'error' => $e->getMessage(),
            ]);
        }

        return [
            'exists' => false,
            'verified' => false,
        ];
    }

    /**
     * Obtenir la liste des sites connus par défaut
     * Cette liste peut être stockée en base de données ou dans un fichier de configuration
     * 
     * @return array
     */
    private function getDefaultKnownSites(): array
    {
        // Pour l'instant, retourner une liste vide
        // Cette liste peut être remplie depuis la base de données ou un fichier de config
        return config('external_sites.known_sites', []);
    }

    /**
     * Enregistrer automatiquement un site externe pour un véhicule
     * 
     * @param Car $car
     * @param array $siteData
     * @return CarExternalSite
     */
    public function registerExternalSite(Car $car, array $siteData): CarExternalSite
    {
        // Vérifier si le site n'existe pas déjà
        $existingSite = CarExternalSite::where('car_id', $car->id)
            ->where('api_url', $siteData['api_url'])
            ->first();

        if ($existingSite) {
            // Mettre à jour le site existant
            $existingSite->update([
                'site_name' => $siteData['site_name'],
                'site_url' => $siteData['site_url'],
                'api_token' => $siteData['api_token'] ?? $existingSite->api_token,
                'external_car_id' => $siteData['external_car_id'] ?? $existingSite->external_car_id,
                'is_active' => true,
            ]);

            return $existingSite;
        }

        // Créer un nouveau site externe
        return CarExternalSite::create([
            'car_id' => $car->id,
            'site_name' => $siteData['site_name'],
            'site_url' => $siteData['site_url'],
            'api_url' => $siteData['api_url'],
            'api_token' => $siteData['api_token'] ?? null,
            'external_car_id' => $siteData['external_car_id'] ?? null,
            'is_active' => true,
            'notes' => 'Découvert automatiquement',
        ]);
    }
}


