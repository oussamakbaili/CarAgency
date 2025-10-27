<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\Auth;

class SupportMessageController extends Controller
{
    /**
     * Get messages for a support ticket (Agency view)
     */
    public function getMessages($ticketId)
    {
        $agency = auth()->user()->agency;
        $ticket = SupportTicket::where('id', $ticketId)
            ->where('agency_id', $agency->id)
            ->firstOrFail();
            
        $messages = $ticket->messages()->with(['sender', 'recipient'])->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Send a message in a support ticket (Agency view)
     */
    public function sendMessage(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $agency = auth()->user()->agency;
        $ticket = SupportTicket::where('id', $ticketId)
            ->where('agency_id', $agency->id)
            ->firstOrFail();

        // Find admin users to send message to
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Aucun administrateur trouvé'], 400);
        }

        // Create the message
        $message = $ticket->sendMessage($agency, $admin, $request->message);

        // Update ticket status to open if it was closed
        if ($ticket->status === 'closed') {
            $ticket->reopen();
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    /**
     * Mark messages as read (Agency view)
     */
    public function markAsRead(Request $request, $ticketId)
    {
        $agency = auth()->user()->agency;
        $ticket = SupportTicket::where('id', $ticketId)
            ->where('agency_id', $agency->id)
            ->firstOrFail();

        $ticket->messages()
            ->where('recipient_id', $agency->id)
            ->where('recipient_type', 'App\Models\Agency')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread messages count for agency
     */
    public function getUnreadCount()
    {
        $agency = auth()->user()->agency;
        
        $count = SupportMessage::whereHas('supportTicket', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->where('recipient_id', $agency->id)
            ->where('recipient_type', 'App\Models\Agency')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
