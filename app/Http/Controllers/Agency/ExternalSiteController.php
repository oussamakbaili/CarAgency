<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarExternalSite;
use App\Services\ExternalSiteDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExternalSiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('agency');
    }

    /**
     * Lister les sites externes pour un véhicule
     */
    public function index(Car $car)
    {
        // Vérifier que l'agence possède ce véhicule
        if ($car->agency_id !== Auth::user()->agency_id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce véhicule.');
        }

        $externalSites = $car->externalSites()->orderBy('created_at', 'desc')->get();

        return view('agence.cars.external-sites', compact('car', 'externalSites'));
    }

    /**
     * Afficher le formulaire pour ajouter un site externe
     */
    public function create(Car $car)
    {
        // Vérifier que l'agence possède ce véhicule
        if ($car->agency_id !== Auth::user()->agency_id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce véhicule.');
        }

        return view('agence.cars.external-sites-create', compact('car'));
    }

    /**
     * Stocker un nouveau site externe
     */
    public function store(Request $request, Car $car)
    {
        // Vérifier que l'agence possède ce véhicule
        if ($car->agency_id !== Auth::user()->agency_id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce véhicule.');
        }

        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url|max:255',
            'api_url' => 'required|url|max:255',
            'api_token' => 'nullable|string|max:500',
            'external_car_id' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        CarExternalSite::create([
            'car_id' => $car->id,
            'site_name' => $request->site_name,
            'site_url' => $request->site_url,
            'api_url' => $request->api_url,
            'api_token' => $request->api_token,
            'external_car_id' => $request->external_car_id,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
            'notes' => $request->notes,
        ]);

        return redirect()->route('agency.cars.external-sites', $car)
            ->with('success', 'Site externe ajouté avec succès.');
    }

    /**
     * Mettre à jour un site externe
     */
    public function update(Request $request, Car $car, CarExternalSite $externalSite)
    {
        // Vérifier que l'agence possède ce véhicule
        if ($car->agency_id !== Auth::user()->agency_id || $externalSite->car_id !== $car->id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce site externe.');
        }

        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url|max:255',
            'api_url' => 'required|url|max:255',
            'api_token' => 'nullable|string|max:500',
            'external_car_id' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $externalSite->update([
            'site_name' => $request->site_name,
            'site_url' => $request->site_url,
            'api_url' => $request->api_url,
            'api_token' => $request->api_token ?: $externalSite->api_token,
            'external_car_id' => $request->external_car_id,
            'is_active' => $request->has('is_active') ? $request->is_active : $externalSite->is_active,
            'notes' => $request->notes,
        ]);

        return redirect()->route('agency.cars.external-sites', $car)
            ->with('success', 'Site externe mis à jour avec succès.');
    }

    /**
     * Supprimer un site externe
     */
    public function destroy(Car $car, CarExternalSite $externalSite)
    {
        // Vérifier que l'agence possède ce véhicule
        if ($car->agency_id !== Auth::user()->agency_id || $externalSite->car_id !== $car->id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce site externe.');
        }

        $externalSite->delete();

        return redirect()->route('agency.cars.external-sites', $car)
            ->with('success', 'Site externe supprimé avec succès.');
    }

    /**
     * Découvrir automatiquement les sites externes qui contiennent ce véhicule
     */
    public function discover(Request $request, Car $car, ExternalSiteDiscoveryService $discoveryService)
    {
        // Vérifier que l'agence possède ce véhicule
        if ($car->agency_id !== Auth::user()->agency_id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce véhicule.');
        }

        // Liste des sites connus à vérifier (peut venir d'un formulaire ou d'une config)
        $knownSites = $request->input('known_sites', []);
        
        // Découvrir les sites
        $discoveredSites = $discoveryService->discoverSites($car, $knownSites);

        // Enregistrer automatiquement les sites découverts
        $registeredCount = 0;
        foreach ($discoveredSites as $siteData) {
            if ($siteData['verified']) {
                $discoveryService->registerExternalSite($car, $siteData);
                $registeredCount++;
            }
        }

        return redirect()->route('agency.cars.external-sites', $car)
            ->with('success', "Découverte terminée : {$registeredCount} site(s) externe(s) trouvé(s) et enregistré(s).");
    }

    /**
     * Afficher le formulaire de découverte
     */
    public function showDiscover(Car $car)
    {
        // Vérifier que l'agence possède ce véhicule
        if ($car->agency_id !== Auth::user()->agency_id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce véhicule.');
        }

        return view('agence.cars.external-sites-discover', compact('car'));
    }
}
