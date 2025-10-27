<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'agency_id',
        'rental_id',
        'ticket_number',
        'subject',
        'message',
        'description',
        'priority',
        'status',
        'category',
        'replies',
        'resolved_at',
        'closed_at',
        'assigned_to',
        'last_reply_by',
        'last_reply_at',
    ];

    protected $casts = [
        'replies' => 'array',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'last_reply_at' => 'datetime',
    ];

    // Relationships
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function lastReplyBy()
    {
        return $this->belongsTo(User::class, 'last_reply_by');
    }

    // Scopes
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeForAgency($query, $agencyId)
    {
        return $query->where('agency_id', $agencyId);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'urgent' => 'text-red-600 bg-red-100',
            'high' => 'text-orange-600 bg-orange-100',
            'medium' => 'text-yellow-600 bg-yellow-100',
            'low' => 'text-green-600 bg-green-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }

    public function getPriorityBadgeAttribute()
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800 border-red-200',
            'high' => 'bg-orange-100 text-orange-800 border-orange-200',
            'medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'low' => 'bg-green-100 text-green-800 border-green-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'urgent' => 'Urgent',
            'high' => 'Haute',
            'medium' => 'Moyenne',
            'low' => 'Basse',
            default => 'Moyenne'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'text-blue-600 bg-blue-100',
            'in_progress' => 'text-yellow-600 bg-yellow-100',
            'resolved' => 'text-green-600 bg-green-100',
            'closed' => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'open' => 'bg-blue-100 text-blue-800 border-blue-200',
            'in_progress' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'resolved' => 'bg-green-100 text-green-800 border-green-200',
            'closed' => 'bg-gray-100 text-gray-800 border-gray-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'open' => 'Ouvert',
            'in_progress' => 'En cours',
            'resolved' => 'Résolu',
            'closed' => 'Fermé',
            default => 'Ouvert'
        };
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'technical' => 'Technique',
            'billing' => 'Facturation',
            'booking' => 'Réservation',
            'general' => 'Général',
            'complaint' => 'Plainte',
            'account' => 'Compte',
            default => 'Général'
        };
    }

    public function getCategoryIconAttribute()
    {
        return match($this->category) {
            'technical' => '🔧',
            'billing' => '💰',
            'booking' => '📅',
            'general' => '📝',
            'complaint' => '⚠️',
            'account' => '👤',
            default => '📝'
        };
    }

    public function getUserTypeAttribute()
    {
        if ($this->client_id) {
            return 'client';
        } elseif ($this->agency_id) {
            return 'agency';
        }
        return 'unknown';
    }

    public function getUserNameAttribute()
    {
        if ($this->client_id && $this->client) {
            return $this->client->nom . ' ' . $this->client->prenom;
        } elseif ($this->agency_id && $this->agency) {
            return $this->agency->nom;
        }
        return 'Utilisateur inconnu';
    }

    // Methods
    public function addReply($message, $userId, $userType = 'admin')
    {
        $replies = $this->replies ?? [];
        $replies[] = [
            'user_id' => $userId,
            'user_type' => $userType,
            'message' => $message,
            'created_at' => now()->toISOString(),
        ];

        $this->update([
            'replies' => $replies,
            'last_reply_by' => $userId,
            'last_reply_at' => now(),
        ]);
    }

    public function markAsInProgress()
    {
        $this->update(['status' => 'in_progress']);
    }

    public function markAsResolved()
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function markAsClosed()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    public function reopen()
    {
        $this->update([
            'status' => 'open',
            'resolved_at' => null,
            'closed_at' => null,
        ]);
    }

    // Generate unique ticket number
    public static function generateTicketNumber()
    {
        do {
            $ticketNumber = 'TKT-' . strtoupper(uniqid());
        } while (self::where('ticket_number', $ticketNumber)->exists());
        
        return $ticketNumber;
    }

    /**
     * Get all messages for this support ticket
     */
    public function messages()
    {
        return $this->hasMany(SupportMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get unread messages count for a specific user
     */
    public function getUnreadMessagesCount($user)
    {
        return $this->messages()
            ->where('recipient_id', $user->id)
            ->where('recipient_type', get_class($user))
            ->where('is_read', false)
            ->count();
    }

    /**
     * Send a message in this support ticket
     */
    public function sendMessage($sender, $recipient, $message)
    {
        return $this->messages()->create([
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'recipient_id' => $recipient->id,
            'recipient_type' => get_class($recipient),
            'message' => $message,
            'is_read' => false,
        ]);
    }
}
