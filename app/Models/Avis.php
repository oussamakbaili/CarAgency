<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'client_id',
        'agency_id',
        'rating',
        'comment',
        'title',
        'is_verified',
        'is_public',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_public' => 'boolean',
        'rating' => 'integer',
    ];

    // Relationships
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Client::class, 'id', 'id', 'client_id', 'user_id');
    }

    // Accessors
    public function getRatingStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getRatingColorAttribute()
    {
        return match($this->rating) {
            1, 2 => 'text-red-500',
            3 => 'text-yellow-500',
            4, 5 => 'text-green-500',
            default => 'text-gray-500'
        };
    }

    public function getShortCommentAttribute()
    {
        return strlen($this->comment) > 100 ? substr($this->comment, 0, 100) . '...' : $this->comment;
    }
}
