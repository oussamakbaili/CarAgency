<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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

    public function externalSites()
    {
        return $this->hasMany(CarExternalSite::class);
    }

    public function activeExternalSites()
    {
        return $this->hasMany(CarExternalSite::class)->where('is_active', true);
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

    public function getIsAvailableAttribute($value)
    {
        // If the attribute was already set (e.g. controller pre-computed it), reuse it
        if (!is_null($value)) {
            return (bool) $value;
        }

        // Vérifier d'abord le statut de base
        if ($this->status !== self::STATUS_AVAILABLE) {
            return false;
        }

        // Si le stock est suivi, vérifier qu'il y a du stock disponible
        if ($this->track_stock && $this->available_stock <= 0) {
            return false;
        }

        // Vérifier s'il y a des réservations pending ou active pour aujourd'hui ou dans le futur
        // Une réservation pending bloque la voiture jusqu'à ce qu'elle soit complétée ou annulée
        // Les réservations rejected, cancelled ou completed ne bloquent pas la disponibilité
        $hasActiveReservations = $this->rentals()
            ->whereIn('status', ['pending', 'active'])
            ->where('end_date', '>=', now()->startOfDay())
            ->exists();

        // Si une réservation est en cours (pending ou active), la voiture n'est pas disponible
        if ($hasActiveReservations) {
            return false;
        }

        // Vérifier la disponibilité sur les sites externes (seulement si la table existe)
        if (Schema::hasTable('car_external_sites')) {
            try {
                if ($this->activeExternalSites()->exists()) {
                    $externalAvailabilityService = new \App\Services\ExternalSiteAvailabilityService();
                    $externalCheck = $externalAvailabilityService->checkExternalSitesAvailability($this);
                    
                    // Le véhicule est disponible seulement s'il est disponible sur TOUS les sites externes
                    return $externalCheck['available'] ?? false;
                }
            } catch (\Exception $e) {
                // En cas d'erreur lors de la vérification des sites externes, 
                // on considère la voiture comme disponible localement
                \Log::warning('Error checking external sites availability for car ' . $this->id . ': ' . $e->getMessage());
                return true;
            }
        }

        // Si pas de sites externes, la disponibilité locale suffit
        return true;
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
        if (!$this->image) {
            return null;
        }

        $imagePath = $this->normalizeStoragePath($this->image);

        // Check if file exists in public disk
        if (Storage::disk('public')->exists($imagePath)) {
            return '/public/storage/' . $imagePath;
        }

        // Check if file exists in default disk
        if (Storage::exists($imagePath)) {
            return '/public/storage/' . $imagePath;
        }

        // Check if file exists in storage/app/public (absolute path check)
        $absolutePath = storage_path('app/public/' . $imagePath);
        if (file_exists($absolutePath)) {
            return '/public/storage/' . $imagePath;
        }

        // File doesn't exist - return null or placeholder
        // You can return a placeholder image URL here if needed
        // return asset('images/placeholder-car.jpg');
        return null;
    }

    // Get picture URLs
    public function getPictureUrlsAttribute()
    {
        if (!$this->pictures) {
            return [];
        }
        
        return collect($this->pictures)->map(function($picture) {
            $picturePath = $this->normalizeStoragePath($picture);

            // Check if file exists in public disk
            if (Storage::disk('public')->exists($picturePath)) {
                return '/public/storage/' . $picturePath;
            }

            // Check if file exists in default disk
            if (Storage::exists($picturePath)) {
                return '/public/storage/' . $picturePath;
            }

            // Check if file exists in storage/app/public (absolute path check)
            $absolutePath = storage_path('app/public/' . $picturePath);
            if (file_exists($absolutePath)) {
                return '/public/storage/' . $picturePath;
            }

            // File doesn't exist - return null
            return null;
        })->filter()->values()->toArray(); // Remove null values and reindex
    }

    protected function normalizeStoragePath(string $path): string
    {
        // Accept values stored as "cars/...", "public/cars/..." or "app/public/cars/..."
        $normalized = preg_replace('/^(app\/)?public\//', '', $path);
        // Convert Windows-style separators to URL-friendly slashes
        $normalized = str_replace('\\', '/', $normalized);
        return ltrim($normalized, '/');
    }

    // Get client price with 15% commission
    public function getClientPricePerDayAttribute()
    {
        $markupRate = config('app.client_markup_rate', 15);
        return round($this->price_per_day * (1 + ($markupRate / 100)), 2);
    }
}
