<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_order_amount',
        'maximum_uses',
        'used_count',
        'maximum_uses_per_customer',
        'starts_at',
        'expires_at',
        'is_active',
        'applicable_products',
        'applicable_categories',
        'excluded_products',
        'excluded_categories',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'excluded_products' => 'array',
        'excluded_categories' => 'array',
        'value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeValid(Builder $query): Builder
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
                    ->where('starts_at', '<=', $now)
                    ->where('expires_at', '>=', $now);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->valid()
                    ->where(function($q) {
                        $q->whereNull('maximum_uses')
                          ->orWhereRaw('used_count < maximum_uses');
                    });
    }

    // Methods
    public function isValid(): bool
    {
        $now = Carbon::now();
        return $this->is_active &&
               $this->starts_at <= $now &&
               $this->expires_at >= $now &&
               ($this->maximum_uses === null || $this->used_count < $this->maximum_uses);
    }

    public function canBeUsedBy($userId = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // Check if user has already used this coupon
        if ($userId && $this->maximum_uses_per_customer > 0) {
            $userUsageCount = \DB::table('coupon_usage')
                ->where('coupon_id', $this->id)
                ->where('user_id', $userId)
                ->count();

            if ($userUsageCount >= $this->maximum_uses_per_customer) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount($orderAmount): float
    {
        if ($orderAmount < $this->minimum_order_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($orderAmount * $this->value) / 100;
        }

        return min($this->value, $orderAmount);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    public function decrementUsage(): void
    {
        $this->decrement('used_count');
    }

    // Relationships
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'coupon_usage')
                    ->withPivot('discount_amount', 'used_at')
                    ->withTimestamps();
    }
}
