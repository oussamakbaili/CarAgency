<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkUserOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Marquer l'utilisateur comme en ligne toutes les 30 secondes maximum
            $user = auth()->user();
            if (!$user->last_seen_at || $user->last_seen_at->diffInSeconds(now()) > 30) {
                $user->markOnline();
            }
        }

        return $next($request);
    }
}
