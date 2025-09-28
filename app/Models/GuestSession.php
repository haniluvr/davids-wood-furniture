<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GuestSession extends Model
{
    protected $table = 'guest_sessions';
    
    protected $primaryKey = 'session_id';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    public $timestamps = false; // Disable timestamps since we only have created_at

    protected $fillable = [
        'session_id',
        'created_at',
        'expires_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get cart items for this guest session
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'session_id', 'session_id');
    }

    /**
     * Get wishlist items for this guest session
     */
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class, 'session_id', 'session_id');
    }

    /**
     * Create a new guest session
     */
    public static function createSession(): self
    {
        $sessionId = Str::uuid()->toString();
        
        return self::create([
            'session_id' => $sessionId,
            'expires_at' => Carbon::now()->addDays(30), // 30 days expiration
        ]);
    }

    /**
     * Find or create guest session
     */
    public static function findOrCreateSession(string $sessionId): self
    {
        $session = self::find($sessionId);
        
        if (!$session) {
            $session = self::create([
                'session_id' => $sessionId,
                'expires_at' => Carbon::now()->addDays(30),
            ]);
        }
        
        return $session;
    }

    /**
     * Check if session is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Clean up expired sessions
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }
}