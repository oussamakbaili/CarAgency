<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SUSPENDED = 'suspended';
    const MAX_TRIES = 3;
    const MAX_CANCELLATIONS = 3;

    protected $fillable = [
        'user_id',
        'agency_name',
        'registration_number',
        'description',
        'responsable_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'opening_hours',
        'documents',
        'profile_picture',
        'tax_number',
        'commercial_register_number',
        'responsable_phone',
        'responsable_position',
        'responsable_identity_number',
        'commercial_register_doc',
        'identity_doc',
        'tax_doc',
        'additional_docs',
        'years_in_business',
        'business_description',
        'estimated_fleet_size',
        'status',
        'rejection_reason',
        'tries_count',
        'balance',
        'total_earnings',
        'pending_earnings',
        'last_payout_at',
        'commission_rate',
        'cancellation_count',
        'last_cancellation_at',
        'is_suspended',
        'suspended_at',
        'suspension_reason',
        'max_cancellations'
    ];

    protected $casts = [
        'status' => 'string',
        'additional_docs' => 'array',
        'opening_hours' => 'array',
        'documents' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'balance' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'pending_earnings' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'last_payout_at' => 'datetime',
        'last_cancellation_at' => 'datetime',
        'is_suspended' => 'boolean',
        'suspended_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function cancellationLogs()
    {
        return $this->hasMany(AgencyCancellationLog::class);
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isSuspended()
    {
        return $this->is_suspended || $this->status === self::STATUS_SUSPENDED;
    }

    public function canCancelBooking()
    {
        return !$this->isSuspended() && $this->cancellation_count < $this->max_cancellations;
    }

    public function getCancellationWarningMessage()
    {
        $remaining = $this->max_cancellations - $this->cancellation_count;
        
        if ($remaining <= 0) {
            return "Votre compte a été suspendu pour trop d'annulations. Contactez l'administrateur.";
        }
        
        if ($remaining == 1) {
            return "ATTENTION: Vous avez annulé " . $this->cancellation_count . " réservation(s). Une annulation de plus entraînera la suspension de votre compte.";
        }
        
        if ($remaining == 2) {
            return "Attention: Vous avez annulé " . $this->cancellation_count . " réservation(s). " . $remaining . " annulation(s) restante(s) avant suspension.";
        }
        
        return null;
    }

    public function incrementCancellationCount($rentalId, $reason = null, $notes = null)
    {
        $this->cancellation_count++;
        $this->last_cancellation_at = now();
        
        // Log the cancellation
        $this->cancellationLogs()->create([
            'rental_id' => $rentalId,
            'cancellation_reason' => $reason,
            'notes' => $notes,
            'cancelled_at' => now()
        ]);
        
        // Check if suspension is needed
        if ($this->cancellation_count >= $this->max_cancellations) {
            $this->suspend('Trop d\'annulations de réservations');
        }
        
        $this->save();
    }

    public function suspend($reason = 'Suspension administrative')
    {
        $this->is_suspended = true;
        $this->suspended_at = now();
        $this->suspension_reason = $reason;
        $this->status = self::STATUS_SUSPENDED;
        $this->save();
    }

    public function unsuspend()
    {
        $this->is_suspended = false;
        $this->suspended_at = null;
        $this->suspension_reason = null;
        $this->status = self::STATUS_APPROVED;
        $this->cancellation_count = 0; // Reset cancellation count
        $this->save();
    }

    public function getDocumentUrl($field)
    {
        if (!$this->$field) {
            return null;
        }
        return asset('storage/' . $this->$field);
    }
}
