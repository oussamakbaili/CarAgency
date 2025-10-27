<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Agency;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class SupportMessageController extends Controller
{
    /**
     * Send a message in a support ticket
     */
    public function sendMessage(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $ticket = SupportTicket::findOrFail($ticketId);
        $sender = Auth::user();

        // Determine recipient based on who created the ticket
        $recipient = null;
        if ($ticket->agency_id) {
            // If ticket is from agency, recipient is the agency
            $recipient = $ticket->agency;
        } elseif ($ticket->client_id) {
            // If ticket is from client, recipient is the client
            $recipient = $ticket->client;
        }

        // Create the message
        $message = $ticket->sendMessage($sender, $recipient, $request->message);

        // Update ticket status to in_progress if admin replies
        if ($sender->role === 'admin' && $ticket->status === 'open') {
            $ticket->markAsInProgress();
        }

        // Create notification for admin when client/agency sends message
        if ($sender->role !== 'admin') {
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                \App\Models\Notification::createSupportMessageNotification($admin->id, $sender, $ticket, $request->message);
            }
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    /**
     * Get messages for a support ticket
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
     * Mark messages as read
     */
    public function markAsRead(Request $request, $ticketId)
    {
        $user = Auth::user();
        $ticket = SupportTicket::findOrFail($ticketId);

        $ticket->messages()
            ->where('recipient_id', $user->id)
            ->where('recipient_type', get_class($user))
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread messages count for current user
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = 0;

        if ($user->role === 'admin') {
            // Admin receives messages from agencies and clients
            $count = SupportMessage::whereHas('supportTicket', function($query) {
                    $query->where('status', '!=', 'closed');
                })
                ->where('recipient_id', $user->id)
                ->where('recipient_type', 'App\Models\User')
                ->where('is_read', false)
                ->count();
        } elseif ($user->role === 'agence') {
            // Agency receives messages from admin
            $agency = $user->agency;
            if ($agency) {
                $count = SupportMessage::whereHas('supportTicket', function($query) use ($agency) {
                        $query->where('agency_id', $agency->id);
                    })
                    ->where('recipient_id', $agency->id)
                    ->where('recipient_type', 'App\Models\Agency')
                    ->where('is_read', false)
                    ->count();
            }
        } elseif ($user->role === 'client') {
            // Client receives messages from admin
            $client = $user->client;
            if ($client) {
                $count = SupportMessage::whereHas('supportTicket', function($query) use ($client) {
                        $query->where('client_id', $client->id);
                    })
                    ->where('recipient_id', $client->id)
                    ->where('recipient_type', 'App\Models\Client')
                    ->where('is_read', false)
                    ->count();
            }
        }

        return response()->json(['count' => $count]);
    }

    /**
     * Create notification for message recipient
     */
    private function createNotification($recipient, $ticket, $message)
    {
        // Create notification record
        $notification = [
            'title' => 'Nouveau message de support',
            'message' => 'Vous avez reçu une nouvelle réponse à votre ticket #' . $ticket->ticket_number,
            'type' => 'support_message',
            'data' => [
                'ticket_id' => $ticket->id,
                'message_id' => $message->id,
                'sender_name' => $message->sender->name ?? $message->sender->nom ?? 'Administrateur',
            ],
            'created_at' => now(),
        ];

        // Store in session or database based on recipient type
        if ($recipient instanceof Agency) {
            // For agencies, we'll store in their session or database
            session()->push('agency_notifications.' . $recipient->id, $notification);
        } elseif ($recipient instanceof Client) {
            // For clients, we'll store in their session or database
            session()->push('client_notifications.' . $recipient->id, $notification);
        }
    }
}
