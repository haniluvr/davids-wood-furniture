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
                return (float) $this->cost;

            case 'free_shipping':
                return 0.0;

            case 'weight_based':
                return (float) $this->calculateWeightBasedCost($weight);

            case 'price_based':
                return (float) $this->calculatePriceBasedCost($orderAmount);

            default:
                return (float) $this->cost;
        }
    }

    private function calculateWeightBasedCost($weight): float
    {
        if (! $this->weight_rates || ! is_array($this->weight_rates)) {
            return (float) $this->cost;
        }

        // Handle both formats: ['weight' => X, 'cost' => Y] or ['min_weight' => X, 'max_weight' => Y, 'rate' => Z]
        foreach ($this->weight_rates as $rate) {
            // New format: min_weight, max_weight, rate
            if (isset($rate['min_weight']) && isset($rate['rate'])) {
                $minWeight = $rate['min_weight'] ?? 0;
                $maxWeight = $rate['max_weight'] ?? null;
                $rateValue = $rate['rate'] ?? 0;

                // Check if weight falls in this range
                if ($weight >= $minWeight && ($maxWeight === null || $weight <= $maxWeight)) {
                    return $rateValue;
                }
            }
            // Old format: weight, cost (cumulative)
            elseif (isset($rate['weight']) && isset($rate['cost'])) {
                $rateWeight = $rate['weight'] ?? 0;
                $rateCost = $rate['cost'] ?? 0;

                if ($weight >= $rateWeight) {
                    // For cumulative rates, we need to handle differently
                    // This assumes the last matching rate applies
                    return $rateCost;
                }
            }
        }

        // If no match found, return base cost
        return (float) $this->cost;
    }

    private function calculatePriceBasedCost($orderAmount): float
    {
        // This would be implemented based on specific business rules
        // For now, return the base cost
        return (float) $this->cost;
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
