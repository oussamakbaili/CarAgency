<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'name'     => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date', 'before:today'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'client',
            'phone'    => $request->phone,
        ]);

        Client::create([
            'user_id'  => $user->id,
            'birthday' => $request->birthday,
            'phone'    => $request->phone,
        ]);

        event(new Registered($user));

        return redirect()->route('verification.notice')->with('status', 'Vérifiez votre email pour continuer.');
    }
}
