<?php

// app/Http/Middleware/AgenceMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClientMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'client') {
            return $next($request);
        }

        // Si la requête vient de booking, rediriger vers booking.step2 avec un message d'erreur
        if ($request->is('booking/*') || $request->routeIs('booking.*')) {
            // Si l'utilisateur est connecté mais n'est pas un client, le déconnecter
            if (Auth::check()) {
                Auth::logout();
            }
            return redirect()->route('booking.step2')
                ->with('error', 'Seuls les clients peuvent réserver des véhicules. Veuillez vous connecter avec un compte client.');
        }

        // Redirect to public home page instead of root
        return redirect()->route('public.home')
            ->with('error', 'Accès réservé aux clients uniquement.');
    }
}
