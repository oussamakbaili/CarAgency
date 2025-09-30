<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyCancellationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'rental_id',
        'cancellation_reason',
        'notes',
        'cancelled_at'
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}