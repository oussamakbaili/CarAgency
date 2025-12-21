<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Services\ExternalSiteDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalSiteDiscoveryController extends Controller
{
    /**
     * API endpoint pour qu'un site externe s'enregistre automatiquement
     * 
     * POST /api/external-sites/register
     * 
     * Body:
     * {
     *   "site_name": "Site A",
     *   "site_url": "https://site-a.com",
     *   "api_url": "https://site-a.com/api",
     *   "api_token": "abc123",
     *   "registration_number": "ABC-123",
     *   "external_car_id": "CAR-456"
     * }
     */
    public function register(Request $request, ExternalSiteDiscoveryService $discoveryService)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url|max:255',
            'api_url' => 'required|url|max:255',
            'api_token' => 'nullable|string|max:500',
            'registration_number' => 'required|string',
            'external_car_id' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Données invalides',
                'details' => $validator->errors(),
            ], 400);
        }

        // Trouver le véhicule
        $car = Car::where('registration_number', $request->registration_number)->first();

        if (!$car) {
            return response()->json([
                'success' => false,
                'error' => 'Véhicule non trouvé',
            ], 404);
        }

        // Enregistrer le site externe
        try {
            $externalSite = $discoveryService->registerExternalSite($car, [
                'site_name' => $request->site_name,
                'site_url' => $request->site_url,
                'api_url' => $request->api_url,
                'api_token' => $request->api_token,
                'external_car_id' => $request->external_car_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Site externe enregistré avec succès',
                'external_site_id' => $externalSite->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API endpoint pour lister les sites externes qui ont un véhicule
     * 
     * GET /api/cars/{registration_number}/external-sites
     */
    public function listExternalSites($registrationNumber)
    {
        $car = Car::where('registration_number', $registrationNumber)->first();

        if (!$car) {
            return response()->json([
                'success' => false,
                'error' => 'Véhicule non trouvé',
            ], 404);
        }

        $externalSites = $car->externalSites()
            ->where('is_active', true)
            ->select('id', 'site_name', 'site_url', 'api_url', 'external_car_id', 'created_at')
            ->get();

        return response()->json([
            'success' => true,
            'car' => [
                'id' => $car->id,
                'brand' => $car->brand,
                'model' => $car->model,
                'registration_number' => $car->registration_number,
            ],
            'external_sites' => $externalSites,
            'count' => $externalSites->count(),
        ]);
    }
}
