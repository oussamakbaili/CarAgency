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
    ];

    protected $casts = [
        'replies' => 'array',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relationships
    public function agency()
    {
        return $this->belongsTo(Agency::class);
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

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'technical' => 'Technique',
            'billing' => 'Facturation',
            'booking' => 'Réservation',
            'general' => 'Général',
            'complaint' => 'Plainte',
            default => 'Général'
        };
    }

    // Generate unique ticket number
    public static function generateTicketNumber()
    {
        do {
            $ticketNumber = 'TKT-' . strtoupper(uniqid());
        } while (self::where('ticket_number', $ticketNumber)->exists());
        
        return $ticketNumber;
    }
}
