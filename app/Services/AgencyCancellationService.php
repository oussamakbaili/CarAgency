<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\Rental;
use App\Models\AgencyCancellationLog;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgencySuspensionNotification;
use App\Mail\AgencyCancellationWarning;

class AgencyCancellationService
{
    /**
     * Handle agency rental cancellation
     */
    public function handleCancellation(Agency $agency, Rental $rental, $reason = null, $notes = null)
    {
        // Check if agency can cancel
        if (!$agency->canCancelBooking()) {
            return [
                'success' => false,
                'message' => 'Vous ne pouvez pas annuler cette réservation. Votre compte est suspendu.',
                'warning' => $agency->getCancellationWarningMessage()
            ];
        }

        // Get warning message before incrementing
        $warningMessage = $agency->getCancellationWarningMessage();
        
        // Increment cancellation count
        $agency->incrementCancellationCount($rental->id, $reason, $notes);
        
        // Update rental status
        $rental->update([
            'status' => 'rejected',
            'rejected_at' => now()
        ]);

        // Send warning email if needed
        if ($warningMessage && !$agency->isSuspended()) {
            $this->sendWarningEmail($agency, $warningMessage);
        }

        // Send suspension email if suspended
        if ($agency->isSuspended()) {
            $this->sendSuspensionEmail($agency);
        }

        return [
            'success' => true,
            'message' => 'Réservation annulée avec succès.',
            'warning' => $warningMessage,
            'suspended' => $agency->isSuspended()
        ];
    }

    /**
     * Send warning email to agency
     */
    private function sendWarningEmail(Agency $agency, $message)
    {
        try {
            Mail::to($agency->email)->send(new AgencyCancellationWarning($agency, $message));
        } catch (\Exception $e) {
            \Log::error('Failed to send cancellation warning email: ' . $e->getMessage());
        }
    }

    /**
     * Send suspension email to agency
     */
    private function sendSuspensionEmail(Agency $agency)
    {
        try {
            Mail::to($agency->email)->send(new AgencySuspensionNotification($agency));
        } catch (\Exception $e) {
            \Log::error('Failed to send suspension email: ' . $e->getMessage());
        }
    }

    /**
     * Get agency cancellation statistics
     */
    public function getCancellationStats(Agency $agency)
    {
        return [
            'cancellation_count' => $agency->cancellation_count,
            'max_cancellations' => $agency->max_cancellations,
            'remaining_cancellations' => $agency->max_cancellations - $agency->cancellation_count,
            'is_suspended' => $agency->isSuspended(),
            'last_cancellation' => $agency->last_cancellation_at,
            'warning_message' => $agency->getCancellationWarningMessage()
        ];
    }

    /**
     * Reset agency cancellation count (admin only)
     */
    public function resetCancellationCount(Agency $agency)
    {
        $agency->update([
            'cancellation_count' => 0,
            'last_cancellation_at' => null
        ]);

        return [
            'success' => true,
            'message' => 'Compteur d\'annulations réinitialisé avec succès.'
        ];
    }
}
