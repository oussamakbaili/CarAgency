<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Services\AgencyCancellationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CancellationExampleController extends Controller
{
    protected $cancellationService;

    public function __construct(AgencyCancellationService $cancellationService)
    {
        $this->cancellationService = $cancellationService;
    }

    /**
     * Example method showing how to cancel a rental with suspension logic
     */
    public function cancelRentalExample(Rental $rental, $reason = 'vehicle_unavailable')
    {
        $agency = Auth::user()->agency;
        
        if (!$agency) {
            return response()->json(['error' => 'Agence non trouvée'], 404);
        }

        // Check if agency can cancel (not suspended and under limit)
        if (!$agency->canCancelBooking()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas annuler cette réservation. Votre compte est suspendu.',
                'warning' => $agency->getCancellationWarningMessage()
            ], 403);
        }

        // Get warning message before processing
        $warningMessage = $agency->getCancellationWarningMessage();

        // Process the cancellation
        $result = $this->cancellationService->handleCancellation(
            $agency,
            $rental,
            $reason,
            'Annulation automatique pour test'
        );

        return response()->json($result);
    }

    /**
     * Example method to check agency cancellation status
     */
    public function checkStatus()
    {
        $agency = Auth::user()->agency;
        
        if (!$agency) {
            return response()->json(['error' => 'Agence non trouvée'], 404);
        }

        $stats = $this->cancellationService->getCancellationStats($agency);

        return response()->json([
            'can_cancel' => $agency->canCancelBooking(),
            'is_suspended' => $agency->isSuspended(),
            'warning_message' => $agency->getCancellationWarningMessage(),
            'stats' => $stats
        ]);
    }
}
