<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Agency;
use Illuminate\Http\Request;

class FeaturedContentController extends Controller
{
    /**
     * Display homepage content management dashboard
     */
    public function index()
    {
        // Get featured and homepage cars
        $featuredCars = Car::featured()
            ->with(['agency', 'category'])
            ->orderByPriority()
            ->get();

        $homepageCars = Car::showOnHomepage()
            ->whereHas('agency', function($query) {
                $query->where('status', 'approved');
            })
            ->where('status', 'available')
            ->with(['agency', 'category'])
            ->orderByPriority()
            ->paginate(20);

        // Get featured and homepage agencies
        $featuredAgencies = Agency::featured()
            ->where('status', 'approved')
            ->orderByPriority()
            ->get();

        $homepageAgencies = Agency::showOnHomepage()
            ->where('status', 'approved')
            ->orderByPriority()
            ->paginate(20);

        // Get all available cars for selection
        $availableCars = Car::whereHas('agency', function($query) {
                $query->where('status', 'approved');
            })
            ->where('status', 'available')
            ->with(['agency', 'category'])
            ->orderBy('brand')
            ->orderBy('model')
            ->get();

        // Get all approved agencies for selection
        $availableAgencies = Agency::where('status', 'approved')
            ->orderBy('agency_name')
            ->get();

        return view('admin.featured.index', compact(
            'featuredCars',
            'homepageCars',
            'featuredAgencies',
            'homepageAgencies',
            'availableCars',
            'availableAgencies'
        ));
    }

    /**
     * Toggle featured status for a car
     */
    public function toggleCarFeatured(Car $car)
    {
        $car->featured = !$car->featured;
        $car->featured_at = $car->featured ? now() : null;
        $car->save();

        return response()->json([
            'success' => true,
            'featured' => $car->featured,
            'message' => $car->featured 
                ? 'Car marked as featured' 
                : 'Car removed from featured'
        ]);
    }

    /**
     * Toggle homepage visibility for a car
     */
    public function toggleCarHomepage(Car $car)
    {
        $car->show_on_homepage = !$car->show_on_homepage;
        $car->save();

        return response()->json([
            'success' => true,
            'show_on_homepage' => $car->show_on_homepage,
            'message' => $car->show_on_homepage 
                ? 'Car will be shown on homepage' 
                : 'Car hidden from homepage'
        ]);
    }

    /**
     * Update car homepage priority
     */
    public function updateCarPriority(Request $request, Car $car)
    {
        $request->validate([
            'priority' => 'required|integer|min:0|max:100'
        ]);

        $car->homepage_priority = $request->priority;
        $car->save();

        return response()->json([
            'success' => true,
            'priority' => $car->homepage_priority,
            'message' => 'Priority updated successfully'
        ]);
    }

    /**
     * Toggle featured status for an agency
     */
    public function toggleAgencyFeatured(Agency $agency)
    {
        $agency->featured = !$agency->featured;
        $agency->featured_at = $agency->featured ? now() : null;
        $agency->save();

        return response()->json([
            'success' => true,
            'featured' => $agency->featured,
            'message' => $agency->featured 
                ? 'Agency marked as featured' 
                : 'Agency removed from featured'
        ]);
    }

    /**
     * Toggle homepage visibility for an agency
     */
    public function toggleAgencyHomepage(Agency $agency)
    {
        $agency->show_on_homepage = !$agency->show_on_homepage;
        $agency->save();

        return response()->json([
            'success' => true,
            'show_on_homepage' => $agency->show_on_homepage,
            'message' => $agency->show_on_homepage 
                ? 'Agency will be shown on homepage' 
                : 'Agency hidden from homepage'
        ]);
    }

    /**
     * Update agency homepage priority
     */
    public function updateAgencyPriority(Request $request, Agency $agency)
    {
        $request->validate([
            'priority' => 'required|integer|min:0|max:100'
        ]);

        $agency->homepage_priority = $request->priority;
        $agency->save();

        return response()->json([
            'success' => true,
            'priority' => $agency->homepage_priority,
            'message' => 'Priority updated successfully'
        ]);
    }

    /**
     * Bulk update cars for homepage
     */
    public function bulkUpdateCars(Request $request)
    {
        $request->validate([
            'car_ids' => 'required|array',
            'car_ids.*' => 'exists:cars,id',
            'action' => 'required|in:feature,unfeature,show,hide'
        ]);

        $cars = Car::whereIn('id', $request->car_ids);

        switch ($request->action) {
            case 'feature':
                $cars->update(['featured' => true, 'featured_at' => now()]);
                $message = 'Cars marked as featured';
                break;
            case 'unfeature':
                $cars->update(['featured' => false, 'featured_at' => null]);
                $message = 'Cars removed from featured';
                break;
            case 'show':
                $cars->update(['show_on_homepage' => true]);
                $message = 'Cars will be shown on homepage';
                break;
            case 'hide':
                $cars->update(['show_on_homepage' => false]);
                $message = 'Cars hidden from homepage';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Bulk update agencies for homepage
     */
    public function bulkUpdateAgencies(Request $request)
    {
        $request->validate([
            'agency_ids' => 'required|array',
            'agency_ids.*' => 'exists:agencies,id',
            'action' => 'required|in:feature,unfeature,show,hide'
        ]);

        $agencies = Agency::whereIn('id', $request->agency_ids);

        switch ($request->action) {
            case 'feature':
                $agencies->update(['featured' => true, 'featured_at' => now()]);
                $message = 'Agencies marked as featured';
                break;
            case 'unfeature':
                $agencies->update(['featured' => false, 'featured_at' => null]);
                $message = 'Agencies removed from featured';
                break;
            case 'show':
                $agencies->update(['show_on_homepage' => true]);
                $message = 'Agencies will be shown on homepage';
                break;
            case 'hide':
                $agencies->update(['show_on_homepage' => false]);
                $message = 'Agencies hidden from homepage';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}

