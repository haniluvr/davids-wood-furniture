<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => '10% off your first order',
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_order_amount' => 50.00,
                'maximum_uses' => 1000,
                'used_count' => 0,
                'maximum_uses_per_customer' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
                'applicable_products' => null,
                'applicable_categories' => null,
                'excluded_products' => null,
                'excluded_categories' => null,
            ],
            [
                'code' => 'SAVE25',
                'name' => 'Summer Sale',
                'description' => '$25 off orders over $200',
                'type' => 'fixed_amount',
                'value' => 25.00,
                'minimum_order_amount' => 200.00,
                'maximum_uses' => 500,
                'used_count' => 0,
                'maximum_uses_per_customer' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
                'applicable_products' => null,
                'applicable_categories' => null,
                'excluded_products' => null,
                'excluded_categories' => null,
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Free shipping on any order',
                'type' => 'fixed_amount',
                'value' => 15.00,
                'minimum_order_amount' => 0,
                'maximum_uses' => 2000,
                'used_count' => 0,
                'maximum_uses_per_customer' => 3,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
                'applicable_products' => null,
                'applicable_categories' => null,
                'excluded_products' => null,
                'excluded_categories' => null,
            ],
            [
                'code' => 'VIP20',
                'name' => 'VIP Customer Discount',
                'description' => '20% off for VIP customers',
                'type' => 'percentage',
                'value' => 20.00,
                'minimum_order_amount' => 100.00,
                'maximum_uses' => 100,
                'used_count' => 0,
                'maximum_uses_per_customer' => 5,
                'starts_at' => now(),
                'expires_at' => now()->addYear(),
                'is_active' => true,
                'applicable_products' => null,
                'applicable_categories' => null,
                'excluded_products' => null,
                'excluded_categories' => null,
            ],
            [
                'code' => 'BULK15',
                'name' => 'Bulk Purchase Discount',
                'description' => '15% off when buying 3 or more items',
                'type' => 'percentage',
                'value' => 15.00,
                'minimum_order_amount' => 300.00,
                'maximum_uses' => 200,
                'used_count' => 0,
                'maximum_uses_per_customer' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(4),
                'is_active' => true,
                'applicable_products' => null,
                'applicable_categories' => null,
                'excluded_products' => null,
                'excluded_categories' => null,
            ],
            [
                'code' => 'NEWYEAR2024',
                'name' => 'New Year Special',
                'description' => 'Special New Year discount',
                'type' => 'percentage',
                'value' => 12.00,
                'minimum_order_amount' => 75.00,
                'maximum_uses' => 300,
                'used_count' => 0,
                'maximum_uses_per_customer' => 1,
                'starts_at' => Carbon::create(2024, 1, 1),
                'expires_at' => Carbon::create(2024, 1, 31),
                'is_active' => false,
                'applicable_products' => null,
                'applicable_categories' => null,
                'excluded_products' => null,
                'excluded_categories' => null,
            ],
            [
                'code' => 'CUSTOM50',
                'name' => 'Custom Furniture Discount',
                'description' => '$50 off custom furniture orders',
                'type' => 'fixed_amount',
                'value' => 50.00,
                'minimum_order_amount' => 500.00,
                'maximum_uses' => 50,
                'used_count' => 0,
                'maximum_uses_per_customer' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
                'applicable_products' => null,
                'applicable_categories' => [1, 2], // Assuming categories 1 and 2 are custom furniture
                'excluded_products' => null,
                'excluded_categories' => null,
            ],
            [
                'code' => 'STUDENT10',
                'name' => 'Student Discount',
                'description' => '10% off for students with valid ID',
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_order_amount' => 25.00,
                'maximum_uses' => 1000,
                'used_count' => 0,
                'maximum_uses_per_customer' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(12),
                'is_active' => true,
                'applicable_products' => null,
                'applicable_categories' => null,
                'excluded_products' => null,
                'excluded_categories' => null,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }

        $this->command->info('Coupons seeded successfully.');
    }
}
