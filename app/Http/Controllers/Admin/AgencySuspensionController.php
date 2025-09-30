<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Services\AgencyCancellationService;
use Illuminate\Http\Request;

class AgencySuspensionController extends Controller
{
    protected $cancellationService;

    public function __construct(AgencyCancellationService $cancellationService)
    {
        $this->cancellationService = $cancellationService;
    }

    /**
     * List suspended agencies
     */
    public function index()
    {
        $suspendedAgencies = Agency::where('is_suspended', true)
            ->orWhere('status', 'suspended')
            ->with(['user', 'cancellationLogs'])
            ->paginate(15);

        return view('admin.agencies.suspended', compact('suspendedAgencies'));
    }

    /**
     * Show agency suspension details
     */
    public function show(Agency $agency)
    {
        $cancellationLogs = $agency->cancellationLogs()
            ->with('rental')
            ->orderBy('cancelled_at', 'desc')
            ->paginate(10);

        $stats = $this->cancellationService->getCancellationStats($agency);

        return view('admin.agencies.suspension-details', compact('agency', 'cancellationLogs', 'stats'));
    }

    /**
     * Suspend an agency
     */
    public function suspend(Request $request, Agency $agency)
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:500'
        ]);

        $agency->suspend($request->suspension_reason);

        return redirect()
            ->route('admin.agencies.suspended')
            ->with('success', 'Agence suspendue avec succès.');
    }

    /**
     * Unsuspend an agency
     */
    public function unsuspend(Agency $agency)
    {
        $agency->unsuspend();

        return redirect()
            ->route('admin.agencies.show', $agency)
            ->with('success', 'Agence réactivée avec succès.');
    }

    /**
     * Reset cancellation count
     */
    public function resetCancellations(Agency $agency)
    {
        $agency->cancellation_count = 0;
        $agency->last_cancellation_at = null;
        $agency->save();

        return redirect()
            ->back()
            ->with('success', 'Le compteur d\'annulations a été réinitialisé avec succès.');
    }

    /**
     * Update max cancellations limit
     */
    public function updateMaxCancellations(Request $request, Agency $agency)
    {
        $request->validate([
            'max_cancellations' => 'required|integer|min:1|max:10'
        ]);

        $agency->update([
            'max_cancellations' => $request->max_cancellations
        ]);

        return redirect()
            ->back()
            ->with('success', 'Limite d\'annulations mise à jour avec succès.');
    }
}