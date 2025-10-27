<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Client;
use App\Models\Agency;
use Illuminate\Support\Facades\Auth;

class SupportMessageController extends Controller
{
    /**
     * Get messages for a support ticket (Admin view)
     */
    public function getMessages($ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);
        $messages = $ticket->messages()->with(['sender', 'recipient'])->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Send a message in a support ticket (Admin view)
     */
    public function sendMessage(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $admin = auth()->user();
        $ticket = SupportTicket::findOrFail($ticketId);

        // Determine the recipient based on ticket type
        $recipient = null;
        if ($ticket->client_id) {
            $recipient = Client::find($ticket->client_id);
        } elseif ($ticket->agency_id) {
            $recipient = Agency::find($ticket->agency_id);
        }

        if (!$recipient) {
            return response()->json(['success' => false, 'message' => 'Destinataire non trouvé'], 400);
        }

        // Create the message
        $message = $ticket->sendMessage($admin, $recipient, $request->message);

        // Update ticket status to in_progress if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    /**
     * Mark messages as read (Admin view)
     */
    public function markAsRead(Request $request, $ticketId)
    {
        $admin = auth()->user();
        $ticket = SupportTicket::findOrFail($ticketId);

        $ticket->messages()
            ->where('recipient_id', $admin->id)
            ->where('recipient_type', 'App\Models\User')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread messages count for admin
     */
    public function getUnreadCount()
    {
        $admin = auth()->user();
        
        $count = SupportMessage::where('recipient_id', $admin->id)
            ->where('recipient_type', 'App\Models\User')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}

