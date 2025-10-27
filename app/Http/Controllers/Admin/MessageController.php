<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Rental;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Afficher la liste des tickets de support (clients et agences)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer TOUS les tickets de support avec messages (clients et agences)
        $supportTickets = SupportTicket::with(['client.user', 'agency.user', 'messages' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->orderBy('updated_at', 'desc')
        ->get();

        // Compter les messages non lus pour chaque ticket
        foreach ($supportTickets as $ticket) {
            $ticket->unread_count = $ticket->getUnreadMessagesCount($user);
            $ticket->last_message = $ticket->messages->first();
            $ticket->conversation_type = 'support';
        }

        // Créer les conversations à partir des tickets de support uniquement
        $allConversations = $supportTickets->map(function($ticket) {
            // Déterminer le type d'utilisateur
            $userType = $ticket->client ? 'Client' : 'Agence';
            $userName = $ticket->client ? $ticket->client->user->name : $ticket->agency->agency_name;
            
            return (object) [
                'id' => $ticket->id,
                'type' => 'support',
                'title' => $ticket->subject,
                'subtitle' => 'Support • Ticket #' . $ticket->ticket_number . ' • ' . $userType . ': ' . $userName . ' • ' . $ticket->created_at->format('d/m/Y'),
                'unread_count' => $ticket->unread_count,
                'last_message' => $ticket->last_message,
                'last_activity' => $ticket->last_message ? $ticket->last_message->created_at : $ticket->created_at,
                'status' => $ticket->status,
                'user_type' => $userType,
                'image' => null,
                'original' => $ticket
            ];
        })
        ->sortByDesc('last_activity')
        ->values();

        return view('admin.messages.index', compact('allConversations', 'supportTickets'));
    }

    /**
     * Afficher les messages d'un ticket de support spécifique
     */
    public function showSupport($ticketId)
    {
        $user = Auth::user();
        
        // Récupérer le ticket de support
        $ticket = SupportTicket::with(['client.user', 'agency.user', 'messages.sender', 'messages.recipient'])
            ->findOrFail($ticketId);

        // Marquer tous les messages comme lus pour cet admin
        \App\Models\SupportMessage::where('support_ticket_id', $ticket->id)
            ->where('recipient_id', $user->id)
            ->where('recipient_type', 'App\Models\User')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('admin.messages.show-support', compact('ticket'));
    }
}
