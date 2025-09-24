<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Avis;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;

class CarReviewController extends Controller
{
    public function store(Request $request, Car $car)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Vérifier que l'utilisateur a loué cette voiture
        $client = Auth::user()->client;
        if (!$client) {
            return response()->json(['error' => 'Client non trouvé'], 404);
        }

        // Vérifier qu'il y a une location terminée pour cette voiture
        $rental = Rental::where('user_id', Auth::id())
            ->where('car_id', $car->id)
            ->where('status', 'completed')
            ->first();

        if (!$rental) {
            return response()->json(['error' => 'Vous devez avoir loué cette voiture pour la noter'], 403);
        }

        // Vérifier qu'il n'y a pas déjà un avis pour cette location
        $existingReview = Avis::where('rental_id', $rental->id)->first();
        if ($existingReview) {
            return response()->json(['error' => 'Vous avez déjà noté cette voiture'], 409);
        }

        // Créer l'avis
        $avis = Avis::create([
            'rental_id' => $rental->id,
            'client_id' => $client->id,
            'agency_id' => $car->agency_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_verified' => true,
            'is_public' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre avis a été enregistré avec succès',
            'review' => $avis->load(['client.user', 'rental.car'])
        ]);
    }

    public function update(Request $request, Avis $avis)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Vérifier que l'avis appartient au client connecté
        if ($avis->client->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $avis->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre avis a été mis à jour avec succès',
            'review' => $avis->load(['client.user', 'rental.car'])
        ]);
    }

    public function destroy(Avis $avis)
    {
        // Vérifier que l'avis appartient au client connecté
        if ($avis->client->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $avis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Votre avis a été supprimé avec succès'
        ]);
    }
}
