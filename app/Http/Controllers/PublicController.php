<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Car;
use App\Services\RentalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
            })
            ->values(); // Reset keys after sorting
        
        // Top picks: prioritize featured cars, then featured agency cars, then by rating (limited to 4)
        $topCars = $allCars->take(4);
        
        // Paginate the discover cars (21 per page)
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 21;
        $currentItems = $allCars->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $discoverCars = new LengthAwarePaginator(
            $currentItems,
            $allCars->count(),
            $perPage,
            $currentPage,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
        
        // Append query parameters to pagination links
        $discoverCars->appends($request->query());

        return view('public.home', compact('topCars', 'discoverCars', 'categories'));
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

        // Utiliser la même vue que la page client pour avoir le même design
        return view('client.cars.show', compact('car'));
    }

    /**
     * Get car details as JSON for modal display
     */
    public function getCarDetails(Agency $agency, Car $car)
    {
        if ($agency->status !== 'approved' || $car->agency_id !== $agency->id) {
            return response()->json(['error' => 'Voiture non trouvée'], 404);
        }

        if ($car->status !== 'available') {
            return response()->json(['error' => 'Cette voiture n\'est pas disponible'], 404);
        }

        $car->load(['agency.user', 'category']);
        
        // Get all images
        $allImages = [];
        if ($car->image_url) {
            $allImages[] = $car->image_url;
        }
        if ($car->picture_urls && is_array($car->picture_urls)) {
            $allImages = array_merge($allImages, $car->picture_urls);
        }
        $allImages = array_unique($allImages);
        
        // Get ratings data
        $averageRating = $car->getAverageRating();
        $totalReviews = $car->getTotalReviews();
        
        return response()->json([
            'success' => true,
            'car' => [
                'id' => $car->id,
                'brand' => $car->brand,
                'model' => $car->model,
                'year' => $car->year,
                'fuel_type' => $car->fuel_type,
                'transmission' => $car->transmission,
                'seats' => $car->seats,
                'doors' => $car->doors,
                'color' => $car->color,
                'registration_number' => $car->registration_number,
                'engine_size' => $car->engine_size,
                'mileage' => $car->mileage,
                'description' => $car->description,
                'features' => $car->features,
                'client_price_per_day' => $car->client_price_per_day,
                'status' => $car->status,
                'images' => $allImages,
                'main_image' => $allImages[0] ?? null,
                'other_images' => array_slice($allImages, 1, 4),
                'average_rating' => $averageRating,
                'total_reviews' => $totalReviews,
                'agency' => [
                    'id' => $car->agency->id,
                    'agency_name' => $car->agency->agency_name,
                    'city' => $car->agency->city,
                    'address' => $car->agency->address,
                    'phone' => $car->agency->phone,
                    'email' => $car->agency->email,
                ],
                'category' => $car->category ? [
                    'id' => $car->category->id,
                    'name' => $car->category->name,
                ] : null,
            ],
        ]);
    }

    /**
     * Check car availability for specific dates (public endpoint)
     */
    public function checkCarAvailability(Request $request, Agency $agency, Car $car, RentalService $rentalService)
    {
        if ($agency->status !== 'approved' || $car->agency_id !== $agency->id) {
            return response()->json(['error' => 'Voiture non trouvée'], 404);
        }

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $isAvailable = $rentalService->checkAvailability($car, $startDate, $endDate);
        
        $days = $startDate->diffInDays($endDate);
        $clientPricePerDay = $car->client_price_per_day;
        $totalPrice = $days * $clientPricePerDay;

        return response()->json([
            'available' => $isAvailable,
            'days' => $days,
            'total_price' => $totalPrice,
            'price_per_day' => $clientPricePerDay,
            'message' => $isAvailable 
                ? 'Véhicule disponible pour ces dates' 
                : 'Véhicule non disponible pour ces dates. Veuillez choisir d\'autres dates.'
        ]);
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
     * API endpoint to search cities by name prefix
     * Used for autocomplete in search forms
     */
    public function searchCities(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:100',
        ]);

        $query = $request->input('q');
        
        // Search cities from approved agencies
        $cities = Agency::where('status', 'approved')
            ->whereNotNull('city')
            ->where('city', 'like', $query . '%')
            ->select('city', DB::raw('COUNT(*) as count'))
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->orderBy('city', 'asc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->city,
                    'count' => $item->count,
                ];
            });

        return response()->json([
            'success' => true,
            'cities' => $cities,
        ]);
    }

    /**
     * Show Wishlists page
     */
    public function wishlists()
    {
        $wishlists = collect([]);
        
        try {
            if (auth()->check() && auth()->user()->isClient()) {
                $wishlists = auth()->user()->wishlists()
                    ->withCount('items')
                    ->with(['items' => function($query) {
                        $query->with(['car' => function($q) {
                            $q->with('agency');
                        }]);
                    }])
                    ->get();
            }
        } catch (\Exception $e) {
            \Log::error('Error loading wishlists: ' . $e->getMessage());
            $wishlists = collect([]);
        }
        
        return view('public.wishlists', compact('wishlists'));
    }

    /**
     * Show messages page (public version without sidebar)
     */
    public function messages()
    {
        if (!auth()->check() || !auth()->user()->isClient()) {
            return redirect()->route('login')
                ->with('message', 'Veuillez vous connecter pour accéder à vos messages.');
        }

        $user = auth()->user();
        $client = $user->client;
        
        // Récupérer les réservations actives avec messages
        $rentals = \App\Models\Rental::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['car', 'agency.user', 'messages' => function($query) use ($user) {
                $query->where('receiver_id', $user->id)
                      ->where('receiver_type', $user->getMessageType())
                      ->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Compter les messages non lus pour chaque réservation
        foreach ($rentals as $rental) {
            $rental->unread_count = $rental->getUnreadMessagesCountForUser($user->id, $user->getMessageType());
            $rental->last_message = $rental->messages->first();
            $rental->conversation_type = 'rental';
        }

        // Récupérer UNIQUEMENT les tickets de support créés par ce client
        $supportTickets = \App\Models\SupportTicket::where('client_id', $client->id)
            ->whereNull('agency_id')
            ->with(['messages' => function($query) use ($client) {
                $query->where('recipient_id', $client->id)
                      ->where('recipient_type', 'App\Models\Client')
                      ->orderBy('created_at', 'desc');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Compter les messages non lus pour chaque ticket
        foreach ($supportTickets as $ticket) {
            $ticket->unread_count = $ticket->getUnreadMessagesCount($client);
            $ticket->last_message = $ticket->messages->first();
            $ticket->conversation_type = 'support';
        }

        // Combiner et trier toutes les conversations par date de dernière activité
        $allConversations = collect()
            ->merge($rentals->map(function($rental) {
                return (object) [
                    'id' => 'rental_' . $rental->id,
                    'type' => 'rental',
                    'title' => $rental->car->brand . ' ' . $rental->car->model,
                    'subtitle' => $rental->agency->agency_name . ' • ' . $rental->start_date->format('d/m/Y') . ' - ' . $rental->end_date->format('d/m/Y'),
                    'unread_count' => $rental->unread_count,
                    'last_message' => $rental->last_message,
                    'last_activity' => $rental->last_message ? $rental->last_message->created_at : $rental->created_at,
                    'status' => 'active',
                    'image' => $rental->car->image_url,
                    'original' => $rental
                ];
            }))
            ->merge($supportTickets->map(function($ticket) {
                return (object) [
                    'id' => 'support_' . $ticket->id,
                    'type' => 'support',
                    'title' => $ticket->subject,
                    'subtitle' => 'Support • Ticket #' . $ticket->ticket_number . ' • ' . $ticket->created_at->format('d/m/Y'),
                    'unread_count' => $ticket->unread_count,
                    'last_message' => $ticket->last_message,
                    'last_activity' => $ticket->last_message ? $ticket->last_message->created_at : $ticket->created_at,
                    'status' => $ticket->status,
                    'image' => null,
                    'original' => $ticket
                ];
            }))
            ->sortByDesc('last_activity')
            ->values();

        return view('public.messages.index', compact('allConversations', 'rentals', 'supportTickets'));
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
