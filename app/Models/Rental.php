<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'active'; // Using 'active' to match database enum
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'rejected'; // Using 'rejected' for cancelled to match database enum

    protected $fillable = [
        'user_id',
        'car_id',
        'agency_id',
        'start_date',
        'end_date',
        'total_price',
        'status', // pending, approved, active, completed, rejected, cancelled
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'user_id', 'user_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Vérifier si la messagerie est activée (réservation approuvée)
     */
    public function isMessagingEnabled(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Obtenir le nombre de messages non lus pour un utilisateur
     */
    public function getUnreadMessagesCountForUser($userId, $userType): int
    {
        return $this->messages()
            ->where('receiver_id', $userId)
            ->where('receiver_type', $userType)
            ->unread()
            ->count();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
} 