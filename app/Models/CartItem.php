<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'product_name',
        'product_sku',
        'product_data',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'product_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guestSession(): BelongsTo
    {
        return $this->belongsTo(GuestSession::class, 'session_id', 'session_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if this cart item belongs to a guest.
     */
    public function isGuest(): bool
    {
        return $this->session_id !== null && $this->user_id === null;
    }

    /**
     * Check if this cart item belongs to a user.
     */
    public function isUser(): bool
    {
        return $this->user_id !== null && $this->session_id === null;
    }

    /**
     * Scope for guest cart items.
     */
    public function scopeForGuest($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId)->whereNull('user_id');
    }

    /**
     * Scope for user cart items.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId)->whereNull('session_id');
    }
}
