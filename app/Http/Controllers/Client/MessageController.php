<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Afficher la liste des conversations (réservations + support)
     */
    public function index()
    {
        $user = Auth::user();
        $client = $user->client;
        
        // Récupérer les réservations actives avec messages
        $rentals = Rental::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['car', 'agency.user', 'messages' => function($query) use ($user) {
                $query->where('receiver_id', $user->id)
                      ->where('receiver_type', $user->getMessageType())
                      ->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Compter les messages non lus pour chaque réservation
        foreach ($rentals as $rental) {
            $rental->unread_count = $rental->getUnreadMessagesCountForUser($user->id, $user->getMessageType());
            $rental->last_message = $rental->messages->first();
            $rental->conversation_type = 'rental';
        }

        // Récupérer UNIQUEMENT les tickets de support créés par ce client
        // (où client_id correspond au client connecté et agency_id est null)
        $supportTickets = \App\Models\SupportTicket::where('client_id', $client->id)
            ->whereNull('agency_id') // S'assurer que c'est un ticket créé par le client, pas par une agence
            ->with(['messages' => function($query) use ($client) {
                $query->where('recipient_id', $client->id)
                      ->where('recipient_type', 'App\Models\Client')
                      ->orderBy('created_at', 'desc');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Compter les messages non lus pour chaque ticket
        foreach ($supportTickets as $ticket) {
            $ticket->unread_count = $ticket->getUnreadMessagesCount($client);
            $ticket->last_message = $ticket->messages->first();
            $ticket->conversation_type = 'support';
        }

        // Combiner et trier toutes les conversations par date de dernière activité
        $allConversations = collect()
            ->merge($rentals->map(function($rental) {
                return (object) [
                    'id' => 'rental_' . $rental->id,
                    'type' => 'rental',
                    'title' => $rental->car->brand . ' ' . $rental->car->model,
                    'subtitle' => $rental->agency->agency_name . ' • ' . $rental->start_date->format('d/m/Y') . ' - ' . $rental->end_date->format('d/m/Y'),
                    'unread_count' => $rental->unread_count,
                    'last_message' => $rental->last_message,
                    'last_activity' => $rental->last_message ? $rental->last_message->created_at : $rental->created_at,
                    'status' => 'active',
                    'image' => $rental->car->image_url,
                    'original' => $rental
                ];
            }))
            ->merge($supportTickets->map(function($ticket) {
                return (object) [
                    'id' => 'support_' . $ticket->id,
                    'type' => 'support',
                    'title' => $ticket->subject,
                    'subtitle' => 'Support • Ticket #' . $ticket->ticket_number . ' • ' . $ticket->created_at->format('d/m/Y'),
                    'unread_count' => $ticket->unread_count,
                    'last_message' => $ticket->last_message,
                    'last_activity' => $ticket->last_message ? $ticket->last_message->created_at : $ticket->created_at,
                    'status' => $ticket->status,
                    'image' => null,
                    'original' => $ticket
                ];
            }))
            ->sortByDesc('last_activity')
            ->values();

        return view('client.messages.index', compact('allConversations', 'rentals', 'supportTickets'));
    }

    /**
     * Afficher les messages d'une réservation spécifique
     */
    public function show(Rental $rental)
    {
        $user = Auth::user();
        
        // Vérifier que la réservation appartient au client et que la messagerie est activée
        if ($rental->user_id !== $user->id || !$rental->isMessagingEnabled()) {
            abort(403, 'Accès non autorisé à cette conversation.');
        }

        // Charger la réservation avec ses relations
        $rental->load(['car', 'agency.user', 'messages.sender', 'messages.receiver']);

        // Marquer tous les messages comme lus
        Message::where('rental_id', $rental->id)
            ->where('receiver_id', $user->id)
            ->where('receiver_type', $user->getMessageType())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('client.messages.show', compact('rental'));
    }

    /**
     * Envoyer un nouveau message
     */
    public function store(Request $request, Rental $rental)
    {
        $user = Auth::user();
        
        // Vérifier que la réservation appartient au client et que la messagerie est activée
        if ($rental->user_id !== $user->id || !$rental->isMessagingEnabled()) {
            abort(403, 'Impossible d\'envoyer un message pour cette réservation.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
            'message_type' => 'in:text,image,document',
        ]);

        // Créer le message
        $message = Message::create([
            'rental_id' => $rental->id,
            'sender_id' => $user->id,
            'sender_type' => $user->getMessageType(),
            'receiver_id' => $rental->agency->user_id,
            'receiver_type' => 'agency',
            'message' => $request->message,
            'message_type' => $request->message_type ?? 'text',
        ]);

        // Déclencher l'événement pour les notifications
        event(new \App\Events\MessageSent($message, $rental->agency->user));

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé avec succès.',
        ]);
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(Message $message)
    {
        $user = Auth::user();
        
        // Vérifier que le message est destiné à cet utilisateur
        if ($message->receiver_id !== $user->id || $message->receiver_type !== $user->getMessageType()) {
            abort(403, 'Accès non autorisé à ce message.');
        }

        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme lu.',
        ]);
    }

    /**
     * Obtenir les nouveaux messages (pour AJAX)
     */
    public function getNewMessages(Rental $rental, Request $request)
    {
        $user = Auth::user();
        $lastMessageId = $request->get('last_message_id', 0);
        
        // Vérifier que la réservation appartient au client
        if ($rental->user_id !== $user->id || !$rental->isMessagingEnabled()) {
            abort(403, 'Accès non autorisé à cette conversation.');
        }

        // Récupérer les nouveaux messages
        $messages = Message::where('rental_id', $rental->id)
            ->where('id', '>', $lastMessageId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages reçus comme lus
        foreach ($messages as $message) {
            if ($message->receiver_id === $user->id && $message->receiver_type === $user->getMessageType()) {
                $message->markAsRead();
            }
        }

        return response()->json([
            'messages' => $messages,
            'last_message_id' => $messages->max('id') ?? $lastMessageId,
        ]);
    }
}
