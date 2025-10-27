<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'sender_id',
        'sender_type',
        'recipient_id',
        'recipient_type',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the support ticket that owns the message
     */
    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    /**
     * Get the sender (admin, agency, or client)
     */
    public function sender()
    {
        return $this->morphTo('sender');
    }

    /**
     * Get the recipient (admin, agency, or client)
     */
    public function recipient()
    {
        return $this->morphTo('recipient');
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for messages to specific user
     */
    public function scopeToUser($query, $user)
    {
        return $query->where('recipient_id', $user->id)
                    ->where('recipient_type', get_class($user));
    }

    /**
     * Scope for messages from specific user
     */
    public function scopeFromUser($query, $user)
    {
        return $query->where('sender_id', $user->id)
                    ->where('sender_type', get_class($user));
    }

    /**
     * Get sender name and type
     */
    public function getSenderInfoAttribute()
    {
        if ($this->sender_type === 'App\Models\User') {
            return [
                'name' => $this->sender->name,
                'type' => 'admin',
                'avatar' => 'A',
                'color' => 'orange'
            ];
        } elseif ($this->sender_type === 'App\Models\Agency') {
            return [
                'name' => $this->sender->nom,
                'type' => 'agency',
                'avatar' => 'A',
                'color' => 'purple'
            ];
        } elseif ($this->sender_type === 'App\Models\Client') {
            return [
                'name' => $this->sender->user->name,
                'type' => 'client',
                'avatar' => 'C',
                'color' => 'blue'
            ];
        }

        return [
            'name' => 'Système',
            'type' => 'system',
            'avatar' => 'S',
            'color' => 'gray'
        ];
    }
}
