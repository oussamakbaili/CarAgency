<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'cin',
        'birthday',
        'phone',
        'address',
        'city',
        'postal_code',
        'date_of_birth',
        'driving_license_number',
        'driving_license_expiry',
        'profile_picture',
        'preferences',
        'documents',
        'bio',
        'occupation',
        'company',
        'nationality',
        'gender',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
    ];

    protected $casts = [
        'birthday' => 'date',
        'date_of_birth' => 'date',
        'driving_license_expiry' => 'date',
        'preferences' => 'array',
        'documents' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'user_id', 'user_id');
    }
}
