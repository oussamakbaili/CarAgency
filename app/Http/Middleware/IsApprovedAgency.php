<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsApprovedAgency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAgence()) {
            return redirect()->route('login');
        }

        if (!$request->user()->agency) {
            return redirect()->route('agence.pending');
        }

        $status = $request->user()->agency->status;

        if ($status === 'rejected') {
            return redirect()->route('agence.rejected');
        }

        if ($status !== 'approved') {
            return redirect()->route('agence.pending');
        }

        return $next($request);
    }
}
