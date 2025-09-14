<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Agency::with(['user', 'cars'])
            ->where('status', 'approved')
            ->withCount(['cars' => function($query) {
                $query->where('status', 'available');
            }])
            ->withAvg('avis', 'rating')
            ->withCount('avis');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('city', 'like', "%{$request->location}%");
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $query->having('avis_avg_rating', '>=', $request->min_rating);
        }

        // Sort by rating (default)
        $sortBy = $request->get('sort', 'rating');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('user.name');
                break;
            case 'cars_count':
                $query->orderBy('cars_count', 'desc');
                break;
            case 'reviews_count':
                $query->orderBy('avis_count', 'desc');
                break;
            case 'rating':
            default:
                $query->orderBy('avis_avg_rating', 'desc');
                break;
        }

        $agencies = $query->paginate(12);

        // Get filter options
        $cities = Agency::where('status', 'approved')
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        return view('client.agencies.index', compact('agencies', 'cities'));
    }

    public function show(Agency $agency, Request $request)
    {
        // Ensure agency is approved
        if ($agency->status !== 'approved') {
            abort(404);
        }

        // Load agency with relationships
        $agency->load(['user', 'avis' => function($query) {
            $query->with('client.user')->latest();
        }]);

        // Get agency statistics
        $stats = $this->getAgencyStats($agency);

        // Get cars with popularity sorting
        $carsQuery = Car::where('agency_id', $agency->id)
            ->where('status', 'available')
            ->withCount(['rentals' => function($query) {
                $query->where('status', 'completed');
            }])
            ->with(['agency.user']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $carsQuery->where(function($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand')) {
            $carsQuery->where('brand', $request->brand);
        }

        if ($request->filled('fuel_type')) {
            $carsQuery->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('max_price')) {
            $carsQuery->where('price_per_day', '<=', $request->max_price);
        }

        if ($request->filled('year_from')) {
            $carsQuery->where('year', '>=', $request->year_from);
        }

        // Sort by popularity (rentals count) by default
        $sortBy = $request->get('sort', 'popularity');
        switch ($sortBy) {
            case 'price_low':
                $carsQuery->orderBy('price_per_day', 'asc');
                break;
            case 'price_high':
                $carsQuery->orderBy('price_per_day', 'desc');
                break;
            case 'year_new':
                $carsQuery->orderBy('year', 'desc');
                break;
            case 'year_old':
                $carsQuery->orderBy('year', 'asc');
                break;
            case 'name':
                $carsQuery->orderBy('brand')->orderBy('model');
                break;
            case 'popularity':
            default:
                $carsQuery->orderBy('rentals_count', 'desc')->orderBy('price_per_day', 'asc');
                break;
        }

        $cars = $carsQuery->paginate(12);

        // Get filter options for this agency
        $brands = Car::where('agency_id', $agency->id)
            ->where('status', 'available')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        $fuelTypes = Car::where('agency_id', $agency->id)
            ->where('status', 'available')
            ->distinct()
            ->pluck('fuel_type')
            ->sort()
            ->values();

        return view('client.agencies.show', compact('agency', 'stats', 'cars', 'brands', 'fuelTypes'));
    }

    private function getAgencyStats($agency)
    {
        $totalCars = Car::where('agency_id', $agency->id)->count();
        $availableCars = Car::where('agency_id', $agency->id)->where('status', 'available')->count();
        
        $totalRentals = Rental::whereHas('car', function($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->count();

        $completedRentals = Rental::whereHas('car', function($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->where('status', 'completed')->count();

        $averageRating = Avis::whereHas('rental.car', function($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->avg('rating') ?? 0;

        $totalReviews = Avis::whereHas('rental.car', function($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->count();

        $recentReviews = Avis::whereHas('rental.car', function($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->with(['client.user', 'rental.car'])
        ->latest()
        ->take(5)
        ->get();

        return [
            'total_cars' => $totalCars,
            'available_cars' => $availableCars,
            'total_rentals' => $totalRentals,
            'completed_rentals' => $completedRentals,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
            'recent_reviews' => $recentReviews,
        ];
    }
}
