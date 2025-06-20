<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'type',
        'description',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
} 