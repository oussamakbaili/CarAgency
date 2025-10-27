<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class SupportMessageController extends Controller
{
    /**
     * Get messages for a support ticket (Client view)
     */
    public function getMessages($ticketId)
    {
        $client = auth()->user()->client;
        $ticket = SupportTicket::where('id', $ticketId)
            ->where('client_id', $client->id)
            ->firstOrFail();
            
        $messages = $ticket->messages()->with(['sender', 'recipient'])->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Send a message in a support ticket (Client view)
     */
    public function sendMessage(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $client = auth()->user()->client;
        $ticket = SupportTicket::where('id', $ticketId)
            ->where('client_id', $client->id)
            ->firstOrFail();

        // Find admin users to send message to
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Aucun administrateur trouvé'], 400);
        }

        // Create the message
        $message = $ticket->sendMessage($client, $admin, $request->message);

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
     * Mark messages as read (Client view)
     */
    public function markAsRead(Request $request, $ticketId)
    {
        $client = auth()->user()->client;
        $ticket = SupportTicket::where('id', $ticketId)
            ->where('client_id', $client->id)
            ->firstOrFail();

        $ticket->messages()
            ->where('recipient_id', $client->id)
            ->where('recipient_type', 'App\Models\Client')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread messages count for client
     */
    public function getUnreadCount()
    {
        $client = auth()->user()->client;
        
        $count = SupportMessage::whereHas('supportTicket', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->where('recipient_id', $client->id)
            ->where('recipient_type', 'App\Models\Client')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
