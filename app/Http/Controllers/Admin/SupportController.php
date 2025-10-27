<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Client;
use App\Models\Agency;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    /**
     * Display support dashboard with statistics
     */
    public function index(Request $request)
    {
        // Statistics
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->open()->count(),
        ];

        // Recent tickets with filters
        $query = SupportTicket::with(['client', 'agency', 'assignedTo'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('user_type')) {
            if ($request->user_type === 'client') {
                $query->whereNotNull('client_id');
            } elseif ($request->user_type === 'agency') {
                $query->whereNotNull('agency_id');
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $tickets = $query->paginate(15);

        // Get admin users for assignment
        $admins = User::where('role', 'admin')->get();

        return view('admin.support.index', compact('stats', 'tickets', 'admins'));
    }

    /**
     * Display ticket details
     */
    public function show($id)
    {
        $ticket = SupportTicket::with([
            'client', 
            'agency', 
            'rental',
            'assignedTo',
            'lastReplyBy'
        ])->findOrFail($id);

        // Get admin users for assignment
        $admins = User::where('role', 'admin')->get();

        return view('admin.support.show', compact('ticket', 'admins'));
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        $oldStatus = $ticket->status;
        
        switch ($request->status) {
            case 'in_progress':
                $ticket->markAsInProgress();
                break;
            case 'resolved':
                $ticket->markAsResolved();
                break;
            case 'open':
                $ticket->reopen();
                break;
        }

        // Add system message pour les autres changements de statut
        $ticket->addReply(
            "Statut changé de '{$oldStatus}' à '{$request->status}' par l'administrateur.",
            auth()->id(),
            'system'
        );

        return redirect()->back();
    }

    /**
     * Update ticket priority
     */
    public function updatePriority(Request $request, $id)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $oldPriority = $ticket->priority;
        
        $ticket->update(['priority' => $request->priority]);

        // Add system message
        $ticket->addReply(
            "Priorité changée de '{$oldPriority}' à '{$request->priority}' par l'administrateur.",
            auth()->id(),
            'system'
        );

        return redirect()->back();
    }

    /**
     * Assign ticket to admin
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $admin = User::findOrFail($request->admin_id);
        
        $ticket->update(['assigned_to' => $admin->id]);

        // Add system message
        $ticket->addReply(
            "Ticket assigné à {$admin->name}.",
            auth()->id(),
            'system'
        );

        return redirect()->back()->with('success', 'Ticket assigné avec succès.');
    }

    /**
     * Add reply to ticket
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        // Determine recipient
        $recipient = null;
        if ($ticket->agency_id) {
            $recipient = $ticket->agency;
        } elseif ($ticket->client_id) {
            $recipient = $ticket->client;
        }

        if ($recipient) {
            // Use new message system
            $ticket->sendMessage(auth()->user(), $recipient, $request->message);
        } else {
            // Fallback to old system
            $ticket->addReply(
                $request->message,
                auth()->id(),
                'admin'
            );
        }

        // If ticket was resolved or closed, reopen it
        if (in_array($ticket->status, ['resolved', 'closed'])) {
            $ticket->reopen();
        }

        return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
    }

    /**
     * Delete ticket
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.support.index')
            ->with('success', 'Ticket supprimé avec succès.');
    }

    /**
     * Get statistics for charts
     */
    public function statistics()
    {
        // Tickets by status
        $byStatus = SupportTicket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Tickets by priority
        $byPriority = SupportTicket::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        // Tickets by category
        $byCategory = SupportTicket::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');

        // Tickets by user type
        $clientTickets = SupportTicket::whereNotNull('client_id')->count();
        $agencyTickets = SupportTicket::whereNotNull('agency_id')->count();

        // Tickets trend (last 30 days)
        $trend = SupportTicket::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Response time (average time to first reply)
        $avgResponseTime = SupportTicket::whereNotNull('last_reply_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, last_reply_at)) as avg_hours'))
            ->first();

        return response()->json([
            'byStatus' => $byStatus,
            'byPriority' => $byPriority,
            'byCategory' => $byCategory,
            'byUserType' => [
                'client' => $clientTickets,
                'agency' => $agencyTickets
            ],
            'trend' => $trend,
            'avgResponseTime' => $avgResponseTime->avg_hours ?? 0
        ]);
    }

    /**
     * Bulk actions on tickets
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:support_tickets,id',
            'action' => 'required|in:assign,status,priority,delete'
        ]);

        $tickets = SupportTicket::whereIn('id', $request->ticket_ids)->get();

        switch ($request->action) {
            case 'assign':
                $request->validate(['admin_id' => 'required|exists:users,id']);
                $tickets->each(function ($ticket) use ($request) {
                    $ticket->update(['assigned_to' => $request->admin_id]);
                });
                $message = 'Tickets assignés avec succès.';
                break;

            case 'status':
                $request->validate(['status' => 'required|in:open,in_progress,resolved,closed']);
                $tickets->each(function ($ticket) use ($request) {
                    $ticket->update(['status' => $request->status]);
                });
                $message = 'Statut des tickets mis à jour avec succès.';
                break;

            case 'priority':
                $request->validate(['priority' => 'required|in:low,medium,high,urgent']);
                $tickets->each(function ($ticket) use ($request) {
                    $ticket->update(['priority' => $request->priority]);
                });
                $message = 'Priorité des tickets mise à jour avec succès.';
                break;

            case 'delete':
                $tickets->each->delete();
                $message = 'Tickets supprimés avec succès.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}

