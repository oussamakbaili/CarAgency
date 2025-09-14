<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        // If no client record exists, create one
        if (!$client) {
            $client = Client::create([
                'user_id' => $user->id,
                'cin' => 'TEMP_' . $user->id,
                'birthday' => '1990-01-01',
                'phone' => $user->phone ?? '0000000000',
                'address' => 'Address not provided',
                'city' => 'City not provided',
                'postal_code' => '00000',
            ]);
        }

        return view('client.profile.index', compact('client'));
    }

    public function updateGeneral(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'cin' => ['required', 'string', 'max:20', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'date_of_birth' => 'required|date|before:today',
            'driving_license_number' => 'nullable|string|max:50',
            'driving_license_expiry' => 'nullable|date|after:today',
        ]);

        // Update user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update client information
        $client->update([
            'cin' => $request->cin,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'date_of_birth' => $request->date_of_birth,
            'driving_license_number' => $request->driving_license_number,
            'driving_license_expiry' => $request->driving_license_expiry,
        ]);

        return redirect()->back()->with('success', 'Informations personnelles mises à jour avec succès.');
    }

    public function updateSecurity(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    public function updatePreferences(Request $request)
    {
        $client = auth()->user()->client;

        $request->validate([
            'preferred_language' => 'nullable|string|in:fr,ar,en',
            'notifications_email' => 'nullable|boolean',
            'notifications_sms' => 'nullable|boolean',
            'marketing_emails' => 'nullable|boolean',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $preferences = $client->preferences ?? [];
        $preferences = array_merge($preferences, [
            'preferred_language' => $request->preferred_language ?? 'fr',
            'notifications_email' => $request->has('notifications_email'),
            'notifications_sms' => $request->has('notifications_sms'),
            'marketing_emails' => $request->has('marketing_emails'),
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
        ]);

        $client->update([
            'preferences' => $preferences,
        ]);

        return redirect()->back()->with('success', 'Préférences mises à jour avec succès.');
    }

    public function updateProfilePicture(Request $request)
    {
        $client = auth()->user()->client;

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old profile picture if exists
        if ($client->profile_picture) {
            Storage::disk('public')->delete($client->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('client-profiles', 'public');
        
        $client->update([
            'profile_picture' => $path,
        ]);

        return redirect()->back()->with('success', 'Photo de profil mise à jour avec succès.');
    }

    public function deleteProfilePicture()
    {
        $client = auth()->user()->client;

        if ($client->profile_picture) {
            Storage::disk('public')->delete($client->profile_picture);
            $client->update(['profile_picture' => null]);
        }

        return redirect()->back()->with('success', 'Photo de profil supprimée avec succès.');
    }

    public function updateDocuments(Request $request)
    {
        $client = auth()->user()->client;

        $request->validate([
            'cin_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'driving_license_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'identity_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $documents = $client->documents ?? [];

        // Handle CIN document
        if ($request->hasFile('cin_document')) {
            if (isset($documents['cin_document'])) {
                Storage::disk('public')->delete($documents['cin_document']);
            }
            $documents['cin_document'] = $request->file('cin_document')->store('client-documents', 'public');
        }

        // Handle driving license document
        if ($request->hasFile('driving_license_document')) {
            if (isset($documents['driving_license_document'])) {
                Storage::disk('public')->delete($documents['driving_license_document']);
            }
            $documents['driving_license_document'] = $request->file('driving_license_document')->store('client-documents', 'public');
        }

        // Handle identity document
        if ($request->hasFile('identity_document')) {
            if (isset($documents['identity_document'])) {
                Storage::disk('public')->delete($documents['identity_document']);
            }
            $documents['identity_document'] = $request->file('identity_document')->store('client-documents', 'public');
        }

        $client->update([
            'documents' => $documents,
        ]);

        return redirect()->back()->with('success', 'Documents mis à jour avec succès.');
    }

    public function deleteDocument($type)
    {
        $client = auth()->user()->client;
        $documents = $client->documents ?? [];

        if (isset($documents[$type])) {
            Storage::disk('public')->delete($documents[$type]);
            unset($documents[$type]);
            
            $client->update(['documents' => $documents]);
        }

        return redirect()->back()->with('success', 'Document supprimé avec succès.');
    }
}


