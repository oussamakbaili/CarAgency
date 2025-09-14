<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonalRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'price_multiplier',
        'vehicle_ids',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_multiplier' => 'decimal:2',
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
               now()->between($this->start_date, $this->end_date);
    }
}
