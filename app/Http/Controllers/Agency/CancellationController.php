<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Rental;
use App\Services\AgencyCancellationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CancellationController extends Controller
{
    protected $cancellationService;

    public function __construct(AgencyCancellationService $cancellationService)
    {
        $this->cancellationService = $cancellationService;
    }

    /**
     * Show cancellation form
     */
    public function show(Rental $rental)
    {
        $agency = Auth::user()->agency;
        
        if (!$agency || $rental->agency_id !== $agency->id) {
            abort(403, 'Accès non autorisé');
        }

        $stats = $this->cancellationService->getCancellationStats($agency);
        
        return view('agency.bookings.cancel', compact('rental', 'stats'));
    }

    /**
     * Process cancellation
     */
    public function cancel(Request $request, Rental $rental)
    {
        $agency = Auth::user()->agency;
        
        if (!$agency || $rental->agency_id !== $agency->id) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        $result = $this->cancellationService->handleCancellation(
            $agency,
            $rental,
            $request->cancellation_reason,
            $request->notes
        );

        if ($result['success']) {
            return redirect()
                ->route('agence.bookings.index')
                ->with('success', $result['message'])
                ->with('warning', $result['warning'] ?? null);
        } else {
            return back()
                ->with('error', $result['message'])
                ->with('warning', $result['warning'] ?? null);
        }
    }

    /**
     * Get cancellation statistics
     */
    public function stats()
    {
        $agency = Auth::user()->agency;
        
        if (!$agency) {
            abort(403, 'Agence non trouvée');
        }

        $stats = $this->cancellationService->getCancellationStats($agency);
        
        return response()->json($stats);
    }
}