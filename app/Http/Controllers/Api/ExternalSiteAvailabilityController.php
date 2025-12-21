<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Services\RentalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalSiteAvailabilityController extends Controller
{
    /**
     * API endpoint pour que les sites externes puissent vérifier la disponibilité d'un véhicule
     * 
     * POST /api/cars/check-availability
     * 
     * Headers:
     * - Authorization: Bearer {token}
     * 
     * Body:
     * {
     *   "car_identifier": "ABC-123" ou "registration_number": "ABC-123",
     *   "start_date": "2024-01-15" (optionnel),
     *   "end_date": "2024-01-20" (optionnel)
     * }
     */
    public function checkAvailability(Request $request, RentalService $rentalService)
    {
        // Valider les données
        $validator = Validator::make($request->all(), [
            'car_identifier' => 'required_without:registration_number|string',
            'registration_number' => 'required_without:car_identifier|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Données invalides',
                'details' => $validator->errors(),
            ], 400);
        }

        // Trouver le véhicule par registration_number ou car_identifier
        $identifier = $request->input('car_identifier') ?? $request->input('registration_number');
        
        $car = Car::where('registration_number', $identifier)->first();

        if (!$car) {
            return response()->json([
                'success' => false,
                'error' => 'Véhicule non trouvé',
                'available' => false,
            ], 404);
        }

        // Vérifier la disponibilité de base
        $isAvailable = $car->is_available;

        // Si des dates sont fournies, vérifier aussi la disponibilité pour ces dates
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            $isAvailable = $rentalService->checkAvailability($car, $startDate, $endDate);
        }

        return response()->json([
            'success' => true,
            'available' => $isAvailable,
            'car' => [
                'id' => $car->id,
                'brand' => $car->brand,
                'model' => $car->model,
                'registration_number' => $car->registration_number,
            ],
        ]);
    }
}
