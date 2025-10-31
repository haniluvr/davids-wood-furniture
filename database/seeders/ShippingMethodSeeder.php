<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

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
                'cost' => 200.00,
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
                'cost' => 500.00,
                'minimum_order_amount' => 0,
                'maximum_order_amount' => null,
                'estimated_days_min' => 1,
                'estimated_days_max' => 3,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Free Shipping',
                'description' => 'Free shipping on orders over ₱5,000',
                'type' => 'free_shipping',
                'cost' => 0,
                'free_shipping_threshold' => 5000.00,
                'minimum_order_amount' => 5000,
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
                'cost' => 150.00,
                'minimum_order_amount' => 0,
                'maximum_order_amount' => null,
                'estimated_days_min' => 4,
                'estimated_days_max' => 8,
                'is_active' => true,
                'sort_order' => 4,
                'weight_rates' => [
                    ['min_weight' => 0, 'max_weight' => 5, 'rate' => 150.00],
                    ['min_weight' => 5, 'max_weight' => 10, 'rate' => 250.00],
                    ['min_weight' => 10, 'max_weight' => 20, 'rate' => 350.00],
                    ['min_weight' => 20, 'max_weight' => null, 'rate' => 500.00],
                ],
            ],
            [
                'name' => 'Local Delivery',
                'description' => 'Same-day delivery within Metro Manila/NCR',
                'type' => 'flat_rate',
                'cost' => 100.00,
                'minimum_order_amount' => 0,
                'maximum_order_amount' => null,
                'estimated_days_min' => 0,
                'estimated_days_max' => 1,
                'is_active' => true,
                'sort_order' => 5,
                'zones' => [
                    'local' => ['radius' => 40, 'unit' => 'km'],
                ],
            ],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }

        $this->command->info('Shipping methods seeded successfully.');
    }
}
