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
        $query = Car::availableFromApprovedAgencies()
            ->with(['agency.user']);

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
        
        // Get available brands for filter
        $brands = Car::availableFromApprovedAgencies()
            ->distinct()
            ->pluck('brand')
            ->sort();

        // Get available fuel types for filter
        $fuelTypes = Car::availableFromApprovedAgencies()
            ->whereNotNull('fuel_type')
            ->distinct()
            ->pluck('fuel_type')
            ->sort();

        return view('client.cars.index', compact('cars', 'brands', 'fuelTypes'));
    }

    public function show(Car $car)
    {
        try {
            // Load necessary relationships
            $car->load(['agency.user']);
            
            // Basic validation - just check if car exists
            if (!$car->exists) {
                abort(404, 'Car not found');
            }

            return view('client.cars.show-simple', compact('car'));
            
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
