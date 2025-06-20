<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\Registered;

class AgencyRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register-agency');
    }

    public function register(Request $request)
    {
        $request->validate([
            'agency_name' => ['required', 'string', 'max:255'],
            'responsable_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'commercial_register_number' => ['required', 'string', 'max:50'],
            'responsable_phone' => ['required', 'string', 'max:20'],
            'responsable_position' => ['required', 'string', 'max:100'],
            'responsable_identity_number' => ['required', 'string', 'max:50'],
            'years_in_business' => ['required', 'integer', 'min:0'],
            'business_description' => ['required', 'string'],
            'estimated_fleet_size' => ['required', 'integer', 'min:1'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'commercial_register_doc' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'identity_doc' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'tax_doc' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'additional_docs.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->agency_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'agence',
                'email_verified_at' => now(), // Temporarily auto-verify email
            ]);

            // Handle file uploads
            $commercialRegisterPath = $request->file('commercial_register_doc')->store('agency-documents', 'public');
            $identityDocPath = $request->file('identity_doc')->store('agency-documents', 'public');
            $taxDocPath = null;
            if ($request->hasFile('tax_doc')) {
                $taxDocPath = $request->file('tax_doc')->store('agency-documents', 'public');
            }

            // Handle additional documents
            $additionalDocs = [];
            if ($request->hasFile('additional_docs')) {
                foreach ($request->file('additional_docs') as $file) {
                    $additionalDocs[] = $file->store('agency-documents', 'public');
                }
            }

            // Create agency
            Agency::create([
                'user_id' => $user->id,
                'agency_name' => $request->agency_name,
                'responsable_name' => $request->responsable_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'tax_number' => $request->tax_number,
                'commercial_register_number' => $request->commercial_register_number,
                'responsable_phone' => $request->responsable_phone,
                'responsable_position' => $request->responsable_position,
                'responsable_identity_number' => $request->responsable_identity_number,
                'commercial_register_doc' => $commercialRegisterPath,
                'identity_doc' => $identityDocPath,
                'tax_doc' => $taxDocPath,
                'additional_docs' => $additionalDocs,
                'years_in_business' => $request->years_in_business,
                'business_description' => $request->business_description,
                'estimated_fleet_size' => $request->estimated_fleet_size,
                'status' => 'pending',
            ]);

            DB::commit();

            // Log the user in immediately
            auth()->login($user);

            return redirect()->route('agence.pending')
                ->with('success', 'Votre inscription a été effectuée avec succès. Veuillez attendre l\'approbation de l\'administrateur.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up any uploaded files if there was an error
            if (isset($commercialRegisterPath)) Storage::disk('public')->delete($commercialRegisterPath);
            if (isset($identityDocPath)) Storage::disk('public')->delete($identityDocPath);
            if (isset($taxDocPath)) Storage::disk('public')->delete($taxDocPath);
            if (!empty($additionalDocs)) {
                foreach ($additionalDocs as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            return back()->with('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.')
                        ->withInput();
        }
    }
}
