<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['agency:id,agency_name,city,phone', 'category:id,name'])
            ->where('status', 'available')
            ->whereHas('agency', function ($q) {
                $q->where('status', 'approved');
            });

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filtre par ville/location
        if ($request->filled('location')) {
            $query->whereHas('agency', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%');
            });
        }

        // Filtre par prix
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        $cars = $query->orderBy('featured', 'desc')
                      ->orderBy('homepage_priority', 'desc')
                      ->paginate(15);

        $cars->getCollection()->transform(function ($car) use ($request) {
            return $this->formatCar($car, $request->user());
        });

        return response()->json($cars);
    }

    public function show(Request $request, $id)
    {
        $car = Car::with(['agency:id,agency_name,city,phone,profile_picture', 'category:id,name'])
            ->findOrFail($id);

        return response()->json([
            'data' => $this->formatCar($car, $request->user()),
        ]);
    }

    public function featured(Request $request)
    {
        $cars = Car::with(['agency:id,agency_name,city', 'category:id,name'])
            ->where('status', 'available')
            ->where('featured', true)
            ->whereHas('agency', function ($q) {
                $q->where('status', 'approved');
            })
            ->orderBy('homepage_priority', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($car) => $this->formatCar($car, $request->user()));

        return response()->json(['data' => $cars]);
    }

    public function toggleFavorite(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        $user = $request->user();

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('car_id', $id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $isFavorite = false;
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'car_id'  => $id,
            ]);
            $isFavorite = true;
        }

        return response()->json([
            'message'     => $isFavorite ? 'Ajouté aux favoris' : 'Retiré des favoris',
            'is_favorite' => $isFavorite,
        ]);
    }

    public function favorites(Request $request)
    {
        $cars = Car::with(['agency:id,agency_name,city', 'category:id,name'])
            ->whereHas('wishlists', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->get()
            ->map(fn($car) => $this->formatCar($car, $request->user()));

        return response()->json(['data' => $cars]);
    }

    private function formatCar(Car $car, $user = null): array
    {
        $baseUrl = config('app.url');

        // Construire les URLs des images
        $imageUrl = $car->image
            ? $baseUrl . '/storage/' . ltrim(preg_replace('/^(app\/)?public\//', '', $car->image), '/')
            : null;

        $pictureUrls = collect($car->pictures ?? [])->map(function ($pic) use ($baseUrl) {
            return $baseUrl . '/storage/' . ltrim(preg_replace('/^(app\/)?public\//', '', $pic), '/');
        })->filter()->values()->toArray();

        $allImages = array_filter(array_merge(
            $imageUrl ? [$imageUrl] : [],
            $pictureUrls
        ));

        // Vérifier si c'est un favori
        $isFavorite = false;
        if ($user) {
            $isFavorite = \App\Models\Wishlist::where('user_id', $user->id)
                ->where('car_id', $car->id)
                ->exists();
        }

        // Calcul de la note moyenne
        $avgRating = $car->getAverageRating();
        $reviewsCount = $car->getReviewsCount();

        return [
            'id'            => $car->id,
            'name'          => $car->brand . ' ' . $car->model,
            'brand'         => $car->brand,
            'model'         => $car->model,
            'year'          => $car->year,
            'category'      => $car->category?->name ?? 'Autre',
            'price_per_day' => (float) $car->price_per_day,
            'location'      => $car->agency?->city ?? '',
            'rating'        => round($avgRating, 1),
            'reviews_count' => $reviewsCount,
            'images'        => array_values($allImages),
            'description'   => $car->description,
            'seats'         => $car->seats,
            'transmission'  => $car->transmission,
            'fuel_type'     => $car->fuel_type,
            'color'         => $car->color,
            'mileage'       => $car->mileage,
            'is_available'  => $car->is_available,
            'is_favorite'   => $isFavorite,
            'featured'      => (bool) $car->featured,
            'agency'        => $car->agency ? [
                'id'           => $car->agency->id,
                'name'         => $car->agency->agency_name,
                'city'         => $car->agency->city,
                'phone'        => $car->agency->phone,
            ] : null,
        ];
    }
}
