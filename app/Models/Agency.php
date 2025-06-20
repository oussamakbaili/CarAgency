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
    const MAX_TRIES = 3;

    protected $fillable = [
        'user_id',
        'agency_name',
        'responsable_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
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
        'tries_count'
    ];

    protected $casts = [
        'status' => 'string',
        'additional_docs' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
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

    public function getDocumentUrl($field)
    {
        if (!$this->$field) {
            return null;
        }
        return asset('storage/' . $this->$field);
    }
}
