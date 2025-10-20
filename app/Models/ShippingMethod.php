<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'cost',
        'free_shipping_threshold',
        'minimum_order_amount',
        'maximum_order_amount',
        'zones',
        'weight_rates',
        'estimated_days_min',
        'estimated_days_max',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_order_amount' => 'decimal:2',
        'zones' => 'array',
        'weight_rates' => 'array',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Methods
    public function calculateCost($orderAmount = 0, $weight = 0, $zone = null): float
    {
        // Free shipping if threshold is met
        if ($this->free_shipping_threshold && $orderAmount >= $this->free_shipping_threshold) {
            return 0;
        }

        // Check minimum/maximum order amount
        if ($this->minimum_order_amount && $orderAmount < $this->minimum_order_amount) {
            return 0; // Not available
        }

        if ($this->maximum_order_amount && $orderAmount > $this->maximum_order_amount) {
            return 0; // Not available
        }

        switch ($this->type) {
            case 'flat_rate':
                return $this->cost;

            case 'free_shipping':
                return 0;

            case 'weight_based':
                return $this->calculateWeightBasedCost($weight);

            case 'price_based':
                return $this->calculatePriceBasedCost($orderAmount);

            default:
                return $this->cost;
        }
    }

    private function calculateWeightBasedCost($weight): float
    {
        if (! $this->weight_rates || ! is_array($this->weight_rates)) {
            return $this->cost;
        }

        $totalCost = 0;
        $remainingWeight = $weight;

        foreach ($this->weight_rates as $rate) {
            if ($remainingWeight <= 0) {
                break;
            }

            $rateWeight = $rate['weight'] ?? 0;
            $rateCost = $rate['cost'] ?? 0;

            if ($remainingWeight >= $rateWeight) {
                $totalCost += $rateCost;
                $remainingWeight -= $rateWeight;
            } else {
                $totalCost += $rateCost;
                break;
            }
        }

        return $totalCost;
    }

    private function calculatePriceBasedCost($orderAmount): float
    {
        // This would be implemented based on specific business rules
        // For now, return the base cost
        return $this->cost;
    }

    public function isAvailableFor($orderAmount = 0, $weight = 0, $zone = null): bool
    {
        // Check if method is active
        if (! $this->is_active) {
            return false;
        }

        // Check minimum order amount
        if ($this->minimum_order_amount && $orderAmount < $this->minimum_order_amount) {
            return false;
        }

        // Check maximum order amount
        if ($this->maximum_order_amount && $orderAmount > $this->maximum_order_amount) {
            return false;
        }

        // Check zone availability
        if ($zone && $this->zones && ! in_array($zone, $this->zones)) {
            return false;
        }

        return true;
    }

    public function getEstimatedDeliveryDays(): string
    {
        if ($this->estimated_days_min && $this->estimated_days_max) {
            if ($this->estimated_days_min === $this->estimated_days_max) {
                return $this->estimated_days_min.' day'.($this->estimated_days_min > 1 ? 's' : '');
            }

            return $this->estimated_days_min.'-'.$this->estimated_days_max.' days';
        }

        return 'Standard delivery';
    }
}
