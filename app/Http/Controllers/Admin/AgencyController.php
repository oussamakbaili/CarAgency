<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Agency::with(['user', 'cars', 'rentals']);

        // Advanced filtering
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('city') && $request->city !== '') {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('agency_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('responsable_name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $agencies = $query->latest()->paginate(15);

        // Get comprehensive statistics
        $statistics = [
            'total' => Agency::count(),
            'pending' => Agency::where('status', 'pending')->count(),
            'approved' => Agency::where('status', 'approved')->count(),
            'rejected' => Agency::where('status', 'rejected')->count(),
            'totalCars' => Car::count(),
            'totalRentals' => Rental::count(),
            'monthlyRevenue' => Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
        ];

        return view('admin.agencies.index', compact('agencies', 'statistics'));
    }

    public function pending(Request $request)
    {
        $query = Agency::with('user')->where('status', 'pending');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('agency_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('responsable_name', 'like', '%' . $search . '%');
            });
        }

        $agencies = $query->latest()->paginate(10);

        return view('admin.agencies.pending', compact('agencies'));
    }

    public function documents(Request $request)
    {
        $query = Agency::with('user')->where('status', 'pending');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('agency_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $agencies = $query->latest()->paginate(10);

        return view('admin.agencies.documents', compact('agencies'));
    }

    public function show(Agency $agency)
    {
        $agency->load(['user', 'cars', 'rentals', 'transactions']);
        
        // Get agency performance metrics
        $performance = [
            'totalCars' => $agency->cars()->count(),
            'activeCars' => $agency->cars()->where('status', 'available')->count(),
            'totalRentals' => $agency->rentals()->count(),
            'activeRentals' => $agency->rentals()->where('status', 'active')->count(),
            'completedRentals' => $agency->rentals()->where('status', 'completed')->count(),
            'totalRevenue' => $agency->rentals()->whereIn('status', ['active', 'completed'])->sum('total_price'),
            'monthlyRevenue' => $agency->rentals()
                ->whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price'),
            'averageRating' => 4.5, // This would come from a reviews table
        ];

        // Get recent activities
        $recentActivities = Activity::where('agency_id', $agency->id)
            ->latest()
            ->take(10)
            ->get();

        // Get recent transactions
        $recentTransactions = $agency->transactions()
            ->latest()
            ->take(10)
            ->get();

        return view('admin.agencies.show', compact('agency', 'performance', 'recentActivities', 'recentTransactions'));
    }

    public function approve(Request $request, Agency $agency)
    {
        \Log::info('Approve method called', [
            'agency_id' => $agency->id,
            'request_data' => $request->all()
        ]);
        
        $request->validate([
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $agency->update([
            'status' => 'approved',
            'commission_rate' => $request->commission_rate ?? 10, // Default 10%
        ]);

        // Create activity log
        Activity::create([
            'agency_id' => $agency->id,
            'type' => 'approval',
            'description' => 'Agency registration was approved',
            'data' => json_encode([
                'commission_rate' => $agency->commission_rate,
                'approved_by' => auth()->user()->name
            ])
        ]);

        // Send approval email
        try {
            Mail::to($agency->email)->send(new \App\Mail\AgencyApproved($agency));
        } catch (\Exception $e) {
            // Log email error but don't fail the approval
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        // If the request expects JSON (AJAX), return the updated row data
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $agency->status,
                'badge_class' => 'bg-green-100 text-green-800',
                'status_label' => 'Approved'
            ]);
        }

        return back()->with('success', 'L\'agence a été approuvée avec succès.');
    }

    public function reject(Request $request, Agency $agency)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        // Increment tries count
        $agency->increment('tries_count');

        $agency->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Create activity log
        Activity::create([
            'agency_id' => $agency->id,
            'type' => 'rejection',
            'description' => 'Agency registration was rejected',
            'data' => json_encode([
                'reason' => $request->rejection_reason,
                'tries_count' => $agency->tries_count,
                'rejected_by' => auth()->user()->name
            ])
        ]);

        // Check if agency has reached maximum tries
        if ($agency->tries_count >= Agency::MAX_TRIES) {
            // Send permanent rejection email
            try {
                Mail::to($agency->email)->send(new \App\Mail\AgencyPermanentlyRejected($agency));
            } catch (\Exception $e) {
                \Log::error('Failed to send permanent rejection email: ' . $e->getMessage());
            }
            
            // Delete the agency and its user
            $user = $agency->user;
            $agency->delete();
            $user->delete();

            return redirect()->route('admin.agencies.index')
                ->with('success', 'L\'agence a été définitivement rejetée et supprimée après avoir dépassé le nombre maximum de tentatives.');
        }

        // Send regular rejection email
        try {
            Mail::to($agency->email)->send(new \App\Mail\AgencyRejected($agency));
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $agency->status,
                'badge_class' => 'bg-red-100 text-red-800',
                'status_label' => 'Rejected'
            ]);
        }

        return back()->with('success', 'L\'agence a été rejetée avec succès.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'agency_ids' => 'required|array',
            'agency_ids.*' => 'exists:agencies,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $agencies = Agency::whereIn('id', $request->agency_ids)
            ->where('status', 'pending')
            ->get();

        foreach ($agencies as $agency) {
            $agency->update([
                'status' => 'approved',
                'commission_rate' => $request->commission_rate ?? 10,
            ]);

            Activity::create([
                'agency_id' => $agency->id,
                'type' => 'bulk_approval',
                'description' => 'Agency registration was approved via bulk action',
                'data' => json_encode([
                    'commission_rate' => $agency->commission_rate,
                    'approved_by' => auth()->user()->name
                ])
            ]);
        }

        return redirect()->route('admin.agencies.pending')
            ->with('success', count($agencies) . ' agences ont été approuvées avec succès.');
    }

    public function bulkReject(Request $request)
    {
        $request->validate([
            'agency_ids' => 'required|array',
            'agency_ids.*' => 'exists:agencies,id',
            'rejection_reason' => 'required|string|min:10',
        ]);

        $agencies = Agency::whereIn('id', $request->agency_ids)
            ->where('status', 'pending')
            ->get();

        foreach ($agencies as $agency) {
            $agency->increment('tries_count');
            $agency->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            Activity::create([
                'agency_id' => $agency->id,
                'type' => 'bulk_rejection',
                'description' => 'Agency registration was rejected via bulk action',
                'data' => json_encode([
                    'reason' => $request->rejection_reason,
                    'tries_count' => $agency->tries_count,
                    'rejected_by' => auth()->user()->name
                ])
            ]);
        }

        return redirect()->route('admin.agencies.pending')
            ->with('success', count($agencies) . ' agences ont été rejetées avec succès.');
    }

    public function performance(Agency $agency)
    {
        $agency->load(['cars', 'rentals', 'transactions']);

        // Get performance data for charts
        $monthlyRevenue = [];
        $monthlyBookings = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $monthlyRevenue[] = $agency->rentals()
                ->whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');
            
            $monthlyBookings[] = $agency->rentals()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        // Get car utilization data
        $carUtilization = $agency->cars()->withCount(['rentals' => function($query) {
            $query->whereIn('status', ['active', 'completed']);
        }])->get();

        return view('admin.agencies.performance', compact('agency', 'monthlyRevenue', 'monthlyBookings', 'labels', 'carUtilization'));
    }

    public function updateCommission(Request $request, Agency $agency)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $oldRate = $agency->commission_rate;
        $agency->update(['commission_rate' => $request->commission_rate]);

        Activity::create([
            'agency_id' => $agency->id,
            'type' => 'commission_update',
            'description' => 'Commission rate was updated',
            'data' => json_encode([
                'old_rate' => $oldRate,
                'new_rate' => $request->commission_rate,
                'updated_by' => auth()->user()->name
            ])
        ]);

        return redirect()->route('admin.agencies.show', $agency)
            ->with('success', 'Le taux de commission a été mis à jour avec succès.');
    }
} 