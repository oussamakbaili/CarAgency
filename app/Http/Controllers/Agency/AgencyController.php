<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgencyController extends Controller
{
    public function showRegisterForm()
    {
        return view('agency.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'agency_name' => 'required|string|max:255',
            'commercial_register_number' => 'required|string|max:255|unique:agencies',
            'email' => 'required|string|email|max:255|unique:agencies',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'responsable_name' => 'required|string|max:255',
            'responsable_position' => 'required|string|max:255',
            'responsable_phone' => 'required|string|max:20',
            'responsable_identity_number' => 'required|string|max:50',
            'commercial_register_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'identity_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tax_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Handle file uploads
        $commercialRegisterDoc = $request->file('commercial_register_doc');
        $identityDoc = $request->file('identity_doc');
        $taxDoc = $request->file('tax_doc');

        $commercialRegisterPath = $commercialRegisterDoc->store('agencies/documents', 'public');
        $identityPath = $identityDoc->store('agencies/documents', 'public');
        $taxPath = $taxDoc->store('agencies/documents', 'public');

        // Create agency
        $agency = Agency::create([
            'agency_name' => $request->agency_name,
            'commercial_register_number' => $request->commercial_register_number,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'password' => Hash::make($request->password),
            'responsable_name' => $request->responsable_name,
            'responsable_position' => $request->responsable_position,
            'responsable_phone' => $request->responsable_phone,
            'responsable_identity_number' => $request->responsable_identity_number,
            'commercial_register_doc' => $commercialRegisterPath,
            'identity_doc' => $identityPath,
            'tax_doc' => $taxPath,
            'status' => 'pending',
        ]);

        // Send notification email to admin
        // You can implement email notification here

        return redirect()->route('agency.register.success')
            ->with('success', 'Votre demande d\'inscription a été soumise avec succès. Vous recevrez une réponse par email dans les 48 heures.');
    }

    public function success()
    {
        return view('agency.success');
    }

    public function showPending()
    {
        // This method is for agencies to view their pending status
        return view('agency.pending');
    }

    public function showRejected()
    {
        // This method is for agencies to view their rejected status
        return view('agency.rejected');
    }

    public function update(Request $request)
    {
        // This method is for agencies to update their information
        $request->validate([
            'agency_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'responsable_name' => 'required|string|max:255',
            'responsable_position' => 'required|string|max:255',
            'responsable_phone' => 'required|string|max:20',
        ]);

        $agency = auth()->user()->agency;
        $agency->update($request->only([        
            'agency_name', 'phone', 'address', 'city', 
            'responsable_name', 'responsable_position', 'responsable_phone'
        ]));

        return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
    }
}