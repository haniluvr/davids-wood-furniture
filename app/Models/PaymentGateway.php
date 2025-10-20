<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gateway_key',
        'display_name',
        'description',
        'config',
        'supported_currencies',
        'supported_countries',
        'transaction_fee_percentage',
        'transaction_fee_fixed',
        'is_active',
        'is_test_mode',
        'sort_order',
    ];

    protected $casts = [
        'config' => 'array',
        'supported_currencies' => 'array',
        'supported_countries' => 'array',
        'transaction_fee_percentage' => 'decimal:4',
        'transaction_fee_fixed' => 'decimal:2',
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('display_name');
    }

    public function scopeForCurrency(Builder $query, string $currency): Builder
    {
        return $query->where(function ($q) use ($currency) {
            $q->whereNull('supported_currencies')
                ->orWhereJsonContains('supported_currencies', $currency);
        });
    }

    public function scopeForCountry(Builder $query, string $country): Builder
    {
        return $query->where(function ($q) use ($country) {
            $q->whereNull('supported_countries')
                ->orWhereJsonContains('supported_countries', $country);
        });
    }

    // Methods
    public function getConfigValue(string $key, $default = null)
    {
        $config = $this->config ?? [];

        return $config[$key] ?? $default;
    }

    public function setConfigValue(string $key, $value): void
    {
        $config = $this->config ?? [];
        $config[$key] = $value;
        $this->config = $config;
    }

    public function getEncryptedConfigValue(string $key, $default = null)
    {
        $encryptedValue = $this->getConfigValue($key);
        if ($encryptedValue) {
            try {
                return Crypt::decryptString($encryptedValue);
            } catch (\Exception $e) {
                return $default;
            }
        }

        return $default;
    }

    public function setEncryptedConfigValue(string $key, $value): void
    {
        $encryptedValue = Crypt::encryptString($value);
        $this->setConfigValue($key, $encryptedValue);
    }

    public function calculateTransactionFee($amount): float
    {
        $percentageFee = ($amount * $this->transaction_fee_percentage) / 100;

        return $percentageFee + $this->transaction_fee_fixed;
    }

    public function isAvailableFor($currency = 'USD', $country = 'US'): bool
    {
        if (! $this->is_active) {
            return false;
        }

        // Check currency support
        if ($this->supported_currencies && ! in_array($currency, $this->supported_currencies)) {
            return false;
        }

        // Check country support
        if ($this->supported_countries && ! in_array($country, $this->supported_countries)) {
            return false;
        }

        return true;
    }

    public function getDisplayName(): string
    {
        return $this->display_name ?: $this->name;
    }

    public function getModeText(): string
    {
        return $this->is_test_mode ? 'Test Mode' : 'Live Mode';
    }

    public function getModeColor(): string
    {
        return $this->is_test_mode ? 'warning' : 'success';
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'payment_gateway_id');
    }
}
