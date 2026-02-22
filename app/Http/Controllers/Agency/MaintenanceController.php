<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agency = Auth::user()->agency;
        
        // Get maintenance statistics
        $inProgress = $agency->maintenances()->inProgress()->count();
        $scheduled = $agency->maintenances()->scheduled()->count();
        $completed = $agency->maintenances()->completed()->count();
        $totalCost = $agency->maintenances()->completed()->sum('cost');
        
        // Get maintenances with car information
        $maintenances = $agency->maintenances()
            ->with('car')
            ->orderBy('scheduled_date', 'desc')
            ->get();
        
        return view('agence.fleet.maintenance', compact('maintenances', 'inProgress', 'scheduled', 'completed', 'totalCost'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agency = Auth::user()->agency;
        $cars = $agency->cars()->with('category')->get();
        
        return view('agence.maintenance.create', compact('cars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:routine,repair,inspection,emergency,other',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'scheduled_date' => 'required|date',
            'start_date' => 'nullable|date|after_or_equal:scheduled_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'garage_name' => 'nullable|string|max:255',
            'garage_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'mileage_at_service' => 'nullable|integer|min:0',
        ]);

        $maintenanceData = $request->all();
        $maintenanceData['agency_id'] = Auth::user()->agency->id;

        Maintenance::create($maintenanceData);

        return redirect()->route('agence.fleet.maintenance')->with('success', 'Maintenance créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        $this->authorize('view', $maintenance);
        $maintenance->load('car.category');
        
        return view('agence.maintenance.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        $this->authorize('update', $maintenance);
        $agency = Auth::user()->agency;
        $cars = $agency->cars()->with('category')->get();
        
        return view('agence.maintenance.edit', compact('maintenance', 'cars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $this->authorize('update', $maintenance);
        
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:routine,repair,inspection,emergency,other',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'scheduled_date' => 'required|date',
            'start_date' => 'nullable|date|after_or_equal:scheduled_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'garage_name' => 'nullable|string|max:255',
            'garage_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'mileage_at_service' => 'nullable|integer|min:0',
        ]);

        $maintenance->update($request->all());

        return redirect()->route('agence.fleet.maintenance')->with('success', 'Maintenance modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $this->authorize('delete', $maintenance);
        
        $maintenance->delete();

        return redirect()->route('agence.fleet.maintenance')->with('success', 'Maintenance supprimée avec succès.');
    }

    /**
     * Update maintenance status
     */
    public function updateStatus(Request $request, Maintenance $maintenance)
    {
        $this->authorize('update', $maintenance);
        
        $request->validate([
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        $maintenance->update([
            'status' => $request->status,
            'start_date' => $request->status === 'in_progress' ? now() : $maintenance->start_date,
            'end_date' => $request->status === 'completed' ? now() : $maintenance->end_date,
        ]);

        return redirect()->back()->with('success', 'Statut de maintenance mis à jour.');
    }
}
