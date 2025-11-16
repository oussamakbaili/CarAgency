<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        
        // Store the intended URL for redirect after login
        session(['url.intended' => $request->fullUrl()]);
        
        // Always redirect to login page for unauthenticated users
        return route('login');
    }
}
