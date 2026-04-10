<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PhoneLoginController extends Controller
{
    // OTP expires after 10 minutes
    private const OTP_TTL = 600;

    public function showPhoneForm(): View
    {
        return view('auth.phone-login');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:8', 'max:20'],
        ]);

        $phone = $this->normalizePhone($request->phone);
        $otp   = (string) random_int(100000, 999999);

        Cache::put("otp:{$phone}", Hash::make($otp), self::OTP_TTL);

        $this->sendSms($phone, "Votre code de vérification ToubCar : {$otp}");

        return redirect()->route('login.phone.otp', ['phone' => $phone])
            ->with('status', 'Code envoyé sur votre téléphone.');
    }

    public function showOtpForm(Request $request): View
    {
        $phone = $request->query('phone');
        return view('auth.phone-otp', compact('phone'));
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp'   => ['required', 'string', 'size:6'],
        ]);

        $phone      = $this->normalizePhone($request->phone);
        $cachedHash = Cache::get("otp:{$phone}");

        if (!$cachedHash || !Hash::check($request->otp, $cachedHash)) {
            return back()->withErrors(['otp' => 'Code invalide ou expiré. Veuillez réessayer.']);
        }

        Cache::forget("otp:{$phone}");

        $user = User::whereHas('client', fn ($q) => $q->where('phone', $phone))
            ->orWhere('phone', $phone)
            ->first();

        if (!$user) {
            // Auto-create account if phone not found
            $user = User::create([
                'name'              => 'Utilisateur ' . substr($phone, -4),
                'email'             => $phone . '@phone.toubcar.com',
                'password'          => Hash::make(Str::random(32)),
                'role'              => 'client',
                'phone'             => $phone,
                'email_verified_at' => now(),
            ]);

            Client::create([
                'user_id' => $user->id,
                'phone'   => $phone,
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('client.dashboard'));
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\s+/', '', $phone);
    }

    private function sendSms(string $phone, string $message): void
    {
        // ─────────────────────────────────────────────────────────────────
        // Configure your SMS provider here.
        // Examples:
        //
        // Twilio:
        //   $twilio = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        //   $twilio->messages->create($phone, ['from' => env('TWILIO_FROM'), 'body' => $message]);
        //
        // Vonage (Nexmo):
        //   $vonage = new \Vonage\Client(new \Vonage\Client\Credentials\Basic(env('VONAGE_KEY'), env('VONAGE_SECRET')));
        //   $vonage->sms()->send(new \Vonage\SMS\Message\SMS($phone, env('VONAGE_FROM'), $message));
        //
        // ─────────────────────────────────────────────────────────────────
        \Log::info("SMS OTP to {$phone}: {$message}");
    }
}
