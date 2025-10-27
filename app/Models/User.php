<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        "phone",
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAgence()
    {
        return $this->role === 'agence';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function agency()
    {
        return $this->hasOne(Agency::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Messages envoyés par cet utilisateur
     */
    public function sentMessages()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    /**
     * Messages reçus par cet utilisateur
     */
    public function receivedMessages()
    {
        return $this->morphMany(Message::class, 'receiver');
    }

    /**
     * Obtenir le type d'utilisateur pour les messages
     */
    public function getMessageType(): string
    {
        if ($this->isAdmin()) {
            return 'admin';
        } elseif ($this->isAgence()) {
            return 'agency';
        } elseif ($this->isClient()) {
            return 'client';
        }
        
        return 'user';
    }

    /**
     * Marquer l'utilisateur comme en ligne
     */
    public function markOnline(): void
    {
        $this->update(['last_seen_at' => now()]);
    }

    /**
     * Vérifier si l'utilisateur est en ligne (vu dans les 5 dernières minutes)
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) <= 5;
    }

    /**
     * Relation avec les notifications client
     */
    public function clientNotifications()
    {
        return $this->hasMany(ClientNotification::class);
    }
}
