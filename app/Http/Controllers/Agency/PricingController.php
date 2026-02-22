<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Rental;
use App\Models\PricingRule;
use App\Models\SeasonalRule;
use App\Models\SpecialOffer;
use App\Models\DynamicPricingConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PricingController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;
        
        // Current pricing overview
        $pricingOverview = [
            'average_daily_rate' => Car::where('cars.agency_id', $agency->id)->avg('cars.price_per_day'),
            'price_range' => [
                'min' => Car::where('cars.agency_id', $agency->id)->min('cars.price_per_day'),
                'max' => Car::where('cars.agency_id', $agency->id)->max('cars.price_per_day'),
            ],
            'total_cars' => Car::where('cars.agency_id', $agency->id)->count(),
        ];
        
        // Cars with current pricing
        $cars = Car::where('cars.agency_id', $agency->id)
            ->with(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }])
            ->get();
        
        return view('agence.pricing.index', compact('pricingOverview', 'cars'));
    }
    
    
    
    
    public function updatePricing(Request $request)
    {
        try {
            \Log::info('Pricing update request received', $request->all());
            
            $request->validate([
                'car_id' => 'required|exists:cars,id',
                'price_per_day' => 'required|numeric|min:0',
                'seasonal_multiplier' => 'nullable|numeric|min:0.1|max:3',
                'reason' => 'required|string|max:255',
            ]);
            
            $agency = auth()->user()->agency;
            
            $car = Car::where('cars.id', $request->car_id)
                ->where('cars.agency_id', $agency->id)
                ->firstOrFail();
            
            $oldPrice = $car->price_per_day;
            $newPrice = $request->price_per_day;
            
            // Enregistrer l'historique du changement de prix
            PricingRule::create([
                'agency_id' => $agency->id,
                'car_id' => $car->id,
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'reason' => $request->reason,
                'seasonal_multiplier' => $request->seasonal_multiplier ?? 1.0,
                'user_id' => auth()->id(),
            ]);
            
            // Mettre à jour le prix du véhicule
            $car->update([
                'price_per_day' => $newPrice,
            ]);
            
            \Log::info('Pricing updated successfully', ['car_id' => $car->id, 'old_price' => $oldPrice, 'new_price' => $newPrice]);
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Prix mis à jour avec succès']);
            }
            
            return redirect()->back()->with('success', 'Prix mis à jour avec succès');
        } catch (\Exception $e) {
            \Log::error('Pricing update error', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour du prix'], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du prix');
        }
    }
    
    private function getPeakHours($agency)
    {
        // Analyze booking patterns to identify peak hours
        return Rental::where('rentals.agency_id', $agency->id)
            ->select(
                DB::raw('HOUR(rentals.created_at) as hour'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->groupBy('hour')
            ->orderBy('booking_count', 'desc')
            ->take(5)
            ->get();
    }
    
    private function getSeasonalTrends($agency)
    {
        // Analyze seasonal booking patterns
        return Rental::where('rentals.agency_id', $agency->id)
            ->select(
                DB::raw('MONTH(rentals.created_at) as month'),
                DB::raw('COUNT(*) as booking_count'),
                DB::raw('AVG(rentals.total_price) as avg_price')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
    }
    
    private function getCompetitorAnalysis($agency)
    {
        // Placeholder for competitor analysis
        return [
            'market_average' => 0,
            'our_average' => Car::where('cars.agency_id', $agency->id)->avg('cars.price_per_day'),
            'price_position' => 'competitive',
        ];
    }
    
    private function getDemandForecast($agency)
    {
        // Placeholder for demand forecasting
        return [
            'next_week' => 'high',
            'next_month' => 'medium',
            'recommendation' => 'increase_prices',
        ];
    }
    
    private function getCurrentSeason()
    {
        $month = Carbon::now()->month;
        
        if (in_array($month, [12, 1, 2])) {
            return 'winter';
        } elseif (in_array($month, [3, 4, 5])) {
            return 'spring';
        } elseif (in_array($month, [6, 7, 8])) {
            return 'summer';
        } else {
            return 'autumn';
        }
    }
    
    private function getSeasonalMultipliers()
    {
        return [
            'winter' => 0.9,
            'spring' => 1.0,
            'summer' => 1.2,
            'autumn' => 1.1,
        ];
    }
    
    private function getHolidayPricing()
    {
        return [
            'new_year' => 1.5,
            'easter' => 1.3,
            'summer_holidays' => 1.4,
            'christmas' => 1.6,
        ];
    }
    
    private function getActiveOffers($agency)
    {
        // Placeholder for active offers
        return collect();
    }
    
    private function getOfferPerformance($agency)
    {
        // Placeholder for offer performance analysis
        return [
            'total_offers' => 0,
            'successful_offers' => 0,
            'conversion_rate' => 0,
        ];
    }
    
    private function getSuggestedOffers($agency)
    {
        // Placeholder for AI-suggested offers
        return [
            'last_minute_discount' => '10% off bookings made within 24 hours',
            'weekend_special' => '15% off weekend rentals',
            'loyalty_discount' => '5% off for repeat customers',
        ];
    }
    
    public function competitorAnalysis()
    {
        $agency = auth()->user()->agency;
        
        // Get competitor analysis data
        $analysis = [
            'our_average' => Car::where('cars.agency_id', $agency->id)->avg('cars.price_per_day'),
            'market_average' => 350, // Placeholder - would come from external API
            'competitors' => [
                ['name' => 'AutoRent Maroc', 'average_price' => 320, 'market_share' => 25],
                ['name' => 'CarRental Pro', 'average_price' => 380, 'market_share' => 20],
                ['name' => 'DriveEasy', 'average_price' => 290, 'market_share' => 15],
                ['name' => 'QuickRent', 'average_price' => 340, 'market_share' => 18],
            ],
            'recommendations' => [
                'increase_5_percent' => 'Augmenter de 5% pour être plus compétitif',
                'decrease_10_percent' => 'Réduire de 10% pour attirer plus de clients',
                'maintain_current' => 'Maintenir les prix actuels - position optimale',
            ]
        ];
        
        return view('agence.pricing.competitor-analysis', compact('analysis'));
    }
    
    public function dynamic()
    {
        $agency = auth()->user()->agency;
        
        // Get dynamic pricing configurations
        $dynamicConfigs = DynamicPricingConfig::where('agency_id', $agency->id)->get();
        
        // Get cars for the agency
        $cars = Car::where('agency_id', $agency->id)->get();
        
        // Calculate pricing overview
        $pricingOverview = [
            'average_daily_rate' => $cars->avg('price_per_day') ?? 0,
            'total_vehicles' => $cars->count(),
            'active_rules' => $dynamicConfigs->where('enabled', true)->count(),
        ];
        
        return view('agence.pricing.dynamic', compact('dynamicConfigs', 'cars', 'pricingOverview'));
    }
    
    public function seasonal()
    {
        $agency = auth()->user()->agency;
        
        // Get seasonal pricing rules
        $seasonalRules = SeasonalRule::where('agency_id', $agency->id)->get();
        
        // Get cars for the agency
        $cars = Car::where('agency_id', $agency->id)->get();
        
        return view('agence.pricing.seasonal', compact('seasonalRules', 'cars'));
    }
    
    public function offers()
    {
        $agency = auth()->user()->agency;
        
        // Get special offers
        $offers = SpecialOffer::where('agency_id', $agency->id)->get();
        
        // Get cars for the agency
        $cars = Car::where('agency_id', $agency->id)->get();
        
        return view('agence.pricing.offers', compact('offers', 'cars'));
    }
    
    public function carPricingHistory($id)
    {
        $agency = auth()->user()->agency;
        
        $car = Car::where('cars.id', $id)
            ->where('cars.agency_id', $agency->id)
            ->firstOrFail();
        
        // Get real pricing history
        $history = PricingRule::where('car_id', $id)
            ->where('agency_id', $agency->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('agence.pricing.car-history', compact('car', 'history'));
    }
    
    public function editCarPricing($id)
    {
        $agency = auth()->user()->agency;
        
        $car = Car::where('cars.id', $id)
            ->where('cars.agency_id', $agency->id)
            ->firstOrFail();
        
        return view('agence.pricing.car-edit', compact('car'));
    }
    
    public function configureDynamicPricing(Request $request)
    {
        try {
            \Log::info('Dynamic pricing configuration request received', $request->all());
            
            $request->validate([
                'enabled' => 'nullable|in:on,1,true',
                'peak_hour_multiplier' => 'required|numeric|min:1|max:3',
                'weekend_multiplier' => 'required|numeric|min:1|max:3',
                'last_minute_multiplier' => 'required|numeric|min:0.5|max:2',
                'demand_threshold' => 'required|numeric|min:0|max:100',
            ]);
            
            $agency = auth()->user()->agency;
            
            // Create or update dynamic pricing configuration
            DynamicPricingConfig::updateOrCreate(
                ['agency_id' => $agency->id],
                [
                    'enabled' => $request->has('enabled') ? true : false,
                    'peak_hour_multiplier' => $request->peak_hour_multiplier,
                    'weekend_multiplier' => $request->weekend_multiplier,
                    'last_minute_multiplier' => $request->last_minute_multiplier,
                    'demand_threshold' => $request->demand_threshold,
                    'peak_hours' => $request->peak_hours ?? [],
                ]
            );
            
            \Log::info('Dynamic pricing configuration updated successfully');
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Configuration de tarification dynamique mise à jour']);
            }
            
            return redirect()->back()->with('success', 'Configuration de tarification dynamique mise à jour');
        } catch (\Exception $e) {
            \Log::error('Dynamic pricing configuration error', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erreur lors de la configuration'], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la configuration');
        }
    }
    
    public function createSeasonalRule(Request $request)
    {
        try {
            \Log::info('Seasonal rule creation request received', $request->all());
            
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'price_multiplier' => 'required|numeric|min:0.1|max:3',
                'vehicle_ids' => 'required|array',
                'vehicle_ids.*' => 'exists:cars,id',
            ]);
            
            $agency = auth()->user()->agency;
            
            // Verify all vehicles belong to the agency
            $validVehicleIds = Car::where('agency_id', $agency->id)
                ->whereIn('id', $request->vehicle_ids)
                ->pluck('id')
                ->toArray();
            
            if (count($validVehicleIds) !== count($request->vehicle_ids)) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Certains véhicules sélectionnés ne vous appartiennent pas'], 400);
                }
                return redirect()->back()->with('error', 'Certains véhicules sélectionnés ne vous appartiennent pas');
            }
            
            // Create seasonal rule
            SeasonalRule::create([
                'agency_id' => $agency->id,
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_multiplier' => $request->price_multiplier,
                'vehicle_ids' => $validVehicleIds,
                'is_active' => true,
            ]);
            
            \Log::info('Seasonal rule created successfully');
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Règle saisonnière créée avec succès']);
            }
            
            return redirect()->back()->with('success', 'Règle saisonnière créée avec succès');
        } catch (\Exception $e) {
            \Log::error('Seasonal rule creation error', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erreur lors de la création de la règle: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la création de la règle');
        }
    }
    
    public function createOffer(Request $request)
    {
        try {
            \Log::info('Special offer creation request received', $request->all());
            
            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:special_offers,code',
                'type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'max_usage' => 'required|integer|min:0',
                'vehicle_ids' => 'required|array',
                'vehicle_ids.*' => 'exists:cars,id',
                'is_flash' => 'nullable|boolean',
                'is_promo_code' => 'nullable|boolean',
            ]);
            
            $agency = auth()->user()->agency;
            
            // Verify all vehicles belong to the agency
            $validVehicleIds = Car::where('agency_id', $agency->id)
                ->whereIn('id', $request->vehicle_ids)
                ->pluck('id')
                ->toArray();
            
            if (count($validVehicleIds) !== count($request->vehicle_ids)) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Certains véhicules sélectionnés ne vous appartiennent pas'], 400);
                }
                return redirect()->back()->with('error', 'Certains véhicules sélectionnés ne vous appartiennent pas');
            }
            
            // Determine offer type based on request
            $offerType = 'regular';
            if ($request->has('is_flash')) {
                $offerType = 'flash';
            } elseif ($request->has('is_promo_code')) {
                $offerType = 'promo_code';
            }
            
            // Create special offer
            SpecialOffer::create([
                'agency_id' => $agency->id,
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'type' => $request->type,
                'discount_value' => $request->discount_value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'max_usage' => $request->max_usage,
                'vehicle_ids' => $validVehicleIds,
                'is_active' => true,
                'offer_type' => $offerType, // Add this field to track offer type
            ]);
            
            \Log::info('Special offer created successfully');
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Offre créée avec succès']);
            }
            
            return redirect()->back()->with('success', 'Offre créée avec succès');
        } catch (\Exception $e) {
            \Log::error('Special offer creation error', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erreur lors de la création de l\'offre: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la création de l\'offre');
        }
    }
    
    public function editSeasonalRule($id)
    {
        $agency = auth()->user()->agency;
        
        $rule = SeasonalRule::where('id', $id)
            ->where('agency_id', $agency->id)
            ->firstOrFail();
            
        $cars = Car::where('agency_id', $agency->id)->get();
        
        return view('agence.pricing.seasonal-edit', compact('rule', 'cars'));
    }
    
    public function updateSeasonalRule(Request $request, $id)
    {
        try {
            \Log::info('Seasonal rule update request received', $request->all());
            
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'price_multiplier' => 'required|numeric|min:0.1|max:3',
                'vehicle_ids' => 'required|array',
                'vehicle_ids.*' => 'exists:cars,id',
            ]);
            
            $agency = auth()->user()->agency;
            
            $rule = SeasonalRule::where('id', $id)
                ->where('agency_id', $agency->id)
                ->firstOrFail();
            
            // Verify all vehicles belong to the agency
            $validVehicleIds = Car::where('agency_id', $agency->id)
                ->whereIn('id', $request->vehicle_ids)
                ->pluck('id')
                ->toArray();
            
            if (count($validVehicleIds) !== count($request->vehicle_ids)) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Certains véhicules sélectionnés ne vous appartiennent pas'], 400);
                }
                return redirect()->back()->with('error', 'Certains véhicules sélectionnés ne vous appartiennent pas');
            }
            
            $rule->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_multiplier' => $request->price_multiplier,
                'vehicle_ids' => $validVehicleIds,
            ]);
            
            \Log::info('Seasonal rule updated successfully');
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Règle saisonnière mise à jour avec succès']);
            }
            
            return redirect()->route('agence.pricing.seasonal')->with('success', 'Règle saisonnière mise à jour avec succès');
        } catch (\Exception $e) {
            \Log::error('Seasonal rule update error', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour de la règle: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la règle');
        }
    }
    
    public function toggleSeasonalRule($id)
    {
        try {
            $agency = auth()->user()->agency;
            
            $rule = SeasonalRule::where('id', $id)
                ->where('agency_id', $agency->id)
                ->firstOrFail();
            
            $rule->update([
                'is_active' => !$rule->is_active
            ]);
            
            \Log::info('Seasonal rule toggled', ['rule_id' => $id, 'new_status' => $rule->is_active]);
            
            return response()->json([
                'success' => true, 
                'message' => $rule->is_active ? 'Règle activée avec succès' : 'Règle désactivée avec succès',
                'is_active' => $rule->is_active
            ]);
        } catch (\Exception $e) {
            \Log::error('Seasonal rule toggle error', ['error' => $e->getMessage()]);
            
            return response()->json(['success' => false, 'message' => 'Erreur lors du changement de statut'], 500);
        }
    }
    
    public function deleteSeasonalRule($id)
    {
        try {
            $agency = auth()->user()->agency;
            
            $rule = SeasonalRule::where('id', $id)
                ->where('agency_id', $agency->id)
                ->firstOrFail();
            
            $rule->delete();
            
            \Log::info('Seasonal rule deleted', ['rule_id' => $id]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Règle supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            \Log::error('Seasonal rule deletion error', ['error' => $e->getMessage()]);
            
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }
    
    public function editOffer($id)
    {
        $agency = auth()->user()->agency;
        
        $offer = SpecialOffer::where('id', $id)
            ->where('agency_id', $agency->id)
            ->firstOrFail();
            
        $cars = Car::where('agency_id', $agency->id)->get();
        
        return view('agence.pricing.offer-edit', compact('offer', 'cars'));
    }
    
    public function updateOffer(Request $request, $id)
    {
        try {
            \Log::info('Special offer update request received', $request->all());
            
            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:special_offers,code,' . $id,
                'type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'max_usage' => 'required|integer|min:0',
                'vehicle_ids' => 'required|array',
                'vehicle_ids.*' => 'exists:cars,id',
            ]);
            
            $agency = auth()->user()->agency;
            
            $offer = SpecialOffer::where('id', $id)
                ->where('agency_id', $agency->id)
                ->firstOrFail();
            
            // Verify all vehicles belong to the agency
            $validVehicleIds = Car::where('agency_id', $agency->id)
                ->whereIn('id', $request->vehicle_ids)
                ->pluck('id')
                ->toArray();
            
            if (count($validVehicleIds) !== count($request->vehicle_ids)) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Certains véhicules sélectionnés ne vous appartiennent pas'], 400);
                }
                return redirect()->back()->with('error', 'Certains véhicules sélectionnés ne vous appartiennent pas');
            }
            
            $offer->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'type' => $request->type,
                'discount_value' => $request->discount_value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'max_usage' => $request->max_usage,
                'vehicle_ids' => $validVehicleIds,
            ]);
            
            \Log::info('Special offer updated successfully');
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Offre mise à jour avec succès']);
            }
            
            return redirect()->route('agence.pricing.offers')->with('success', 'Offre mise à jour avec succès');
        } catch (\Exception $e) {
            \Log::error('Special offer update error', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'offre: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de l\'offre');
        }
    }
    
    public function deleteOffer($id)
    {
        try {
            $agency = auth()->user()->agency;
            
            $offer = SpecialOffer::where('id', $id)
                ->where('agency_id', $agency->id)
                ->firstOrFail();
            
            $offer->delete();
            
            \Log::info('Special offer deleted', ['offer_id' => $id]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Offre supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            \Log::error('Special offer deletion error', ['error' => $e->getMessage()]);
            
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }
}
