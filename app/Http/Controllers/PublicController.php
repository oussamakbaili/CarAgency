<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    /**
     * Show the public homepage with featured and top-rated cars from all agencies
     */
    public function home(Request $request)
    {
        // Get active categories
        $categories = \App\Models\Category::active()->ordered()->get();
        
        // Build query for cars that should be shown on homepage
        $carsQuery = Car::whereHas('agency', function($query) {
                $query->where('status', 'approved')
                      ->showOnHomepage();  // Only show agencies marked for homepage
            })
            ->where('status', 'available')
            ->showOnHomepage()  // Only show cars marked for homepage
            ->with(['agency.user', 'category'])
            ->withCount(['avis as reviews_count' => function($query) {
                $query->where('is_public', true);
            }]);
        
        // Filter by category if provided
        if ($request->filled('category')) {
            $carsQuery->where('category_id', $request->category);
        }
        
        // Filter by location if provided
        if ($request->filled('where')) {
            $carsQuery->whereHas('agency', function($query) use ($request) {
                $query->where('city', 'like', '%' . $request->where . '%')
                      ->orWhere('address', 'like', '%' . $request->where . '%');
            });
        }
        
        // Get all cars with ratings, prioritizing featured ones and featured agency cars
        $allCars = $carsQuery->get()
            ->map(function($car) {
                $car->average_rating = $car->getAverageRating();
                return $car;
            })
            ->sortByDesc(function($car) {
                // Sort by: 
                // 1. Featured cars first (10000 points)
                // 2. Cars from featured agencies (5000 points)
                // 3. Homepage priority (0-100) * 100
                // 4. Average rating (0-5)
                $featuredCar = $car->featured ? 10000 : 0;
                $featuredAgency = $car->agency->featured ? 5000 : 0;
                $priority = $car->homepage_priority * 100;
                $rating = $car->average_rating;
                
                return $featuredCar + $featuredAgency + $priority + $rating;
            });
        
        // Top picks: prioritize featured cars, then featured agency cars, then by rating (limited to 4)
        $topCars = $allCars->take(4);
        
        // All cars for "Discover" section (up to 12)
        $discoverCars = $allCars->take(12);

        return view('public.home', compact('topCars', 'discoverCars', 'categories'));
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
    public function showAgency(Agency $agency, Request $request)
    {
        if ($agency->status !== 'approved') {
            abort(404, 'Agence non trouvée');
        }

        // Get active categories
        $categories = \App\Models\Category::active()->ordered()->get();

        // Build query for cars
        $carsQuery = $agency->cars()->where('status', 'available');
        
        // Filter by category if provided
        if ($request->filled('category')) {
            $carsQuery->where('category_id', $request->category);
        }
        
        // Get the cars
        $cars = $carsQuery->get();
        
        // Load user relationship
        $agency->load('user');

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

        return view('public.agency.show', compact('agency', 'reviews', 'categories', 'cars'));
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
     * Search cars with filters (Airbnb-style)
     */
    public function searchCars(Request $request)
    {
        // Build query for available cars
        $carsQuery = Car::whereHas('agency', function($query) {
                $query->where('status', 'approved');
            })
            ->where('status', 'available')
            ->with(['agency.user', 'category'])
            ->withCount(['avis as reviews_count' => function($query) {
                $query->where('is_public', true);
            }]);
        
        // Filter by location
        if ($request->filled('where')) {
            $carsQuery->whereHas('agency', function($query) use ($request) {
                $query->where('city', 'like', '%' . $request->where . '%')
                      ->orWhere('address', 'like', '%' . $request->where . '%');
            });
        }
        
        // Filter by number of passengers (seats)
        if ($request->filled('passengers')) {
            $carsQuery->where('seats', '>=', $request->passengers);
        }
        
        // TODO: Filter by availability dates (check_in, check_out)
        // This will require checking against existing rentals
        
        // Get cars with ratings
        $cars = $carsQuery->paginate(12)->through(function($car) {
            $car->average_rating = $car->getAverageRating();
            return $car;
        });
        
        // Pass request parameters to maintain search context
        $cars->appends($request->all());

        return view('public.cars-search', compact('cars'));
    }

    /**
     * Show About Us page
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Show How it Works page
     */
    public function howItWorks()
    {
        return view('public.how-it-works');
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
