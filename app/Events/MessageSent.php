<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiver;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message, User $receiver)
    {
        $this->message = $message;
        $this->receiver = $receiver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->receiver->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'message' => $this->message->message,
                'sender_name' => $this->message->sender->name,
                'created_at' => $this->message->created_at->toISOString(),
            ],
            'notification' => [
                'title' => 'Nouveau message',
                'body' => 'Vous avez reçu un nouveau message de ' . $this->message->sender->name,
                'icon' => 'message',
                'url' => $this->getNotificationUrl(),
            ]
        ];
    }

    /**
     * Get the notification URL based on user type
     */
    private function getNotificationUrl(): string
    {
        if ($this->receiver->isClient()) {
            return route('client.messages.show', $this->message->rental);
        } elseif ($this->receiver->isAgence()) {
            return route('agence.messages.show', $this->message->rental);
        }
        
        return '#';
    }
}
