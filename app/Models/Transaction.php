<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const TYPE_RENTAL_PAYMENT = 'rental_payment';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_WITHDRAWAL_REQUEST = 'withdrawal_request';
    const TYPE_REFUND = 'refund';
    const TYPE_COMMISSION = 'commission';
    const TYPE_PENALTY = 'penalty';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'agency_id',
        'rental_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'metadata',
        'status',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Create a new transaction and update agency balance
     */
    public static function createTransaction($agencyId, $type, $amount, $description = null, $rentalId = null, $metadata = null)
    {
        $agency = Agency::findOrFail($agencyId);
        $balanceBefore = $agency->balance;
        $balanceAfter = $balanceBefore + $amount;

        $transaction = self::create([
            'agency_id' => $agencyId,
            'rental_id' => $rentalId,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'metadata' => $metadata,
            'status' => self::STATUS_COMPLETED,
            'processed_at' => now()
        ]);

        // Update agency balance
        $agency->update(['balance' => $balanceAfter]);

        return $transaction;
    }
}
