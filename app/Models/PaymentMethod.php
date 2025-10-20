<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'card_type',
        'card_last_four',
        'card_holder_name',
        'card_expiry_month',
        'card_expiry_year',
        'gcash_number',
        'gcash_name',
        'billing_address',
        'is_default',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'is_default' => 'boolean',
        'card_expiry_month' => 'integer',
        'card_expiry_year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get display name for the payment method
     */
    public function getDisplayName(): string
    {
        if ($this->type === 'card') {
            return $this->card_type.' •••• '.$this->card_last_four;
        } elseif ($this->type === 'gcash') {
            return 'GCash •••• '.substr($this->gcash_number, -4);
        }

        return 'Unknown Payment Method';
    }

    /**
     * Get masked number for display
     */
    public function getMaskedNumber(): string
    {
        if ($this->type === 'card') {
            return '•••• •••• •••• '.$this->card_last_four;
        } elseif ($this->type === 'gcash') {
            return '•••• •••• '.substr($this->gcash_number, -4);
        }

        return '•••• •••• •••• ••••';
    }

    /**
     * Check if this is a card payment method
     */
    public function isCard(): bool
    {
        return $this->type === 'card';
    }

    /**
     * Check if this is a GCash payment method
     */
    public function isGcash(): bool
    {
        return $this->type === 'gcash';
    }

    /**
     * Get formatted expiry date for cards
     */
    public function getFormattedExpiry(): string
    {
        if ($this->isCard() && $this->card_expiry_month && $this->card_expiry_year) {
            return sprintf('%02d/%d', $this->card_expiry_month, $this->card_expiry_year);
        }

        return '';
    }

    /**
     * Check if card is expired
     */
    public function isExpired(): bool
    {
        if (! $this->isCard() || ! $this->card_expiry_month || ! $this->card_expiry_year) {
            return false;
        }

        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');

        return $this->card_expiry_year < $currentYear ||
               ($this->card_expiry_year == $currentYear && $this->card_expiry_month < $currentMonth);
    }
}
