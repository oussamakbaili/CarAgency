<?php

namespace App\Http\Controllers\Agence;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Afficher la liste des conversations (réservations avec messagerie activée)
     */
    public function index()
    {
        $user = Auth::user();
        $agency = $user->agency;
        
        // Récupérer les réservations actives de cette agence avec messages
        $rentals = Rental::where('agency_id', $agency->id)
            ->where('status', 'active')
            ->with(['car', 'user', 'messages' => function($query) use ($user) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Compter les messages non lus pour chaque réservation
        foreach ($rentals as $rental) {
            $rental->unread_count = $rental->getUnreadMessagesCountForUser($user->id, $user->getMessageType());
            $rental->last_message = $rental->messages->first();
        }

        return view('agence.messages.index', compact('rentals'));
    }

    /**
     * Afficher les messages d'une réservation spécifique
     */
    public function show(Rental $rental)
    {
        $user = Auth::user();
        $agency = $user->agency;
        
        // Vérifier que la réservation appartient à cette agence et que la messagerie est activée
        if ($rental->agency_id !== $agency->id || !$rental->isMessagingEnabled()) {
            abort(403, 'Accès non autorisé à cette conversation.');
        }

        // Charger la réservation avec ses relations
        $rental->load(['car', 'user.client', 'messages.sender', 'messages.receiver']);

        // Marquer tous les messages comme lus
        Message::where('rental_id', $rental->id)
            ->where('receiver_id', $user->id)
            ->where('receiver_type', $user->getMessageType())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('agence.messages.show', compact('rental'));
    }

    /**
     * Envoyer un nouveau message
     */
    public function store(Request $request, Rental $rental)
    {
        $user = Auth::user();
        $agency = $user->agency;
        
        // Vérifier que la réservation appartient à cette agence et que la messagerie est activée
        if ($rental->agency_id !== $agency->id || !$rental->isMessagingEnabled()) {
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
            'sender_type' => 'agency',
            'receiver_id' => $rental->user_id,
            'receiver_type' => 'client',
            'message' => $request->message,
            'message_type' => $request->message_type ?? 'text',
        ]);

        // Déclencher l'événement pour les notifications
        event(new \App\Events\MessageSent($message, $rental->user));

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
        if ($message->receiver_id !== $user->id || $message->receiver_type !== 'agency') {
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
        $agency = $user->agency;
        $lastMessageId = $request->get('last_message_id', 0);
        
        // Vérifier que la réservation appartient à cette agence
        if ($rental->agency_id !== $agency->id || !$rental->isMessagingEnabled()) {
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
            if ($message->receiver_id === $user->id && $message->receiver_type === 'agency') {
                $message->markAsRead();
            }
        }

        return response()->json([
            'messages' => $messages,
            'last_message_id' => $messages->max('id') ?? $lastMessageId,
        ]);
    }
}
