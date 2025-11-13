<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Get user's wishlists
     */
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->withCount('items')->get();
        return response()->json($wishlists);
    }

    /**
     * Create a new wishlist
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $wishlist = Auth::user()->wishlists()->create([
                'name' => $request->name,
                'description' => $request->description,
                'is_public' => $request->is_public ?? false,
            ]);

            return response()->json($wishlist, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating wishlist: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating wishlist. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add car to wishlist
     */
    public function addCar(Request $request, $wishlistId)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
        ]);

        $wishlist = Auth::user()->wishlists()->findOrFail($wishlistId);

        // Check if car already in wishlist
        $exists = $wishlist->items()->where('car_id', $request->car_id)->exists();
        
        if ($exists) {
            return response()->json(['message' => 'Car already in wishlist'], 400);
        }

        $wishlist->items()->create([
            'car_id' => $request->car_id,
        ]);

        return response()->json(['message' => 'Car added to wishlist successfully'], 200);
    }

    /**
     * Remove car from wishlist
     */
    public function removeCar($wishlistId, $carId)
    {
        $wishlist = Auth::user()->wishlists()->findOrFail($wishlistId);
        
        $wishlist->items()->where('car_id', $carId)->delete();

        return response()->json(['message' => 'Car removed from wishlist successfully'], 200);
    }

    /**
     * Check if car is in any wishlist
     */
    public function checkCar($carId)
    {
        $wishlists = Auth::user()->wishlists()->with(['items' => function($query) use ($carId) {
            $query->where('car_id', $carId);
        }])->get();

        $inWishlists = $wishlists->filter(function($wishlist) {
            return $wishlist->items->count() > 0;
        })->pluck('id')->toArray();

        return response()->json([
            'in_wishlists' => $inWishlists,
            'wishlists' => $wishlists,
        ]);
    }

    /**
     * Delete wishlist
     */
    public function destroy($id)
    {
        $wishlist = Auth::user()->wishlists()->findOrFail($id);
        $wishlist->delete();

        return response()->json(['message' => 'Wishlist deleted successfully'], 200);
    }
}
