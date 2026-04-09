<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $rentals = Rental::with(['car:id,brand,model,image,price_per_day', 'agency:id,agency_name,city'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($rental) => $this->formatRental($rental));

        return response()->json(['data' => $rentals]);
    }

    public function show(Request $request, $id)
    {
        $rental = Rental::with(['car', 'agency:id,agency_name,city,phone'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json(['data' => $this->formatRental($rental)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id'     => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $car = Car::with('agency')->findOrFail($request->car_id);

        if (!$car->is_available) {
            return response()->json([
                'message' => 'Cette voiture n\'est pas disponible.',
            ], 422);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);
        $days      = $startDate->diffInDays($endDate);

        if ($days < 1) {
            return response()->json(['message' => 'La durée minimale est de 1 jour.'], 422);
        }

        $totalPrice = $car->price_per_day * $days;

        $rental = Rental::create([
            'user_id'     => $request->user()->id,
            'car_id'      => $car->id,
            'agency_id'   => $car->agency_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'total_price' => $totalPrice,
            'status'      => 'pending',
        ]);

        $rental->load(['car:id,brand,model,image,price_per_day', 'agency:id,agency_name,city']);

        return response()->json([
            'message' => 'Réservation créée avec succès. En attente de confirmation.',
            'data'    => $this->formatRental($rental),
        ], 201);
    }

    public function cancel(Request $request, $id)
    {
        $rental = Rental::where('user_id', $request->user()->id)
            ->whereIn('status', ['pending'])
            ->findOrFail($id);

        $rental->update([
            'status'      => 'rejected',
            'rejected_at' => now(),
        ]);

        return response()->json(['message' => 'Réservation annulée avec succès.']);
    }

    private function formatRental(Rental $rental): array
    {
        $baseUrl  = config('app.url');
        $car      = $rental->car;
        $imageUrl = null;

        if ($car && $car->image) {
            $imagePath = ltrim(preg_replace('/^(app\/)?public\//', '', $car->image), '/');
            $imageUrl  = $baseUrl . '/storage/' . $imagePath;
        }

        $statusLabels = [
            'pending'   => 'En attente',
            'active'    => 'Confirmée',
            'completed' => 'Terminée',
            'rejected'  => 'Annulée',
        ];

        return [
            'id'          => $rental->id,
            'start_date'  => $rental->start_date?->format('Y-m-d'),
            'end_date'    => $rental->end_date?->format('Y-m-d'),
            'total_price' => (float) $rental->total_price,
            'status'      => $rental->status,
            'status_label'=> $statusLabels[$rental->status] ?? $rental->status,
            'created_at'  => $rental->created_at?->format('Y-m-d H:i'),
            'car'         => $car ? [
                'id'            => $car->id,
                'name'          => $car->brand . ' ' . $car->model,
                'price_per_day' => (float) $car->price_per_day,
                'images'        => $imageUrl ? [$imageUrl] : [],
            ] : null,
            'agency'      => $rental->agency ? [
                'id'   => $rental->agency->id,
                'name' => $rental->agency->agency_name,
                'city' => $rental->agency->city,
            ] : null,
        ];
    }
}
