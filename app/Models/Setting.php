<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_public',
        'sort_order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Boot method to clear cache when settings change
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('settings');
            Cache::forget('public_settings');
        });

        static::deleted(function () {
            Cache::forget('settings');
            Cache::forget('public_settings');
        });
    }

    // Scopes
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('group')->orderBy('sort_order')->orderBy('label');
    }

    // Accessors & Mutators
    public function getValueAttribute($value)
    {
        return match($this->type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match($this->type) {
            'boolean' => $value ? '1' : '0',
            'json', 'array' => json_encode($value),
            default => (string) $value,
        };
    }

    // Static methods
    public static function get(string $key, $default = null)
    {
        $settings = Cache::remember('settings', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, $value, string $type = 'string', string $group = 'general', string $label = null, string $description = null, bool $isPublic = false): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'label' => $label ?: ucwords(str_replace(['_', '-'], ' ', $key)),
                'description' => $description,
                'is_public' => $isPublic,
            ]
        );

        // Clear cache
        Cache::forget('settings');
        Cache::forget('public_settings');

        return $setting;
    }

    public static function getByGroup(string $group): array
    {
        return self::where('group', $group)->ordered()->get()->keyBy('key')->toArray();
    }

    public static function getPublic(): array
    {
        return Cache::remember('public_settings', 3600, function () {
            return self::public()->pluck('value', 'key')->toArray();
        });
    }

    public static function getGroups(): array
    {
        return self::distinct('group')->pluck('group')->sort()->toArray();
    }

    public static function getTypeOptions(): array
    {
        return [
            'string' => 'String',
            'integer' => 'Integer',
            'float' => 'Float',
            'boolean' => 'Boolean',
            'json' => 'JSON',
            'array' => 'Array',
        ];
    }

    // Common settings getters
    public static function siteName(): string
    {
        return self::get('site_name', 'David\'s Wood Furniture');
    }

    public static function siteDescription(): string
    {
        return self::get('site_description', 'Premium handcrafted wooden furniture');
    }

    public static function contactEmail(): string
    {
        return self::get('contact_email', 'contact@davidswood.com');
    }

    public static function currency(): string
    {
        return self::get('currency', 'USD');
    }

    public static function currencySymbol(): string
    {
        return self::get('currency_symbol', '$');
    }

    public static function taxRate(): float
    {
        return (float) self::get('tax_rate', 0);
    }

    public static function shippingCost(): float
    {
        return (float) self::get('shipping_cost', 0);
    }

    public static function freeShippingThreshold(): float
    {
        return (float) self::get('free_shipping_threshold', 100);
    }

    public static function lowStockThreshold(): int
    {
        return (int) self::get('low_stock_threshold', 10);
    }

    public static function maintenanceMode(): bool
    {
        return (bool) self::get('maintenance_mode', false);
    }

    public static function getValue(string $key, $default = null)
    {
        return self::get($key, $default);
    }

    public static function setValue(string $key, $value): void
    {
        self::set($key, $value);
    }
}