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
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);
        

        $client = Client::create([
            'name' => $request->name,  
            'user_id' => $user->id,
            'cin' => $request->cin,
            'birthday' => $request->birthday,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        event(new Registered($user));

        // Don't log in immediately - redirect to email verification
        return redirect()->route('verification.notice')->with('status', 'Vérifiez votre email pour continuer.');
    }
}
