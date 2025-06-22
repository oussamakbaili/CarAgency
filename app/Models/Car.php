<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    const STATUS_AVAILABLE = 'available';
    const STATUS_RENTED = 'rented';
    const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'agency_id',
        'brand',
        'model',
        'registration_number',
        'year',
        'price_per_day',
        'description',
        'color',
        'fuel_type',
        'status',
        'image',
        'stock_quantity',
        'available_stock',
        'track_stock'
    ];

    protected $casts = [
        'year' => 'integer',
        'price_per_day' => 'decimal:2',
        'status' => 'string',
        'stock_quantity' => 'integer',
        'available_stock' => 'integer',
        'track_stock' => 'boolean',
    ];

    protected $appends = ['is_available'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isRented()
    {
        return $this->status === self::STATUS_RENTED;
    }

    public function isInMaintenance()
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function getIsAvailableAttribute()
    {
        if (!$this->track_stock) {
            return $this->status === self::STATUS_AVAILABLE;
        }
        
        return $this->status === self::STATUS_AVAILABLE && $this->available_stock > 0;
    }

    public function hasStock()
    {
        return !$this->track_stock || $this->available_stock > 0;
    }

    public function reserveStock($quantity = 1)
    {
        if (!$this->track_stock) {
            return true;
        }

        if ($this->available_stock >= $quantity) {
            $this->decrement('available_stock', $quantity);
            return true;
        }

        return false;
    }

    public function releaseStock($quantity = 1)
    {
        if (!$this->track_stock) {
            return true;
        }

        $this->increment('available_stock', $quantity);
        return true;
    }

    // Scope for available cars from approved agencies
    public function scopeAvailableFromApprovedAgencies($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)
                    ->whereHas('agency', function($q) {
                        $q->where('status', 'approved');
                    });
    }

    // Get the image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
