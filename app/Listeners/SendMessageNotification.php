<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Models\Notification;
use App\Models\ClientNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        $receiver = $event->receiver;
        $message = $event->message;

        // Créer une notification pour l'agence si c'est un client qui envoie
        if ($receiver->isAgence()) {
            Notification::create([
                'agency_id' => $receiver->agency->id,
                'type' => 'message',
                'title' => 'Nouveau message',
                'message' => 'Vous avez reçu un nouveau message de ' . $message->sender->name,
                'icon' => 'message',
                'icon_color' => 'blue',
                'action_url' => route('agence.messages.show', $message->rental),
                'related_id' => $message->id,
            ]);
        }
        
        // Créer une notification pour le client si c'est une agence qui envoie
        if ($receiver->isClient()) {
            ClientNotification::create([
                'user_id' => $receiver->id,
                'type' => 'message',
                'title' => 'Nouveau message',
                'message' => 'Vous avez reçu un nouveau message de ' . $message->sender->name,
                'icon' => 'message',
                'icon_color' => 'blue',
                'action_url' => route('client.messages.show', $message->rental),
                'related_id' => $message->id,
            ]);
        }
    }
}
