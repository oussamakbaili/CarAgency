<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApprovedAgency
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->agency) {
            return redirect()->route('register.agency');
        }

        $status = auth()->user()->agency->status;

        if ($status === 'pending') {
            return redirect()->route('agence.pending');
        }

        if ($status === 'rejected') {
            return redirect()->route('agence.rejected');
        }

        if ($status !== 'approved') {
            abort(403, 'Your agency account is not approved.');
        }

        return $next($request);
    }
} 