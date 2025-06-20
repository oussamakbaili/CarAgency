<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Activity;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Agency::with('user');

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $agencies = $query->latest()->paginate(10);

        // Get statistics
        $statistics = [
            'total' => Agency::count(),
            'pending' => Agency::where('status', 'pending')->count(),
            'approved' => Agency::where('status', 'approved')->count(),
            'rejected' => Agency::where('status', 'rejected')->count(),
        ];

        return view('admin.agencies.index', compact('agencies', 'statistics'));
    }

    public function show(Agency $agency)
    {
        return view('admin.agencies.show', compact('agency'));
    }

    public function approve(Agency $agency)
    {
        $agency->update(['status' => 'approved']);

        // Send approval email
        Mail::to($agency->email)->send(new \App\Mail\AgencyApproved($agency));

        return redirect()->route('admin.agencies.show', $agency)
            ->with('success', 'L\'agence a été approuvée avec succès.');
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
                'tries_count' => $agency->tries_count
            ])
        ]);

        // Check if agency has reached maximum tries
        if ($agency->tries_count >= Agency::MAX_TRIES) {
            // Send permanent rejection email
            Mail::to($agency->email)->send(new \App\Mail\AgencyPermanentlyRejected($agency));
            
            // Delete the agency and its user
            $user = $agency->user;
            $agency->delete();
            $user->delete();

            return redirect()->route('admin.agencies.index')
                ->with('success', 'L\'agence a été définitivement rejetée et supprimée après avoir dépassé le nombre maximum de tentatives.');
        }

        // Send regular rejection email
        Mail::to($agency->email)->send(new \App\Mail\AgencyRejected($agency));

        return redirect()->route('admin.agencies.index')
            ->with('success', 'L\'agence a été rejetée avec succès.');
    }
} 