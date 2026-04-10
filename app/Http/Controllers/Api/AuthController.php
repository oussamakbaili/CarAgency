<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email ou mot de passe incorrect.'],
            ]);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'phone'  => $user->phone,
                'role'   => $user->role,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => 'client',
        ]);

        // Créer le profil client associé
        Client::create([
            'user_id' => $user->id,
            'phone'   => $request->phone ?? '',
            'address' => '',
            'birthday' => now()->subYears(18)->format('Y-m-d'),
            'cin'     => '',
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    // ─── Phone OTP ────────────────────────────────────────────────────────────

    public function sendPhoneOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string|min:8']);

        $phone = $request->phone;
        $code  = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put("otp_{$phone}", $code, now()->addMinutes(10));

        \Log::info("[ToubCar OTP] Phone: {$phone}  Code: {$code}");

        // In production: send via Twilio or any SMS gateway
        // TODO: Replace with real SMS sending

        $response = ['message' => 'Code envoyé'];

        // Return code in response only in debug/dev mode for testing
        if (config('app.debug')) {
            $response['dev_code'] = $code;
        }

        return response()->json($response);
    }

    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code'  => 'required|string|size:6',
        ]);

        $phone   = $request->phone;
        $stored  = Cache::get("otp_{$phone}");

        if (!$stored || $stored !== $request->code) {
            return response()->json(['message' => 'Code invalide ou expiré.'], 422);
        }

        Cache::forget("otp_{$phone}");

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            $user = User::create([
                'name'     => 'Utilisateur',
                'email'    => 'phone_' . Str::random(8) . '@toubcar.app',
                'password' => Hash::make(Str::random(32)),
                'phone'    => $phone,
                'role'     => 'client',
            ]);
            Client::create([
                'user_id' => $user->id,
                'phone'   => $phone,
                'address' => '',
                'birthday' => now()->subYears(18)->format('Y-m-d'),
                'cin'     => '',
            ]);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ]);
    }

    // ─── Google OAuth ─────────────────────────────────────────────────────────

    public function googleAuth(Request $request)
    {
        $request->validate(['access_token' => 'required|string']);

        $googleResp = Http::get('https://www.googleapis.com/oauth2/v3/userinfo', [
            'access_token' => $request->access_token,
        ]);

        if (!$googleResp->ok()) {
            return response()->json(['message' => 'Token Google invalide.'], 401);
        }

        $g    = $googleResp->json();
        $user = User::where('email', $g['email'])->first();

        if (!$user) {
            $user = User::create([
                'name'     => $g['name'] ?? 'Utilisateur Google',
                'email'    => $g['email'],
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

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ]);
    }

    // ─── Apple Sign In ────────────────────────────────────────────────────────

    public function appleAuth(Request $request)
    {
        $request->validate(['identity_token' => 'required|string']);

        // Decode the JWT payload (not verifying signature here – use a library in production)
        $parts   = explode('.', $request->identity_token);
        $payload = json_decode(base64_decode(str_pad($parts[1], strlen($parts[1]) + (4 - strlen($parts[1]) % 4) % 4, '=', STR_PAD_RIGHT)), true);

        if (!isset($payload['email']) && !isset($payload['sub'])) {
            return response()->json(['message' => 'Token Apple invalide.'], 401);
        }

        $appleId = $payload['sub'];
        $email   = $payload['email'] ?? "apple_{$appleId}@toubcar.app";
        $user    = User::where('email', $email)->first();

        if (!$user) {
            $name = $request->full_name ?? 'Utilisateur Apple';
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
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

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ]);
    }
}
