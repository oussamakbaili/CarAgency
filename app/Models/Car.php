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
        'category_id',
        'color',
        'fuel_type',
        'status',
        'image',
        'pictures',
        'stock_quantity',
        'available_stock',
        'track_stock',
        'maintenance_due',
        'last_maintenance',
        'mileage',
        'transmission',
        'seats',
        'engine_size',
        'features',
        'featured',
        'show_on_homepage',
        'homepage_priority'
    ];

    protected $casts = [
        'year' => 'integer',
        'price_per_day' => 'decimal:2',
        'status' => 'string',
        'stock_quantity' => 'integer',
        'available_stock' => 'integer',
        'track_stock' => 'boolean',
        'maintenance_due' => 'date',
        'last_maintenance' => 'date',
        'mileage' => 'integer',
        'seats' => 'integer',
        'features' => 'array',
        'pictures' => 'array',
        'featured' => 'boolean',
        'show_on_homepage' => 'boolean',
        'homepage_priority' => 'integer',
        'featured_at' => 'datetime',
    ];

    protected $appends = ['is_available'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function avis()
    {
        return $this->hasManyThrough(\App\Models\Avis::class, Rental::class, 'car_id', 'rental_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->approved();
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

    // Méthodes pour les avis
    public function getAverageRating()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    public function getReviewsCount()
    {
        return $this->approvedReviews()->count();
    }

    public function getTotalReviews()
    {
        return \App\Models\Avis::whereHas('rental', function($query) {
            $query->where('car_id', $this->id);
        })->where('is_public', true)->count();
    }

    public function getRatingDistribution()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = \App\Models\Avis::whereHas('rental', function($query) {
                $query->where('car_id', $this->id);
            })->where('is_public', true)->where('rating', $i)->count();
        }
        return $distribution;
    }

    public function getRecentReviews($limit = 5)
    {
        return \App\Models\Avis::whereHas('rental', function($query) {
            $query->where('car_id', $this->id);
        })
            ->where('is_public', true)
            ->with(['client.user', 'rental'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
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

    // Scope for featured cars
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    // Scope for homepage cars
    public function scopeShowOnHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }

    // Scope to order by homepage priority
    public function scopeOrderByPriority($query)
    {
        return $query->orderBy('homepage_priority', 'desc')->orderBy('created_at', 'desc');
    }

    // Get the image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // Get picture URLs
    public function getPictureUrlsAttribute()
    {
        if (!$this->pictures) {
            return [];
        }
        
        return collect($this->pictures)->map(function($picture) {
            return asset('storage/' . $picture);
        })->toArray();
    }
}
