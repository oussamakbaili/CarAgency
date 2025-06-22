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
        'image'
    ];

    protected $casts = [
        'year' => 'integer',
        'price_per_day' => 'decimal:2',
        'status' => 'string',
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
        return $this->status === self::STATUS_AVAILABLE;
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
