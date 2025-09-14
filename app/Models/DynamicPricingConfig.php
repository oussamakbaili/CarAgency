<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicPricingConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'enabled',
        'peak_hour_multiplier',
        'weekend_multiplier',
        'last_minute_multiplier',
        'demand_threshold',
        'peak_hours',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'peak_hour_multiplier' => 'decimal:2',
        'weekend_multiplier' => 'decimal:2',
        'last_minute_multiplier' => 'decimal:2',
        'peak_hours' => 'array',
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function calculateDynamicPrice($basePrice, $isPeakHour = false, $isWeekend = false, $isLastMinute = false): float
    {
        if (!$this->enabled) {
            return $basePrice;
        }

        $multiplier = 1.0;

        if ($isPeakHour) {
            $multiplier *= $this->peak_hour_multiplier;
        }

        if ($isWeekend) {
            $multiplier *= $this->weekend_multiplier;
        }

        if ($isLastMinute) {
            $multiplier *= $this->last_minute_multiplier;
        }

        return $basePrice * $multiplier;
    }
}
