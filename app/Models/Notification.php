<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'admin_id',
        'client_id',
        'rental_id',
        'transaction_id',
        'type',
        'category',
        'priority',
        'title',
        'message',
        'icon',
        'icon_color',
        'action_url',
        'related_id',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'data' => 'array',
    ];

    /**
     * Get the agency that owns the notification.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the admin that owns the notification.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the client that owns the notification.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the rental related to the notification.
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    /**
     * Get the transaction related to the notification.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get the time ago format.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->locale('fr')->diffForHumans();
    }

    /**
     * Get icon SVG path.
     */
    public function getIconSvgAttribute()
    {
        $icons = [
            'bell' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            'car' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16v6 M7 5l3 2 M14 3h3v5h-3V3z',
            'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'money' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
            'check' => 'M5 13l4 4L19 7',
            'alert' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'message' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        ];

        return $icons[$this->icon] ?? $icons['bell'];
    }

    /**
     * Get icon color classes.
     */
    public function getIconColorClassAttribute()
    {
        $colors = [
            'blue' => 'bg-blue-100 text-blue-600',
            'green' => 'bg-green-100 text-green-600',
            'orange' => 'bg-orange-100 text-orange-600',
            'red' => 'bg-red-100 text-red-600',
            'purple' => 'bg-purple-100 text-purple-600',
            'yellow' => 'bg-yellow-100 text-yellow-600',
        ];

        return $colors[$this->icon_color] ?? $colors['blue'];
    }

    /**
     * Scope to get unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get recent notifications.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Scope to get notifications for admin.
     */
    public function scopeForAdmin($query, $adminId = null)
    {
        return $query->where('admin_id', $adminId ?? auth()->id());
    }

    /**
     * Scope to get notifications by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Create a support message notification for admin.
     */
    public static function createSupportMessageNotification($adminId, $sender, $ticket, $message)
    {
        $senderType = $sender instanceof Agency ? 'agence' : 'client';
        $senderName = $sender instanceof Agency ? $sender->nom : $sender->user->name;
        
        return self::create([
            'admin_id' => $adminId,
            'agency_id' => $sender instanceof Agency ? $sender->id : null,
            'client_id' => $sender instanceof Client ? $sender->id : null,
            'category' => 'support',
            'priority' => $ticket->priority,
            'type' => 'support_message',
            'title' => 'Nouveau message de support',
            'message' => "Message reçu de {$senderName} pour le ticket #{$ticket->ticket_number}",
            'icon' => 'message',
            'icon_color' => 'blue',
            'action_url' => route('admin.support.show', $ticket->id),
            'related_id' => $ticket->id,
            'data' => [
                'sender_type' => $senderType,
                'sender_name' => $senderName,
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'message_preview' => substr($message, 0, 100),
            ],
        ]);
    }

    /**
     * Create a new reservation notification for admin.
     */
    public static function createReservationNotification($adminId, $rental, $type = 'new')
    {
        $types = [
            'new' => ['title' => 'Nouvelle réservation', 'icon' => 'calendar', 'color' => 'green'],
            'cancelled' => ['title' => 'Réservation annulée', 'icon' => 'calendar', 'color' => 'red'],
            'completed' => ['title' => 'Réservation terminée', 'icon' => 'check', 'color' => 'blue'],
        ];

        $config = $types[$type] ?? $types['new'];

        return self::create([
            'admin_id' => $adminId,
            'client_id' => $rental->client_id,
            'rental_id' => $rental->id,
            'category' => 'reservation',
            'priority' => 'medium',
            'type' => "reservation_{$type}",
            'title' => $config['title'],
            'message' => "Réservation #{$rental->id} - {$rental->car->marque} {$rental->car->modele}",
            'icon' => $config['icon'],
            'icon_color' => $config['color'],
            'action_url' => route('admin.bookings.show', $rental->id),
            'related_id' => $rental->id,
            'data' => [
                'rental_id' => $rental->id,
                'client_name' => $rental->client->user->name,
                'car_info' => "{$rental->car->marque} {$rental->car->modele}",
                'total_amount' => $rental->total_amount,
                'start_date' => $rental->start_date,
                'end_date' => $rental->end_date,
            ],
        ]);
    }

    /**
     * Create a payment notification for admin.
     */
    public static function createPaymentNotification($adminId, $transaction, $type = 'received')
    {
        $types = [
            'received' => ['title' => 'Paiement reçu', 'icon' => 'money', 'color' => 'green'],
            'failed' => ['title' => 'Échec de paiement', 'icon' => 'alert', 'color' => 'red'],
            'refunded' => ['title' => 'Remboursement effectué', 'icon' => 'money', 'color' => 'orange'],
        ];

        $config = $types[$type] ?? $types['received'];

        return self::create([
            'admin_id' => $adminId,
            'transaction_id' => $transaction->id,
            'client_id' => $transaction->client_id,
            'rental_id' => $transaction->rental_id,
            'category' => 'payment',
            'priority' => $type === 'failed' ? 'high' : 'medium',
            'type' => "payment_{$type}",
            'title' => $config['title'],
            'message' => "Paiement de {$transaction->amount} MAD - {$transaction->description}",
            'icon' => $config['icon'],
            'icon_color' => $config['color'],
            'action_url' => route('admin.finance.transactions.show', $transaction->id),
            'related_id' => $transaction->id,
            'data' => [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
                'client_name' => $transaction->client->user->name,
            ],
        ]);
    }

    /**
     * Create an agency notification for admin.
     */
    public static function createAgencyNotification($adminId, $agency, $type = 'registration')
    {
        $types = [
            'registration' => ['title' => 'Nouvelle inscription d\'agence', 'icon' => 'user', 'color' => 'blue'],
            'approved' => ['title' => 'Agence approuvée', 'icon' => 'check', 'color' => 'green'],
            'rejected' => ['title' => 'Agence rejetée', 'icon' => 'alert', 'color' => 'red'],
            'suspended' => ['title' => 'Agence suspendue', 'icon' => 'alert', 'color' => 'orange'],
        ];

        $config = $types[$type] ?? $types['registration'];

        return self::create([
            'admin_id' => $adminId,
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => $type === 'registration' ? 'high' : 'medium',
            'type' => "agency_{$type}",
            'title' => $config['title'],
            'message' => "Agence : {$agency->nom} - {$agency->adresse}",
            'icon' => $config['icon'],
            'icon_color' => $config['color'],
            'action_url' => route('admin.agencies.show', $agency->id),
            'related_id' => $agency->id,
            'data' => [
                'agency_id' => $agency->id,
                'agency_name' => $agency->nom,
                'agency_email' => $agency->email,
                'agency_phone' => $agency->telephone,
                'status' => $agency->status,
            ],
        ]);
    }
}

