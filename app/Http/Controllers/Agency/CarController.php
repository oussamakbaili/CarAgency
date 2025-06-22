<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index()
    {
        $cars = Auth::user()->agency->cars;
        return view('agence.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('agence.cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:cars',
            'year' => 'required|digits:4|integer',
            'price_per_day' => 'required|numeric',
            'description' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:1',
            'track_stock' => 'boolean',
        ]);

        $carData = $request->all();
        $carData['available_stock'] = $carData['stock_quantity'];
        $carData['track_stock'] = $request->has('track_stock');
        
        Auth::user()->agency->cars()->create($carData);

        return redirect()->route('agence.cars.index')->with('success', 'Voiture ajoutée avec succès.');
    }

    public function edit(Car $car)
    {
        $this->authorize('update', $car);
        return view('agence.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $this->authorize('update', $car);

        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:cars,registration_number,' . $car->id,
            'year' => 'required|digits:4|integer',
            'price_per_day' => 'required|numeric',
            'description' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:1',
            'available_stock' => 'required|integer|min:0',
            'track_stock' => 'boolean',
        ]);

        $carData = $request->all();
        $carData['track_stock'] = $request->has('track_stock');
        
        $car->update($carData);

        return redirect()->route('agence.cars.index')->with('success', 'Voiture modifiée avec succès.');
    }

    public function destroy(Car $car)
    {
        $this->authorize('delete', $car);
        $car->delete();
        return redirect()->route('agence.cars.index')->with('success', 'Voiture supprimée avec succès.');
    }
}
