<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Car;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'review_type' => 'required|in:car,agency',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'car_id' => 'required_if:review_type,car|exists:cars,id',
            'agency_id' => 'required_if:review_type,agency|exists:agencies,id',
        ]);

        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return back()->with('error', 'Vous devez être connecté pour laisser un avis.');
        }

        $reviewData = [
            'user_id' => Auth::id(),
            'review_type' => $request->review_type,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Les avis sont modérés
        ];

        if ($request->review_type === 'car') {
            $reviewData['car_id'] = $request->car_id;
            
            // Vérifier si l'utilisateur a déjà laissé un avis pour ce véhicule
            $existingReview = Review::where('user_id', Auth::id())
                ->where('car_id', $request->car_id)
                ->where('review_type', 'car')
                ->first();
                
            if ($existingReview) {
                return back()->with('error', 'Vous avez déjà laissé un avis pour ce véhicule.');
            }
        } else {
            $reviewData['agency_id'] = $request->agency_id;
            
            // Vérifier si l'utilisateur a déjà laissé un avis pour cette agence
            $existingReview = Review::where('user_id', Auth::id())
                ->where('agency_id', $request->agency_id)
                ->where('review_type', 'agency')
                ->first();
                
            if ($existingReview) {
                return back()->with('error', 'Vous avez déjà laissé un avis pour cette agence.');
            }
        }

        Review::create($reviewData);

        return back()->with('success', 'Votre avis a été soumis et sera examiné avant publication.');
    }

    public function index(Request $request)
    {
        $query = Review::with(['user', 'car', 'agency']);

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('review_type')) {
            $query->where('review_type', $request->review_type);
        }

        if ($request->has('car_id')) {
            $query->where('car_id', $request->car_id);
        }

        if ($request->has('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        $reviews = $query->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->approve();
        
        return back()->with('success', 'Avis approuvé avec succès.');
    }

    public function reject(Review $review)
    {
        $review->reject();
        
        return back()->with('success', 'Avis rejeté avec succès.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        
        return back()->with('success', 'Avis supprimé avec succès.');
    }

    // API endpoints pour récupérer les avis
    public function getCarReviews(Car $car)
    {
        $reviews = $car->approvedReviews()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => $car->getAverageRating(),
            'total_reviews' => $car->getReviewsCount()
        ]);
    }

    public function getAgencyReviews(Agency $agency)
    {
        $reviews = $agency->approvedReviews()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => $agency->getAverageRating(),
            'total_reviews' => $agency->getReviewsCount()
        ]);
    }
}