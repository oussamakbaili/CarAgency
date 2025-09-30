<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    /**
     * Show the public homepage with top-rated agencies
     */
    public function home()
    {
        // Get top-rated agencies (for now, we'll use approved agencies with most cars)
        $topAgencies = Agency::where('status', 'approved')
            ->withCount('cars')
            ->with('user')
            ->orderBy('cars_count', 'desc')
            ->take(6)
            ->get();

        return view('public.home', compact('topAgencies'));
    }

    /**
     * Show all agencies with search and filter
     */
    public function agencies(Request $request)
    {
        $query = Agency::where('status', 'approved')
            ->withCount('cars')
            ->with('user');

        // Search by agency name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('agency_name', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by minimum rating (if we had reviews)
        if ($request->filled('min_rating')) {
            // For now, we'll just show all agencies
            // In the future, this would filter by actual ratings
        }

        $agencies = $query->orderBy('agency_name')->paginate(12);

        return view('public.agencies', compact('agencies'));
    }

    /**
     * Show agency details with reviews
     */
    public function showAgency(Agency $agency)
    {
        if ($agency->status !== 'approved') {
            abort(404, 'Agence non trouvée');
        }

        $agency->load(['user', 'cars' => function($query) {
            $query->where('status', 'available');
        }]);

        // Get recent reviews (for now, we'll create mock data)
        $reviews = collect([
            (object)[
                'id' => 1,
                'user_name' => 'Ahmed Benali',
                'rating' => 5,
                'comment' => 'Excellent service, voiture en parfait état. Je recommande vivement cette agence.',
                'created_at' => now()->subDays(2)
            ],
            (object)[
                'id' => 2,
                'user_name' => 'Fatima Alami',
                'rating' => 4,
                'comment' => 'Très bon accueil et voiture propre. Prix raisonnable.',
                'created_at' => now()->subDays(5)
            ],
            (object)[
                'id' => 3,
                'user_name' => 'Omar Tazi',
                'rating' => 5,
                'comment' => 'Service professionnel, je reviendrai certainement.',
                'created_at' => now()->subDays(7)
            ]
        ]);

        return view('public.agency.show', compact('agency', 'reviews'));
    }

    /**
     * Show agency cars
     */
    public function agencyCars(Agency $agency)
    {
        if ($agency->status !== 'approved') {
            abort(404, 'Agence non trouvée');
        }

        $query = $agency->cars()->where('status', 'available');

        // Filter by brand
        if (request()->filled('brand')) {
            $query->where('brand', 'like', '%' . request('brand') . '%');
        }

        // Filter by model
        if (request()->filled('model')) {
            $query->where('model', 'like', '%' . request('model') . '%');
        }

        // Filter by price range
        if (request()->filled('min_price')) {
            $query->where('price_per_day', '>=', request('min_price'));
        }

        if (request()->filled('max_price')) {
            $query->where('price_per_day', '<=', request('max_price'));
        }

        // Filter by fuel type
        if (request()->filled('fuel_type')) {
            $query->where('fuel_type', request('fuel_type'));
        }

        // Filter by transmission
        if (request()->filled('transmission')) {
            $query->where('transmission', request('transmission'));
        }

        $cars = $query->orderBy('brand')->orderBy('model')->paginate(12);

        return view('public.agency.cars', compact('agency', 'cars'));
    }

    /**
     * Show car details
     */
    public function showCar(Agency $agency, Car $car)
    {
        if ($agency->status !== 'approved' || $car->agency_id !== $agency->id) {
            abort(404, 'Voiture non trouvée');
        }

        if ($car->status !== 'available') {
            abort(404, 'Cette voiture n\'est pas disponible');
        }

        $car->load('agency.user');

        return view('public.car.show', compact('agency', 'car'));
    }

    /**
     * Handle search requests
     */
    public function search(Request $request)
    {
        $query = Agency::where('status', 'approved')
            ->withCount('cars')
            ->with('user');

        // Search by agency name
        if ($request->filled('agency')) {
            $query->where('agency_name', 'like', '%' . $request->agency . '%');
        }

        // Search by car brand/model
        if ($request->filled('car')) {
            $query->whereHas('cars', function($q) use ($request) {
                $q->where('brand', 'like', '%' . $request->car . '%')
                  ->orWhere('model', 'like', '%' . $request->car . '%');
            });
        }

        // Search by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $agencies = $query->orderBy('agency_name')->paginate(12);

        return view('public.agencies', compact('agencies'));
    }

    /**
     * Redirect to login when trying to book without authentication
     */
    public function requireLogin(Request $request)
    {
        // Store the intended URL in the session
        session(['url.intended' => $request->header('referer')]);
        
        return redirect()->route('login')
            ->with('message', 'Veuillez vous connecter pour effectuer une réservation.');
    }
}
