<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agency;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;
        return view('agence.profile.index', compact('agency'));
    }

    public function updateGeneral(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $agency->update([
            'agency_name' => $request->name,
            'registration_number' => $request->registration_number,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Informations générales mises à jour avec succès.');
    }

    public function updateOpeningHours(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $request->validate([
            'opening_hours' => 'required|array',
            'opening_hours.*.day' => 'required|string',
            'opening_hours.*.open_time' => 'required|string',
            'opening_hours.*.close_time' => 'required|string',
            'opening_hours.*.is_closed' => 'boolean',
        ]);

        $agency->update([
            'opening_hours' => $request->opening_hours,
        ]);

        return redirect()->back()->with('success', 'Heures d\'ouverture mises à jour avec succès.');
    }

    public function updateLocations(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $request->validate([
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $agency->update([
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->back()->with('success', 'Informations de localisation mises à jour avec succès.');
    }

    public function uploadDocument(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $request->validate([
            'document_type' => 'required|string|in:license,insurance,registration,other',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            'description' => 'nullable|string|max:255',
        ]);

        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents/agencies/' . $agency->id, $filename, 'public');

        // Store document info in agency documents
        $documents = $agency->documents ?? [];
        $documents[] = [
            'type' => $request->document_type,
            'filename' => $filename,
            'path' => $path,
            'description' => $request->description,
            'uploaded_at' => now()->toISOString(),
        ];

        $agency->update(['documents' => $documents]);

        return redirect()->back()->with('success', 'Document téléchargé avec succès.');
    }

    public function deleteDocument(Request $request, $index)
    {
        $agency = auth()->user()->agency;
        $documents = $agency->documents ?? [];
        
        if (isset($documents[$index])) {
            // Delete file from storage
            Storage::disk('public')->delete($documents[$index]['path']);
            
            // Remove from array
            unset($documents[$index]);
            $documents = array_values($documents); // Re-index array
            
            $agency->update(['documents' => $documents]);
            
            return redirect()->back()->with('success', 'Document supprimé avec succès.');
        }

        return redirect()->back()->with('error', 'Document non trouvé.');
    }

    public function updateSecurity(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    public function updateProfilePicture(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);

        // Delete old profile picture if exists
        if ($agency->profile_picture) {
            Storage::disk('public')->delete($agency->profile_picture);
        }

        $file = $request->file('profile_picture');
        $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('agencies/' . $agency->id, $filename, 'public');

        $agency->update(['profile_picture' => $path]);

        return redirect()->back()->with('success', 'Photo de profil mise à jour avec succès.');
    }
}