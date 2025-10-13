<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WishlistItem extends Model
{
    const UPDATED_AT = null; // Only created_at column exists
    
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
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
     * Check if this wishlist item belongs to a guest
     */
    public function isGuest(): bool
    {
        return $this->session_id !== null && $this->user_id === null;
    }

    /**
     * Check if this wishlist item belongs to a user
     */
    public function isUser(): bool
    {
        return $this->user_id !== null && $this->session_id === null;
    }

    /**
     * Scope for guest wishlist items
     */
    public function scopeForGuest($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId)->whereNull('user_id');
    }

    /**
     * Scope for user wishlist items
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId)->whereNull('session_id');
    }
}