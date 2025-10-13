<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'shipping_method',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'currency',
        'billing_address',
        'shipping_address',
        'payment_method',
        'payment_status',
        'notes',
        'admin_notes',
        'shipped_at',
        'delivered_at',
        'tracking_number',
        'processed_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate a unique order number in format: ORD-YYYY-NNNN
     * YYYY = Year when order was made
     * NNNN = Unique alphanumeric code (letters + numbers)
     */
    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $maxAttempts = 100;
        
        for ($i = 0; $i < $maxAttempts; $i++) {
            // Generate 4-character alphanumeric code (uppercase letters and numbers)
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
            $orderNumber = "ORD-{$year}-{$code}";
            
            // Check if this order number already exists
            if (!self::where('order_number', $orderNumber)->exists()) {
                return $orderNumber;
            }
        }
        
        // Fallback: if somehow we can't generate unique after 100 attempts,
        // use timestamp-based code (this should never happen in practice)
        $code = strtoupper(substr(base_convert(time(), 10, 36), -4));
        return "ORD-{$year}-{$code}";
    }

    /**
     * Generate tracking number in format: RRPCC-ZZZZ-NNNN (15 characters including dashes)
     * RR = Region code (2 letters)
     * P = Province code (1 digit, 0 if not applicable)
     * CC = City/Municipality code (2 digits)
     * ZZZZ = Zip Code (4 digits)
     * NNNN = 4-character code from order number
     */
    public function generateTrackingNumber(): string
    {
        $user = $this->user;
        
        if (!$user) {
            return 'N/A';
        }

        // Extract the 4-character code from order number (ORD-YYYY-NNNN)
        $orderCode = substr($this->order_number, -4);

        // RR - Region code (2 letters)
        $regionCode = $this->getRegionCode($user->region);

        // P - Province code (1 digit, 0 if not applicable)
        $provinceCode = $this->getProvinceCode($user->province, $user->region);

        // CC - City/Municipality code (2 digits)
        $cityCode = $this->getCityCode($user->city);

        // ZZZZ - Zip Code (4 digits, pad with 0 if less than 4)
        $zipCode = str_pad(substr(preg_replace('/\D/', '', $user->zip_code ?? '0000'), 0, 4), 4, '0', STR_PAD_LEFT);

        // RRPCC-ZZZZ-NNNN
        return $regionCode . $provinceCode . $cityCode . '-' . $zipCode . '-' . $orderCode;
    }

    /**
     * Convert region name to 2-letter code
     */
    private function getRegionCode(?string $region): string
    {
        $regions = [
            'National Capital Region (NCR)' => 'NC',
            'Cordillera Administrative Region (CAR)' => 'CR',
            'Region I (Ilocos Region)' => 'IL',
            'Region II (Cagayan Valley)' => 'CV',
            'Region III (Central Luzon)' => 'CL',
            'Region IV-A (CALABARZON)' => 'CZ',
            'Region IV-B (MIMAROPA)' => 'MM',
            'Region V (Bicol Region)' => 'BC',
            'Region VI (Western Visayas)' => 'WV',
            'Region VII (Central Visayas)' => 'CV',
            'Region VIII (Eastern Visayas)' => 'EV',
            'Region IX (Zamboanga Peninsula)' => 'ZP',
            'Region X (Northern Mindanao)' => 'NM',
            'Region XI (Davao Region)' => 'DV',
            'Region XII (SOCCSKSARGEN)' => 'SK',
            'Region XIII (Caraga)' => 'CG',
            'Autonomous Region in Muslim Mindanao (ARMM)' => 'AR',
        ];

        return $regions[$region] ?? 'XX';
    }

    /**
     * Convert province to 1-digit code (0 if NCR or not applicable)
     */
    private function getProvinceCode(?string $province, ?string $region): string
    {
        // NCR has no provinces
        if ($region === 'National Capital Region (NCR)' || empty($province)) {
            return '0';
        }

        // Generate a hash-based code from province name (1-9)
        $hash = crc32(strtolower($province ?? ''));
        return (string) (($hash % 9) + 1);
    }

    /**
     * Convert city name to 2-digit code
     */
    private function getCityCode(?string $city): string
    {
        if (empty($city)) {
            return '00';
        }

        // Take first 2 letters and convert to numbers (A=01, B=02, ... Z=26)
        $city = strtoupper(preg_replace('/[^A-Z]/', '', $city));
        $first = ord(substr($city, 0, 1)) - 64; // A=1, B=2, etc.
        $second = strlen($city) > 1 ? ord(substr($city, 1, 1)) - 64 : 0;
        
        // Combine into 2-digit code (mod 10 to keep single digits)
        $code = ($first % 10) . ($second % 10);
        return str_pad($code, 2, '0', STR_PAD_LEFT);
    }
}
