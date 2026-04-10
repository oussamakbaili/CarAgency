<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'La connexion via Google a échoué. Veuillez réessayer.');
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'               => $googleUser->getName() ?? 'Utilisateur Google',
                'email'              => $googleUser->getEmail(),
                'password'           => Hash::make(Str::random(32)),
                'role'               => 'client',
                'email_verified_at'  => now(),
            ]);

            Client::create([
                'user_id' => $user->id,
                'phone'   => null,
            ]);
        } elseif (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user, true);

        return redirect()->intended($this->redirectPath($user));
    }

    public function redirectToApple(): RedirectResponse
    {
        return Socialite::driver('apple')->redirect();
    }

    public function handleAppleCallback(): RedirectResponse
    {
        try {
            $appleUser = Socialite::driver('apple')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'La connexion via Apple a échoué. Veuillez réessayer.');
        }

        $email = $appleUser->getEmail();

        if (!$email) {
            return redirect()->route('login')->with('error', 'Impossible de récupérer votre email Apple. Veuillez réessayer.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $name = trim(($appleUser->user['name']['firstName'] ?? '') . ' ' . ($appleUser->user['name']['lastName'] ?? ''));
            $user = User::create([
                'name'              => $name ?: 'Utilisateur Apple',
                'email'             => $email,
                'password'          => Hash::make(Str::random(32)),
                'role'              => 'client',
                'email_verified_at' => now(),
            ]);

            Client::create([
                'user_id' => $user->id,
                'phone'   => null,
            ]);
        } elseif (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user, true);

        return redirect()->intended($this->redirectPath($user));
    }

    private function redirectPath(User $user): string
    {
        return match ($user->role) {
            'admin'  => route('admin.dashboard'),
            'agence' => route('agence.dashboard'),
            default  => route('client.dashboard'),
        };
    }
}
