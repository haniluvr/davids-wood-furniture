<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;

class DummyOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a user to create orders for
        $user = User::first();
        
        if (!$user) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        // Get some products
        $products = Product::take(5)->get();
        
        if ($products->isEmpty()) {
            $this->command->info('No products found. Please run ProductSeeder first.');
            return;
        }

        // Create dummy orders
        $orders = [
            [
                'order_number' => 'ORD-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'status' => 'processing',
                'subtotal' => 129.99,
                'tax_amount' => 10.40,
                'shipping_amount' => 15.00,
                'total_amount' => 155.39,
                'currency' => 'USD',
                'billing_address' => [
                    'street' => '123 Main Street',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10001',
                    'country' => 'United States'
                ],
                'shipping_address' => [
                    'street' => '123 Main Street',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10001',
                    'country' => 'United States'
                ],
                'payment_method' => 'credit_card',
                'payment_status' => 'paid',
                'notes' => 'Please handle with care',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'order_number' => 'ORD-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'status' => 'delivered',
                'subtotal' => 89.99,
                'tax_amount' => 7.20,
                'shipping_amount' => 12.00,
                'total_amount' => 109.19,
                'currency' => 'USD',
                'billing_address' => [
                    'street' => '123 Main Street',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10001',
                    'country' => 'United States'
                ],
                'shipping_address' => [
                    'street' => '123 Main Street',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10001',
                    'country' => 'United States'
                ],
                'payment_method' => 'credit_card',
                'payment_status' => 'paid',
                'shipped_at' => Carbon::now()->subDays(5),
                'delivered_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'order_number' => 'ORD-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'status' => 'shipped',
                'subtotal' => 199.99,
                'tax_amount' => 16.00,
                'shipping_amount' => 20.00,
                'total_amount' => 235.99,
                'currency' => 'USD',
                'billing_address' => [
                    'street' => '123 Main Street',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10001',
                    'country' => 'United States'
                ],
                'shipping_address' => [
                    'street' => '123 Main Street',
                    'city' => 'New York',
                    'state' => 'NY',
                    'zip' => '10001',
                    'country' => 'United States'
                ],
                'payment_method' => 'credit_card',
                'payment_status' => 'paid',
                'notes' => 'Express shipping requested',
                'shipped_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(3),
            ]
        ];

        foreach ($orders as $orderData) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderData['order_number'],
                'status' => $orderData['status'],
                'subtotal' => $orderData['subtotal'],
                'tax_amount' => $orderData['tax_amount'],
                'shipping_amount' => $orderData['shipping_amount'],
                'total_amount' => $orderData['total_amount'],
                'currency' => $orderData['currency'],
                'billing_address' => $orderData['billing_address'],
                'shipping_address' => $orderData['shipping_address'],
                'payment_method' => $orderData['payment_method'],
                'payment_status' => $orderData['payment_status'],
                'notes' => $orderData['notes'] ?? null,
                'shipped_at' => $orderData['shipped_at'] ?? null,
                'delivered_at' => $orderData['delivered_at'] ?? null,
                'created_at' => $orderData['created_at'],
            ]);

            // Create order items for each order
            $numItems = rand(1, 3);
            $selectedProducts = $products->random($numItems);
            
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 2);
                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku ?? 'SKU-' . $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_data' => [
                        'image' => $product->image,
                        'description' => $product->description,
                        'category' => $product->category->name ?? 'Furniture'
                    ]
                ]);
            }
        }

        $this->command->info('Dummy orders created successfully!');
    }
}
