<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AgencyManagementController extends Controller
{
    public function index()
    {
        $agencies = Agency::with(['user', 'cars'])
            ->where('status', Agency::STATUS_APPROVED)
            ->latest()
            ->paginate(10);

        $statistics = [
            'total_agencies' => Agency::where('status', Agency::STATUS_APPROVED)->count(),
            'total_cars' => DB::table('cars')->whereIn('agency_id', function($query) {
                $query->select('id')->from('agencies')->where('status', Agency::STATUS_APPROVED);
            })->count(),
            'agencies_this_month' => Agency::where('status', Agency::STATUS_APPROVED)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view('admin.agencies.index', compact('agencies', 'statistics'));
    }

    public function requests()
    {
        $pendingAgencies = Agency::with('user')
            ->where('status', Agency::STATUS_PENDING)
            ->latest()
            ->paginate(10);

        return view('admin.agencies.requests', compact('pendingAgencies'));
    }

    public function create()
    {
        return view('admin.agencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'agency_name' => ['required', 'string', 'max:255'],
            'responsable_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->agency_name,
            'email' => $request->email,
                'password' => Hash::make($request->password),
            'role' => 'agence',
        ]);

            Agency::create([
            'user_id' => $user->id,
            'agency_name' => $request->agency_name,
                'responsable_name' => $request->responsable_name,
            'phone' => $request->phone,
            'email' => $request->email,
                'status' => Agency::STATUS_APPROVED,
        ]);
        });

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agence créée avec succès');
    }

    public function edit(Agency $agency)
    {
        return view('admin.agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency)
    {
        $request->validate([
            'agency_name' => ['required', 'string', 'max:255'],
            'responsable_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $agency->user_id],
        ]);

        DB::transaction(function () use ($request, $agency) {
            $agency->user->update([
                'name' => $request->agency_name,
                'email' => $request->email,
            ]);

            $agency->update([
                'agency_name' => $request->agency_name,
                'responsable_name' => $request->responsable_name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);
        });

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agence mise à jour avec succès');
    }

    public function approve(Agency $agency)
    {
        $agency->update(['status' => Agency::STATUS_APPROVED]);
        
        // You could send an email notification here
        
        return redirect()->route('admin.agency.requests')
            ->with('success', 'Agence approuvée avec succès');
    }

    public function reject(Agency $agency)
    {
        DB::transaction(function () use ($agency) {
            $userId = $agency->user_id;
            $agency->delete();
            User::destroy($userId);
        });

        return redirect()->route('admin.agency.requests')
            ->with('success', 'Demande d\'agence rejetée');
    }

    public function destroy(Agency $agency)
    {
        DB::transaction(function () use ($agency) {
            // Delete all cars belonging to this agency
            $agency->cars()->delete();
            
            // Delete the agency and its user
            $userId = $agency->user_id;
            $agency->delete();
            User::destroy($userId);
        });

        return redirect()->route('admin.agencies.index')
            ->with('success', 'Agence supprimée avec succès');
    }

    public function statistics()
    {
        $statistics = [
            'total_agencies' => Agency::where('status', Agency::STATUS_APPROVED)->count(),
            'pending_agencies' => Agency::where('status', Agency::STATUS_PENDING)->count(),
            'total_cars' => DB::table('cars')->whereIn('agency_id', function($query) {
                $query->select('id')->from('agencies')->where('status', Agency::STATUS_APPROVED);
            })->count(),
            'agencies_by_month' => Agency::where('status', Agency::STATUS_APPROVED)
                ->select(DB::raw('COUNT(*) as count'), DB::raw('MONTH(created_at) as month'))
                ->groupBy('month')
                ->get(),
            'cars_by_agency' => Agency::where('status', Agency::STATUS_APPROVED)
                ->withCount('cars')
                ->get(),
            'recent_activities' => Agency::with('user')
                ->where('status', '!=', Agency::STATUS_PENDING)
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.agencies.statistics', compact('statistics'));
    }
}
