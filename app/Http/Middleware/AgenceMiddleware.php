<?php

// app/Http/Middleware/AgenceMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AgenceMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'agence') {
            return $next($request);
        }

        // Redirect to public home page instead of root
        return redirect()->route('public.home');
    }
}
