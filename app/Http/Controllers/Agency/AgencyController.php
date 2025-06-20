<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AgencyController extends Controller
{
    public function showRejected()
    {
        $agency = auth()->user()->agency;
        
        if ($agency->status === 'approved') {
            return redirect()->route('agence.dashboard');
        }

        if ($agency->status === 'pending') {
            return redirect()->route('agence.pending');
        }

        if ($agency->status !== 'rejected') {
            abort(403, 'Unauthorized action.');
        }

        $maxTries = Agency::MAX_TRIES;
        return view('agence.rejected', compact('agency', 'maxTries'));
    }

    public function showPending()
    {
        $agency = auth()->user()->agency;
        
        if ($agency->status === 'approved') {
            return redirect()->route('agence.dashboard');
        }

        if ($agency->status === 'rejected') {
            return redirect()->route('agence.rejected');
        }

        return view('agence.pending', compact('agency'));
    }

    public function update(Request $request)
    {
        $agency = auth()->user()->agency;

        // Check if agency has exceeded maximum tries
        if ($agency->tries_count >= Agency::MAX_TRIES) {
            // Delete the agency and its user
            $user = $agency->user;
            
            // Send permanent rejection email
            Mail::to($agency->email)->send(new \App\Mail\AgencyPermanentlyRejected($agency));
            
            // Delete the agency and user
            $agency->delete();
            $user->delete();

            // Redirect to login with message
            return redirect()->route('login')
                ->with('error', 'Votre compte a été définitivement rejeté après avoir dépassé le nombre maximum de tentatives.');
        }

        $request->validate([
            'agency_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'commercial_register_number' => 'required|string|max:255',
            'responsable_name' => 'required|string|max:255',
            'responsable_phone' => 'required|string|max:255',
            'responsable_position' => 'required|string|max:255',
            'responsable_identity_number' => 'required|string|max:255',
            'commercial_register_doc' => 'nullable|mimes:pdf|max:2048',
            'identity_doc' => 'nullable|mimes:pdf|max:2048',
            'tax_doc' => 'nullable|mimes:pdf|max:2048',
        ]);

        $data = $request->only([
            'agency_name',
            'email',
            'phone',
            'address',
            'city',
            'commercial_register_number',
            'responsable_name',
            'responsable_phone',
            'responsable_position',
            'responsable_identity_number',
        ]);

        // Handle document uploads
        if ($request->hasFile('commercial_register_doc')) {
            Storage::disk('public')->delete($agency->commercial_register_doc);
            $data['commercial_register_doc'] = $request->file('commercial_register_doc')->store('documents', 'public');
        }

        if ($request->hasFile('identity_doc')) {
            Storage::disk('public')->delete($agency->identity_doc);
            $data['identity_doc'] = $request->file('identity_doc')->store('documents', 'public');
        }

        if ($request->hasFile('tax_doc')) {
            Storage::disk('public')->delete($agency->tax_doc);
            $data['tax_doc'] = $request->file('tax_doc')->store('documents', 'public');
        }

        // Reset status to pending for re-review
        $data['status'] = 'pending';
        $data['rejection_reason'] = null;
        $data['tries_count'] = $agency->tries_count + 1;

        $agency->update($data);

        // Create activity log
        Activity::create([
            'agency_id' => $agency->id,
            'type' => 'resubmission',
            'description' => 'Agency information updated and resubmitted for review',
            'data' => json_encode([
                'previous_rejection_reason' => $agency->rejection_reason,
                'tries_count' => $data['tries_count']
            ])
        ]);

        return redirect()->route('agence.pending')
            ->with('success', 'Vos informations ont été mises à jour et soumises pour révision.');
    }
} 