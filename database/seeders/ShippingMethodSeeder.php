<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shippingMethods = [
            [
                'name' => 'Standard Shipping',
                'description' => 'Regular ground shipping with tracking',
                'type' => 'flat_rate',
                'cost' => 9.99,
                'minimum_order_amount' => 0,
                'maximum_order_amount' => null,
                'estimated_days_min' => 3,
                'estimated_days_max' => 7,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Express Shipping',
                'description' => 'Fast shipping for urgent orders',
                'type' => 'flat_rate',
                'cost' => 19.99,
                'minimum_order_amount' => 0,
                'maximum_order_amount' => null,
                'estimated_days_min' => 1,
                'estimated_days_max' => 3,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Free Shipping',
                'description' => 'Free shipping on orders over $100',
                'type' => 'free_shipping',
                'cost' => 0,
                'free_shipping_threshold' => 100.00,
                'minimum_order_amount' => 100,
                'maximum_order_amount' => null,
                'estimated_days_min' => 5,
                'estimated_days_max' => 10,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Weight-Based Shipping',
                'description' => 'Shipping cost based on package weight',
                'type' => 'weight_based',
                'cost' => 5.99,
                'minimum_order_amount' => 0,
                'maximum_order_amount' => null,
                'estimated_days_min' => 4,
                'estimated_days_max' => 8,
                'is_active' => true,
                'sort_order' => 4,
                'weight_rates' => [
                    ['min_weight' => 0, 'max_weight' => 5, 'rate' => 5.99],
                    ['min_weight' => 5, 'max_weight' => 10, 'rate' => 8.99],
                    ['min_weight' => 10, 'max_weight' => 20, 'rate' => 12.99],
                    ['min_weight' => 20, 'max_weight' => null, 'rate' => 19.99],
                ],
            ],
            [
                'name' => 'Local Delivery',
                'description' => 'Same-day delivery within 25 miles',
                'type' => 'flat_rate',
                'cost' => 15.00,
                'minimum_order_amount' => 50,
                'maximum_order_amount' => null,
                'estimated_days_min' => 0,
                'estimated_days_max' => 1,
                'is_active' => true,
                'sort_order' => 5,
                'zones' => [
                    'local' => ['radius' => 25, 'unit' => 'miles']
                ],
            ],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::create($method);
        }

        $this->command->info('Shipping methods seeded successfully.');
    }
}
