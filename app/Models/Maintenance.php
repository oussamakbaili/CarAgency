<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'agency_id',
        'title',
        'description',
        'type',
        'status',
        'scheduled_date',
        'start_date',
        'end_date',
        'cost',
        'garage_name',
        'garage_contact',
        'notes',
        'mileage_at_service',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'cost' => 'decimal:2',
        'mileage_at_service' => 'integer',
    ];

    // Relationships
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'orange',
            'in_progress' => 'red',
            'completed' => 'green',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'routine' => 'Routine',
            'repair' => 'Réparation',
            'inspection' => 'Inspection',
            'emergency' => 'Urgence',
            'other' => 'Autre',
            default => 'Inconnu',
        };
    }
}
