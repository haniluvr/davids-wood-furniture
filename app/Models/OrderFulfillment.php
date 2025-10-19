<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFulfillment extends Model
{
    protected $table = 'order_fulfillment';
    
    protected $fillable = [
        'order_id',
        'items_packed',
        'label_printed',
        'shipped',
        'carrier',
        'tracking_number',
        'packed_at',
        'shipped_at',
        'packing_notes',
        'shipping_notes',
        'packed_by',
        'shipped_by',
    ];

    protected $casts = [
        'items_packed' => 'boolean',
        'label_printed' => 'boolean',
        'shipped' => 'boolean',
        'packed_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function packedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'packed_by');
    }

    public function shippedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'shipped_by');
    }

    /**
     * Get fulfillment progress percentage
     */
    public function getProgressAttribute(): int
    {
        $steps = 0;
        $total = 3; // items_packed, label_printed, shipped

        if ($this->items_packed) $steps++;
        if ($this->label_printed) $steps++;
        if ($this->shipped) $steps++;

        return round(($steps / $total) * 100);
    }

    /**
     * Get fulfillment status
     */
    public function getStatusAttribute(): string
    {
        if ($this->shipped) return 'shipped';
        if ($this->label_printed) return 'ready_to_ship';
        if ($this->items_packed) return 'packed';
        return 'pending';
    }
}
