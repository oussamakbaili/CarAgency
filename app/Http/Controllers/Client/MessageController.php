<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'message' => 'nullable|string|max:2000',
            'message_type' => 'in:text,image,document',
            'attachments.*' => 'file|max:10240|mimes:jpeg,jpg,png,gif,pdf,doc,docx,txt',
        ], [
            'message.required' => 'Le message est requis ou vous devez joindre un fichier.',
        ]);

        // Vérifier qu'il y a soit un message, soit des fichiers
        if (empty($request->message) && !$request->hasFile('attachments')) {
            return response()->json([
                'success' => false,
                'errors' => ['message' => ['Le message est requis ou vous devez joindre un fichier.']]
            ], 422);
        }

        // Gérer l'upload des fichiers
        $attachments = [];
        if ($request->hasFile('attachments')) {
            $uploadedFiles = $request->file('attachments');
            
            // Si c'est un tableau associatif (attachments[0], attachments[1], etc.)
            if (is_array($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    if ($file && $file->isValid()) {
                        try {
                            // S'assurer que le dossier existe
                            $directory = 'messages/attachments';
                            if (!Storage::disk('public')->exists($directory)) {
                                Storage::disk('public')->makeDirectory($directory);
                            }
                            
                            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs($directory, $filename, 'public');
                            
                            if ($path) {
                                $attachments[] = [
                                    'name' => $file->getClientOriginalName(),
                                    'path' => $path,
                                    'size' => $file->getSize(),
                                    'type' => $file->getMimeType(),
                                    'url' => asset('storage/' . $path)
                                ];
                            }
                        } catch (\Exception $e) {
                            \Log::error('Erreur lors de l\'upload du fichier: ' . $e->getMessage());
                            \Log::error('Stack trace: ' . $e->getTraceAsString());
                            // Retourner une erreur si l'upload échoue
                            return response()->json([
                                'success' => false,
                                'message' => 'Erreur lors de l\'upload du fichier: ' . $e->getMessage()
                            ], 500);
                        }
                    }
                }
            }
        }

        // Déterminer le type de message
        $messageType = 'text';
        if (!empty($attachments)) {
            $hasImage = collect($attachments)->contains(function($attachment) {
                return str_starts_with($attachment['type'], 'image/');
            });
            $messageType = $hasImage ? 'image' : 'document';
        }

        // Créer le message - s'assurer qu'il y a au moins un message ou des attachments
        $messageText = $request->message ?? '';
        if (empty($messageText) && empty($attachments)) {
            $messageText = 'Fichier joint'; // Message par défaut si seulement des fichiers
        }

        try {
            $message = Message::create([
                'rental_id' => $rental->id,
                'sender_id' => $user->id,
                'sender_type' => $user->getMessageType(),
                'receiver_id' => $rental->agency->user_id,
                'receiver_type' => 'agency',
                'message' => $messageText,
                'message_type' => $request->message_type ?? $messageType,
                'attachments' => !empty($attachments) ? $attachments : null,
            ]);

            // Déclencher l'événement pour les notifications (avec gestion d'erreur)
            try {
                event(new \App\Events\MessageSent($message, $rental->agency->user));
            } catch (\Exception $e) {
                \Log::warning('Erreur lors de l\'envoi de l\'événement MessageSent: ' . $e->getMessage());
                // Ne pas bloquer l'envoi du message si l'événement échoue
            }

            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du message: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du message: ' . $e->getMessage()
            ], 500);
        }
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

        // Si lastMessageId est 0, récupérer tous les messages
        $query = Message::where('rental_id', $rental->id);
        if ($lastMessageId > 0) {
            $query->where('id', '>', $lastMessageId);
        }
        
        $messages = $query->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages reçus comme lus
        foreach ($messages as $message) {
            if ($message->receiver_id === $user->id && $message->receiver_type === $user->getMessageType()) {
                $message->markAsRead();
            }
        }

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'last_message_id' => $messages->max('id') ?? $lastMessageId,
        ]);
    }
}
