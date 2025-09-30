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
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
