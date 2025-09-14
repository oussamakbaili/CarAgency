<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'name',
        'code',
        'type',
        'discount_value',
        'start_date',
        'end_date',
        'max_usage',
        'usage_count',
        'vehicle_ids',
        'is_active',
        'offer_type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_value' => 'decimal:2',
        'vehicle_ids' => 'array',
        'is_active' => 'boolean',
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function cars()
    {
        return Car::whereIn('id', $this->vehicle_ids ?? []);
    }

    public function isActive(): bool
    {
        return $this->is_active && 
               now()->between($this->start_date, $this->end_date) &&
               ($this->max_usage == 0 || $this->usage_count < $this->max_usage);
    }

    public function calculateDiscount($price): float
    {
        if ($this->type === 'percentage') {
            return $price * ($this->discount_value / 100);
        }
        
        return min($this->discount_value, $price);
    }
}
