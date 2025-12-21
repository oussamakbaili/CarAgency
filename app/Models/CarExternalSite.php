<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarExternalSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'site_name',
        'site_url',
        'api_url',
        'api_token',
        'external_car_id',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'api_token', // Ne pas exposer le token dans les réponses JSON
    ];

    /**
     * Relation avec le véhicule
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
