<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index()
    {
        $cars = Auth::user()->agency->cars()->with('category')->get();
        return view('agence.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('agence.cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:cars',
            'year' => 'required|digits:4|integer',
            'price_per_day' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'seats' => 'nullable|integer|min:1|max:20',
            'engine_size' => 'nullable|string|max:255',
            'mileage' => 'nullable|integer|min:0',
            'maintenance_due' => 'nullable|date|after:today',
            'last_maintenance' => 'nullable|date|before:today',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'pictures' => 'required|array|min:1|max:4',
            'pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock_quantity' => 'required|integer|min:1',
            'track_stock' => 'boolean',
        ]);

        $carData = $request->all();
        $carData['available_stock'] = $carData['stock_quantity'];
        $carData['track_stock'] = $request->has('track_stock');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $carData['image'] = $request->file('image')->store('cars', 'public');
        }
        
        // Handle multiple pictures upload
        if ($request->hasFile('pictures')) {
            $pictures = [];
            foreach ($request->file('pictures') as $picture) {
                $pictures[] = $picture->store('cars', 'public');
            }
            $carData['pictures'] = $pictures;
        }
        
        Auth::user()->agency->cars()->create($carData);

        return redirect()->route('agence.cars.index')->with('success', 'Voiture ajoutée avec succès.');
    }

    public function show(Car $car)
    {
        $this->authorize('view', $car);
        $car->load('category');
        return view('agence.cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        $this->authorize('update', $car);
        $car->load('category');
        return view('agence.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $this->authorize('update', $car);

        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:cars,registration_number,' . $car->id,
            'year' => 'required|digits:4|integer',
            'price_per_day' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'seats' => 'nullable|integer|min:1|max:20',
            'engine_size' => 'nullable|string|max:255',
            'mileage' => 'nullable|integer|min:0',
            'maintenance_due' => 'nullable|date',
            'last_maintenance' => 'nullable|date',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'pictures' => 'nullable|array|max:4',
            'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock_quantity' => 'required|integer|min:1',
            'available_stock' => 'required|integer|min:0',
            'track_stock' => 'boolean',
        ]);

        $carData = $request->all();
        $carData['track_stock'] = $request->has('track_stock');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $carData['image'] = $request->file('image')->store('cars', 'public');
        }
        
        // Handle multiple pictures upload
        if ($request->hasFile('pictures')) {
            $pictures = [];
            foreach ($request->file('pictures') as $picture) {
                $pictures[] = $picture->store('cars', 'public');
            }
            $carData['pictures'] = $pictures;
        } else {
            // Keep existing pictures if no new ones uploaded
            unset($carData['pictures']);
        }
        
        $car->update($carData);

        return redirect()->route('agence.cars.index')->with('success', 'Voiture modifiée avec succès.');
    }

    public function destroy(Car $car)
    {
        $this->authorize('delete', $car);
        $car->delete();
        return redirect()->route('agence.cars.index')->with('success', 'Voiture supprimée avec succès.');
    }

    public function updateStatus(Request $request, Car $car)
    {
        $this->authorize('update', $car);
        
        $request->validate([
            'status' => 'required|in:available,rented,maintenance',
        ]);

        $car->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Statut du véhicule mis à jour avec succès.');
    }

    public function categories()
    {
        // This method is now handled by CategoryController
        return redirect()->route('agence.fleet.categories');
    }

    public function maintenance()
    {
        $agency = Auth::user()->agency;
        $cars = $agency->cars()
            ->where('status', 'maintenance')
            ->orWhere('maintenance_due', '<=', now()->addDays(30))
            ->orderBy('maintenance_due')
            ->get();
        
        return view('agence.fleet.maintenance', compact('cars'));
    }

    public function analytics(Request $request)
    {
        $agency = Auth::user()->agency;
        
        // Fleet statistics
        $totalCars = $agency->cars()->count();
        $availableCars = $agency->cars()->where('status', 'available')->count();
        $rentedCars = $agency->rentals()->where('status', 'active')->count();
        $maintenanceCars = $agency->cars()->where('status', 'maintenance')->count();
        
        // Calculate utilization rate
        $utilizationRate = $totalCars > 0 ? round(($rentedCars / $totalCars) * 100, 1) : 0;
        
        // Revenue analytics
        $monthlyRevenue = $agency->rentals()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total_price');
        
        // Revenue per vehicle
        $revenuePerVehicle = $totalCars > 0 ? round($monthlyRevenue / $totalCars, 0) : 0;
        
        // Average rental duration
        $avgDuration = $agency->rentals()
            ->where('status', 'completed')
            ->whereNotNull('end_date')
            ->whereNotNull('start_date')
            ->selectRaw('AVG(DATEDIFF(end_date, start_date)) as avg_days')
            ->value('avg_days') ?? 0;
        
        // Customer satisfaction (mock data for now)
        $satisfaction = 4.8;
        
        // Popular categories with revenue
        $categoryStats = $agency->cars()
            ->join('categories', 'cars.category_id', '=', 'categories.id')
            ->leftJoin('rentals', function($join) {
                $join->on('cars.id', '=', 'rentals.car_id')
                     ->where('rentals.status', 'completed')
                     ->whereMonth('rentals.created_at', now()->month)
                     ->whereYear('rentals.created_at', now()->year);
            })
            ->selectRaw('
                categories.name as category, 
                COUNT(DISTINCT cars.id) as count, 
                AVG(cars.price_per_day) as avg_price,
                COALESCE(SUM(rentals.total_price), 0) as revenue
            ')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
        
        // Top performing vehicles
        $topVehicles = $agency->cars()
            ->leftJoin('rentals', function($join) {
                $join->on('cars.id', '=', 'rentals.car_id')
                     ->where('rentals.status', 'completed')
                     ->whereMonth('rentals.created_at', now()->month)
                     ->whereYear('rentals.created_at', now()->year);
            })
            ->selectRaw('
                cars.id,
                cars.brand,
                cars.model,
                cars.year,
                COUNT(rentals.id) as rental_count,
                COALESCE(SUM(rentals.total_price), 0) as revenue
            ')
            ->groupBy('cars.id', 'cars.brand', 'cars.model', 'cars.year')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();
        
        // Maintenance costs by type
        $maintenanceCosts = $agency->maintenances()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('
                type,
                SUM(cost) as total_cost
            ')
            ->groupBy('type')
            ->get();
        
        // Fleet utilization data for chart
        $fleetUtilization = [
            'available' => $availableCars,
            'rented' => $rentedCars,
            'maintenance' => $maintenanceCars
        ];
        
        // Export functionality
        if ($request->has('export')) {
            return $this->exportAnalytics($agency, $categoryStats, $topVehicles, $maintenanceCosts);
        }
        
        return view('agence.fleet.analytics', compact(
            'totalCars', 
            'availableCars', 
            'rentedCars', 
            'maintenanceCars',
            'utilizationRate',
            'monthlyRevenue',
            'revenuePerVehicle',
            'avgDuration',
            'satisfaction',
            'categoryStats',
            'topVehicles',
            'maintenanceCosts',
            'fleetUtilization'
        ));
    }
    
    private function exportAnalytics($agency, $categoryStats, $topVehicles, $maintenanceCosts)
    {
        $filename = 'fleet_analytics_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($agency, $categoryStats, $topVehicles, $maintenanceCosts) {
            $file = fopen('php://output', 'w');
            
            // Fleet Overview
            fputcsv($file, ['Fleet Analytics Report - ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);
            fputcsv($file, ['Fleet Overview']);
            fputcsv($file, ['Total Cars', $agency->cars()->count()]);
            fputcsv($file, ['Available Cars', $agency->cars()->where('status', 'available')->count()]);
            fputcsv($file, ['Rented Cars', $agency->rentals()->where('status', 'active')->count()]);
            fputcsv($file, ['Maintenance Cars', $agency->cars()->where('status', 'maintenance')->count()]);
            fputcsv($file, []);
            
            // Category Statistics
            fputcsv($file, ['Category Statistics']);
            fputcsv($file, ['Category', 'Vehicle Count', 'Average Price', 'Revenue']);
            foreach ($categoryStats as $stat) {
                fputcsv($file, [$stat->category, $stat->count, $stat->avg_price, $stat->revenue]);
            }
            fputcsv($file, []);
            
            // Top Performing Vehicles
            fputcsv($file, ['Top Performing Vehicles']);
            fputcsv($file, ['Vehicle', 'Rental Count', 'Revenue']);
            foreach ($topVehicles as $vehicle) {
                fputcsv($file, [$vehicle->brand . ' ' . $vehicle->model . ' ' . $vehicle->year, $vehicle->rental_count, $vehicle->revenue]);
            }
            fputcsv($file, []);
            
            // Maintenance Costs
            fputcsv($file, ['Maintenance Costs by Type']);
            fputcsv($file, ['Type', 'Total Cost']);
            foreach ($maintenanceCosts as $cost) {
                fputcsv($file, [$cost->type, $cost->total_cost]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
