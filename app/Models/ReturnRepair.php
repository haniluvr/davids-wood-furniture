<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRepair extends Model
{
    protected $table = 'returns_repairs';

    protected $fillable = [
        'rma_number',
        'order_id',
        'user_id',
        'type',
        'status',
        'reason',
        'description',
        'products',
        'refund_amount',
        'refund_method',
        'admin_notes',
        'customer_notes',
        'photos',
        'approved_at',
        'received_at',
        'completed_at',
        'processed_by',
    ];

    protected $casts = [
        'products' => 'array',
        'photos' => 'array',
        'refund_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'processed_by');
    }

    /**
     * Generate a unique RMA number in format: RMA-YYYY-NNNN
     */
    public static function generateRmaNumber(): string
    {
        $year = date('Y');
        $maxAttempts = 100;

        for ($i = 0; $i < $maxAttempts; $i++) {
            // Generate 4-character alphanumeric code
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
            $rmaNumber = "RMA-{$year}-{$code}";

            if (! self::where('rma_number', $rmaNumber)->exists()) {
                return $rmaNumber;
            }
        }

        // Fallback
        $code = strtoupper(substr(base_convert(time(), 10, 36), -4));

        return "RMA-{$year}-{$code}";
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'requested' => 'yellow',
            'approved' => 'blue',
            'received' => 'purple',
            'processing' => 'indigo',
            'repaired' => 'green',
            'refunded' => 'emerald',
            'completed' => 'green',
            'rejected' => 'red',
            default => 'gray'
        };
    }
}
