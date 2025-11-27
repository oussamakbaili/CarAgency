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
            
        $messages = $ticket->messages()
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

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
            'message' => 'nullable|string|max:2000',
            'message_type' => 'in:text,image,document',
            'attachments.*' => 'file|max:10240|mimes:jpeg,jpg,png,gif,pdf,doc,docx,txt',
        ]);

        // Vérifier qu'il y a soit un message, soit des fichiers
        if (empty($request->message) && !$request->hasFile('attachments')) {
            return response()->json([
                'success' => false,
                'errors' => ['message' => ['Le message est requis ou vous devez joindre un fichier.']]
            ], 422);
        }

        $client = auth()->user()->client;
        $ticket = SupportTicket::where('id', $ticketId)
            ->where('client_id', $client->id)
            ->firstOrFail();

        // Find admin users to send message to
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Aucun administrateur trouvé'], 400);
        }

        // Gérer l'upload des fichiers
        $attachments = [];
        if ($request->hasFile('attachments')) {
            $uploadedFiles = $request->file('attachments');
            
            if (is_array($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    if ($file && $file->isValid()) {
                        try {
                            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs('messages/attachments', $filename, 'public');
                            
                            $attachments[] = [
                                'name' => $file->getClientOriginalName(),
                                'path' => $path,
                                'size' => $file->getSize(),
                                'type' => $file->getMimeType(),
                                'url' => asset('storage/' . $path)
                            ];
                        } catch (\Exception $e) {
                            \Log::error('Erreur lors de l\'upload du fichier: ' . $e->getMessage());
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

        // Create the message
        $message = $ticket->sendMessage($client, $admin, $messageText, !empty($attachments) ? $attachments : null, $messageType);

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
        // Vérifier que l'utilisateur est authentifié et est un client
        if (!auth()->check() || !auth()->user()->isClient()) {
            return response()->json(['count' => 0], 401);
        }
        
        $client = auth()->user()->client;
        
        if (!$client) {
            return response()->json(['count' => 0], 404);
        }
        
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
