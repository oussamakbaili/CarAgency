<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
     * Send a message in a support ticket (Agency view)
     */
    public function sendMessage(Request $request, $ticketId)
    {
        try {
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

            $agency = auth()->user()->agency;
            if (!$agency) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agence non trouvée'
                ], 400);
            }

            $ticket = SupportTicket::where('id', $ticketId)
                ->where('agency_id', $agency->id)
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
                
                // Gérer le cas où c'est un seul fichier ou un tableau
                if (!is_array($uploadedFiles)) {
                    $uploadedFiles = [$uploadedFiles];
                }
                
                foreach ($uploadedFiles as $file) {
                    if ($file && $file->isValid()) {
                        try {
                            // S'assurer que le dossier existe
                            $directory = 'messages/attachments';
                            if (!Storage::disk('public')->exists($directory)) {
                                Storage::disk('public')->makeDirectory($directory, 0755, true);
                            }
                            
                            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs($directory, $filename, 'public');
                            
                            if ($path) {
                                $attachments[] = [
                                    'name' => $file->getClientOriginalName(),
                                    'path' => $path,
                                    'size' => $file->getSize(),
                                    'type' => $file->getMimeType(),
                                    'url' => Storage::disk('public')->url($path)
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
            try {
                $message = $ticket->sendMessage($agency, $admin, $messageText, !empty($attachments) ? $attachments : null, $messageType);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la création du message: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du message: ' . $e->getMessage()
                ], 500);
            }

            // Update ticket status to open if it was closed
            if ($ticket->status === 'closed') {
                $ticket->reopen();
            }

            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur dans sendMessage (SupportMessageController): ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
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
