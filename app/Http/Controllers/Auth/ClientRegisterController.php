<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ClientRegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register-client');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cin' => ['required', 'string', 'max:20', 'unique:clients'],
            'birthday' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'cin_recto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // 5MB max
            'cin_verso' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // 5MB max
            'driving_license_number' => ['nullable', 'string', 'max:50'],
            'driving_license_expiry' => ['nullable', 'date'],
            'driving_license_image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // 5MB max
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        // Upload CIN images
        $cinRectoPath = null;
        $cinVersoPath = null;
        if ($request->hasFile('cin_recto')) {
            $cinRectoPath = $request->file('cin_recto')->store('clients/cin', 'public');
        }
        if ($request->hasFile('cin_verso')) {
            $cinVersoPath = $request->file('cin_verso')->store('clients/cin', 'public');
        }

        // Upload driving license image
        $drivingLicensePath = null;
        if ($request->hasFile('driving_license_image')) {
            $drivingLicensePath = $request->file('driving_license_image')->store('clients/driving-licenses', 'public');
        }

        $client = Client::create([
            'user_id' => $user->id,
            'cin' => $request->cin,
            'cin_recto' => $cinRectoPath,
            'cin_verso' => $cinVersoPath,
            'birthday' => $request->birthday,
            'phone' => $request->phone,
            'address' => $request->address,
            'driving_license_number' => $request->driving_license_number,
            'driving_license_expiry' => $request->driving_license_expiry,
            'driving_license_image' => $drivingLicensePath,
        ]);

        event(new Registered($user));

        // Don't log in immediately - redirect to email verification
        return redirect()->route('verification.notice')->with('status', 'Vérifiez votre email pour continuer.');
    }
}
