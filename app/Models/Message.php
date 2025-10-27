<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'message',
        'is_read',
        'read_at',
        'attachments',
        'message_type',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'attachments' => 'array',
    ];

    /**
     * Relation avec la réservation
     */
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    /**
     * Relation polymorphe avec l'expéditeur
     */
    public function sender(): MorphTo
    {
        return $this->morphTo('sender', 'sender_type', 'sender_id');
    }

    /**
     * Relation polymorphe avec le destinataire
     */
    public function receiver(): MorphTo
    {
        return $this->morphTo('receiver', 'receiver_type', 'receiver_id');
    }

    /**
     * Marquer le message comme lu
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Vérifier si le message est récent (moins de 5 minutes)
     */
    public function isRecent(): bool
    {
        return $this->created_at->diffInMinutes(now()) < 5;
    }

    /**
     * Scope pour les messages non lus
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope pour les messages d'une réservation
     */
    public function scopeForRental($query, $rentalId)
    {
        return $query->where('rental_id', $rentalId);
    }

    /**
     * Obtenir le statut de lecture du message
     * 1. Pas de coche = Message envoyé, destinataire hors ligne
     * 2. Une coche grise = Message envoyé, destinataire en ligne mais pas lu
     * 3. Deux coches bleues = Message lu par le destinataire
     */
    public function getReadStatusAttribute()
    {
        $receiver = $this->receiver;
        
        // Si le message n'est pas lu
        if (!$this->is_read) {
            // Si le destinataire est en ligne
            if ($receiver && $receiver->isOnline()) {
                return 'delivered'; // Une coche grise
            } else {
                return 'sent'; // Pas de coche
            }
        }
        
        // Message lu
        return 'read'; // Deux coches bleues
    }
}
