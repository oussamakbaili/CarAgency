<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Stocker l'URL de retour si fournie
        if ($request->has('return_url')) {
            session(['url.intended' => $request->input('return_url')]);
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $role = $user->role;

        // Vérifier s'il y a une redirection vers la réservation
        $redirectTo = $request->input('redirect_to');
        $returnUrl = $request->input('return_url');
        $intendedUrl = session()->pull('url.intended');
        
        // Déterminer l'URL de redirection (priorité: redirect_to > return_url > intended)
        $targetUrl = $redirectTo ?: ($returnUrl ?: $intendedUrl);
        
        // Vérifier s'il y a une session de réservation active
        $hasBookingSession = session()->has('booking_data');
        
        // Si la redirection est vers booking OU s'il y a une session de réservation, vérifier que l'utilisateur est un client
        if (($targetUrl && (str_contains($targetUrl, 'booking') || str_contains($targetUrl, 'step3'))) || $hasBookingSession) {
            if ($role !== 'client') {
                Auth::logout();
                return redirect()->route('booking.step2')
                    ->with('error', 'Seuls les clients peuvent réserver des véhicules. Veuillez vous connecter avec un compte client.');
            }
            // Si c'est un client et qu'il y a une session de réservation, rediriger vers step3
            if ($hasBookingSession && !$targetUrl) {
                return redirect()->route('booking.step3');
            }
            if ($targetUrl) {
                return redirect($targetUrl);
            }
        }

        if ($role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        } elseif ($role === 'agence') {
            if (!$user->agency) {
                return redirect()->route('agence.pending');
            }

            $status = $user->agency->status;

            if ($status === 'rejected') {
                return redirect()->route('agence.rejected');
            } elseif ($status === 'pending') {
                return redirect()->route('agence.pending');
            }

            return redirect()->intended('/agence/dashboard');
        } elseif ($role === 'client') {
            // Pour les clients, vérifier s'il y a une URL intended
            if ($intendedUrl) {
                return redirect($intendedUrl);
            }
            return redirect()->intended('/');
        }

        return redirect()->intended('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Get user info before logout for logging
        $user = Auth::user();
        $userRole = $user ? $user->role : 'unknown';
        
        // Complete logout process
        Auth::guard('web')->logout();
        
        // Invalidate the session completely
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Clear all session data
        $request->session()->flush();
        
        // Add success message
        $request->session()->flash('success', 'Vous avez été déconnecté avec succès.');

        // Force redirect to home page using route name
        // This ensures we go to the public home page, not any profile page
        return redirect()->route('public.home', ['logout' => 'success'])->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
}
