<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Agency;
use App\Models\Rental;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['agency', 'rentals']);

        // Advanced filtering
        if ($request->has('status') && $request->status !== '') {
            // Normalize incoming status (supports FR labels just in case)
            $rawStatus = strtolower(trim($request->status));
            $statusMap = [
                'available' => Car::STATUS_AVAILABLE,
                'disponible' => Car::STATUS_AVAILABLE,
                'rented' => Car::STATUS_RENTED,
                'en location' => Car::STATUS_RENTED,
                'location' => Car::STATUS_RENTED,
                'maintenance' => Car::STATUS_MAINTENANCE,
                'en maintenance' => Car::STATUS_MAINTENANCE,
            ];

            $normalizedStatus = $statusMap[$rawStatus] ?? $rawStatus;

            if (in_array($normalizedStatus, [Car::STATUS_AVAILABLE, Car::STATUS_RENTED, Car::STATUS_MAINTENANCE], true)) {
                $query->where('cars.status', $normalizedStatus);
            }
        }

        if ($request->has('brand') && $request->brand !== '') {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        if ($request->has('agency_id') && $request->agency_id !== '') {
            $query->where('agency_id', $request->agency_id);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('registration_number', 'like', '%' . $search . '%')
                  ->orWhereHas('agency', function($agencyQuery) use ($search) {
                      $agencyQuery->where('agency_name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('price_min') && $request->price_min !== '') {
            $query->where('price_per_day', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max !== '') {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        if ($request->has('year_from') && $request->year_from !== '') {
            $query->where('year', '>=', $request->year_from);
        }

        if ($request->has('year_to') && $request->year_to !== '') {
            $query->where('year', '<=', $request->year_to);
        }

        $vehicles = $query->latest()->paginate(15);

        // Get statistics
        $statistics = [
            'total' => Car::count(),
            'available' => Car::where('status', 'available')->count(),
            'rented' => Car::where('status', 'rented')->count(),
            'maintenance' => Car::where('status', 'maintenance')->count(),
            'totalAgencies' => Agency::where('status', 'approved')->count(),
            'averagePrice' => Car::avg('price_per_day'),
            'mostPopularBrand' => $this->getMostPopularBrand(),
        ];

        // Get agencies for filter dropdown
        $agencies = Agency::where('status', 'approved')->orderBy('agency_name')->get();

        return view('admin.vehicles.index', compact('vehicles', 'statistics', 'agencies'));
    }

    public function categories(Request $request)
    {
        // Get car categories/brands with statistics
        $categories = Car::select('brand', DB::raw('count(*) as total_cars'), DB::raw('avg(price_per_day) as avg_price'))
            ->groupBy('brand')
            ->orderBy('total_cars', 'desc')
            ->get();

        // Get fuel type distribution
        $fuelTypes = Car::select('fuel_type', DB::raw('count(*) as count'))
            ->groupBy('fuel_type')
            ->get();

        // Get year distribution
        $yearDistribution = Car::select('year', DB::raw('count(*) as count'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return view('admin.vehicles.categories', compact('categories', 'fuelTypes', 'yearDistribution'));
    }

    public function show(Car $vehicle)
    {
        $vehicle->load(['agency', 'rentals.user']);

        // Get vehicle statistics
        $statistics = [
            'totalRentals' => $vehicle->rentals()->count(),
            'activeRentals' => $vehicle->rentals()->where('status', 'active')->count(),
            'completedRentals' => $vehicle->rentals()->where('status', 'completed')->count(),
            'totalRevenue' => $vehicle->rentals()->whereIn('status', ['active', 'completed'])->sum('total_price'),
            'averageRentalDuration' => $this->getAverageRentalDuration($vehicle),
            'utilizationRate' => $this->getUtilizationRate($vehicle),
            'lastRental' => $vehicle->rentals()->latest()->first(),
        ];

        // Get rental history
        $rentalHistory = $vehicle->rentals()
            ->with(['user', 'agency'])
            ->latest()
            ->paginate(10);

        // Get monthly performance
        $monthlyPerformance = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $monthlyPerformance[] = [
                'rentals' => $vehicle->rentals()
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'revenue' => $vehicle->rentals()
                    ->whereIn('status', ['active', 'completed'])
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_price'),
            ];
        }

        return view('admin.vehicles.show-simple', compact('vehicle', 'statistics', 'rentalHistory', 'monthlyPerformance', 'labels'));
    }

    public function fleetAnalytics()
    {
        // Overall fleet statistics
        $fleetStats = [
            'totalVehicles' => Car::count(),
            'availableVehicles' => Car::where('status', 'available')->count(),
            'rentedVehicles' => Car::where('status', 'rented')->count(),
            'maintenanceVehicles' => Car::where('status', 'maintenance')->count(),
            'averagePrice' => Car::avg('price_per_day'),
            'totalRevenue' => Rental::whereIn('status', ['active', 'completed'])->sum('total_price'),
        ];

        // Top performing vehicles
        $topPerformers = Car::with(['agency'])
            ->withCount(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }])
            ->withSum(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }], 'total_price')
            ->orderBy('rentals_sum_total_price', 'desc')
            ->take(10)
            ->get();

        // Brand performance
        $brandPerformance = Car::select('brand', 
                DB::raw('count(*) as total_cars'),
                DB::raw('avg(price_per_day) as avg_price'),
                DB::raw('sum(case when status = "available" then 1 else 0 end) as available_cars')
            )
            ->groupBy('brand')
            ->orderBy('total_cars', 'desc')
            ->get();

        // Monthly fleet utilization
        $monthlyUtilization = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $totalCars = Car::whereMonth('created_at', '<=', $date->month)
                ->whereYear('created_at', '<=', $date->year)
                ->count();
            
            $rentedCars = Rental::whereIn('status', ['active'])
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $monthlyUtilization[] = $totalCars > 0 ? round(($rentedCars / $totalCars) * 100, 2) : 0;
        }

        return view('admin.vehicles.fleet-analytics', compact(
            'fleetStats', 
            'topPerformers', 
            'brandPerformance', 
            'monthlyUtilization', 
            'labels'
        ));
    }

    public function updateStatus(Request $request, Car $vehicle)
    {
        $request->validate([
            'status' => 'required|in:available,rented,maintenance',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $vehicle->status;
        $vehicle->update([
            'status' => $request->status,
        ]);

        // Log the status change
        \Log::info("Vehicle {$vehicle->id} status changed from {$oldStatus} to {$request->status} by admin " . auth()->user()->name);

        return redirect()->route('admin.vehicles.show', $vehicle)
            ->with('success', 'Le statut du véhicule a été mis à jour avec succès.');
    }

    public function export(Request $request)
    {
        $query = Car::with(['agency']);

        // Apply same filters as index
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('brand') && $request->brand !== '') {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        if ($request->has('agency_id') && $request->agency_id !== '') {
            $query->where('agency_id', $request->agency_id);
        }

        $vehicles = $query->get();

        $filename = 'vehicles_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($vehicles) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Marque', 'Modèle', 'Immatriculation', 'Année', 'Prix/jour', 
                'Statut', 'Agence', 'Total réservations', 'Revenus totaux'
            ]);

            foreach ($vehicles as $vehicle) {
                fputcsv($file, [
                    $vehicle->id,
                    $vehicle->brand,
                    $vehicle->model,
                    $vehicle->registration_number,
                    $vehicle->year,
                    $vehicle->price_per_day,
                    $vehicle->status,
                    $vehicle->agency->agency_name ?? 'N/A',
                    $vehicle->rentals()->count(),
                    $vehicle->rentals()->whereIn('status', ['active', 'completed'])->sum('total_price')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getMostPopularBrand()
    {
        $brand = Car::select('brand', DB::raw('count(*) as count'))
            ->groupBy('brand')
            ->orderBy('count', 'desc')
            ->first();

        return $brand ? $brand->brand : 'N/A';
    }

    private function getAverageRentalDuration(Car $vehicle)
    {
        $rentals = $vehicle->rentals()->whereIn('status', ['active', 'completed'])->get();
        
        if ($rentals->isEmpty()) {
            return 0;
        }

        $totalDays = $rentals->sum(function($rental) {
            return $rental->start_date->diffInDays($rental->end_date);
        });

        return round($totalDays / $rentals->count(), 1);
    }

    private function getUtilizationRate(Car $vehicle)
    {
        $totalDays = 365; // Last year
        $rentedDays = $vehicle->rentals()
            ->whereIn('status', ['active', 'completed'])
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->get()
            ->sum(function($rental) {
                return $rental->start_date->diffInDays($rental->end_date);
            });

        return $totalDays > 0 ? round(($rentedDays / $totalDays) * 100, 2) : 0;
    }
}
