<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and products
        $users = User::take(5)->get();
        $products = Product::take(10)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('No users or products found. Please seed users and products first.');
            return;
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'refunded', 'failed'];
        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'cash_on_delivery'];

        for ($i = 1; $i <= 25; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

            // Calculate order totals
            $subtotal = 0;
            $orderItems = [];
            $numItems = rand(1, 4);

            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $quantity;
                
                $subtotal += $totalPrice;
                
                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_data' => [
                        'name' => $product->name,
                        'image' => $product->image,
                        'category' => $product->category->name ?? null,
                    ],
                ];
            }

            $taxAmount = $subtotal * 0.1; // 10% tax
            $shippingCost = $subtotal > 100 ? 0 : 15; // Free shipping over $100
            $discountAmount = 0; // No discounts for now
            $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;

            // Create billing and shipping addresses
            $billingAddress = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'address_line_1' => fake()->streetAddress(),
                'address_line_2' => rand(0, 1) ? fake()->secondaryAddress() : null,
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => 'United States',
            ];

            $shippingAddress = rand(0, 1) ? $billingAddress : [
                'name' => $user->first_name . ' ' . $user->last_name,
                'address_line_1' => fake()->streetAddress(),
                'address_line_2' => rand(0, 1) ? fake()->secondaryAddress() : null,
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => 'United States',
            ];

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => $status,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'USD',
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'shipping_method' => $shippingCost > 0 ? 'standard' : 'free',
                'tracking_number' => in_array($status, ['shipped', 'delivered']) ? 'TRK' . strtoupper(uniqid()) : null,
                'notes' => rand(0, 1) ? fake()->sentence() : null,
                'shipped_at' => in_array($status, ['shipped', 'delivered']) ? fake()->dateTimeBetween('-30 days', '-1 day') : null,
                'delivered_at' => $status === 'delivered' ? fake()->dateTimeBetween('-15 days', 'now') : null,
                'created_at' => fake()->dateTimeBetween('-60 days', 'now'),
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
            }
        }

        $this->command->info('Created 25 sample orders with order items.');
    }
}