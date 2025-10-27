<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'agency_id',
        'review_type',
        'rating',
        'comment',
        'status',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rating' => 'integer'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForCar($query, $carId)
    {
        return $query->where('car_id', $carId)->where('review_type', 'car');
    }

    public function scopeForAgency($query, $agencyId)
    {
        return $query->where('agency_id', $agencyId)->where('review_type', 'agency');
    }

    // Méthodes utilitaires
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    // Méthodes statiques pour calculer les moyennes
    public static function getAverageRatingForCar($carId)
    {
        return self::forCar($carId)
            ->approved()
            ->avg('rating') ?: 0;
    }

    public static function getAverageRatingForAgency($agencyId)
    {
        return self::forAgency($agencyId)
            ->approved()
            ->avg('rating') ?: 0;
    }

    public static function getReviewsCountForCar($carId)
    {
        return self::forCar($carId)->approved()->count();
    }

    public static function getReviewsCountForAgency($agencyId)
    {
        return self::forAgency($agencyId)->approved()->count();
    }
}