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
    public function create(): View
    {
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
