<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function latestItem()
    {
        return $this->hasOne(WishlistItem::class)->latestOfMany();
    }

    public function cars()
    {
        return $this->hasManyThrough(Car::class, WishlistItem::class, 'wishlist_id', 'id', 'id', 'car_id');
    }

    public function getCarsCountAttribute()
    {
        return $this->items()->count();
    }
}
