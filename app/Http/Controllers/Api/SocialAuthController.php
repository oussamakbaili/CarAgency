<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the mobile app to Google OAuth.
     * The mobile app passes its own redirect_uri so we can return the token via deep link.
     */
    public function redirectToGoogle(Request $request)
    {
        // Save the app's deep-link URI so we can redirect back after auth
        if ($request->has('redirect_uri')) {
            session(['mobile_redirect_uri' => $request->redirect_uri]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Google OAuth callback — creates/finds the user and redirects back to the app.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            $appUri = session('mobile_redirect_uri', 'toubcar://auth/callback');
            return redirect($appUri . '?error=' . urlencode('Connexion Google annulée.'));
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'     => $googleUser->getName() ?? 'Utilisateur Google',
                'email'    => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                'role'     => 'client',
            ]);
            Client::create([
                'user_id' => $user->id,
                'phone'   => '',
                'address' => '',
                'birthday' => now()->subYears(18)->format('Y-m-d'),
                'cin'     => '',
            ]);
        }

        $token    = $user->createToken('mobile-app')->plainTextToken;
        $appUri   = session('mobile_redirect_uri', 'toubcar://auth/callback');
        $userData = urlencode(json_encode([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role'  => $user->role,
        ]));

        return redirect("{$appUri}?token={$token}&user={$userData}");
    }
}
