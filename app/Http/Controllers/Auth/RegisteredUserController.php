<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Client;
use App\Models\Agency;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.choose-register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:client,agency',

        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => $request->role === 'client' ? null : now(), // auto verified for agency
        ]);

        if ($request->role === 'client') {
            Client::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'cin' => $request->cin,
                'birthday' => $request->birthday,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        } elseif ($request->role === 'agency') {
            Agency::create([
                'user_id' => $user->id,
                'agency_name' => $request->agency_name,
                'responsable_name' => $request->responsable_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'status' => 'pending',
            ]);
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
