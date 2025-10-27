<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepopulateOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user_id 1
        $user = User::find(76);
        if (! $user) {
            $this->command->error('User with ID 1 not found!');

            return;
        }

        // Get some products to use in orders
        $products = Product::take(5)->get();
        if ($products->isEmpty()) {
            $this->command->error('No products found! Please seed products first.');

            return;
        }

        $this->command->info('Creating 9 orders for user: '.$user->email);

        // Create orders with different statuses following the priority logic
        $orders = [
            // Pending orders (most recent - should appear first in user view)
            [
                'status' => 'pending',
                'created_at' => now()->subHours(2),
                'products' => [$products[0], $products[1]],
                'quantities' => [1, 2],
            ],
            [
                'status' => 'pending',
                'created_at' => now()->subHours(4),
                'products' => [$products[2]],
                'quantities' => [1],
            ],

            // Processing orders
            [
                'status' => 'processing',
                'created_at' => now()->subDays(1),
                'products' => [$products[0], $products[3]],
                'quantities' => [2, 1],
            ],
            [
                'status' => 'processing',
                'created_at' => now()->subDays(2),
                'products' => [$products[1], $products[2], $products[4]],
                'quantities' => [1, 1, 1],
            ],

            // Cancelled orders (can appear anywhere)
            [
                'status' => 'cancelled',
                'created_at' => now()->subDays(3),
                'products' => [$products[0]],
                'quantities' => [1],
            ],

            // Shipped orders
            [
                'status' => 'shipped',
                'created_at' => now()->subDays(5),
                'products' => [$products[1], $products[3]],
                'quantities' => [1, 2],
            ],
            [
                'status' => 'shipped',
                'created_at' => now()->subDays(7),
                'products' => [$products[2]],
                'quantities' => [3],
            ],

            // Delivered orders
            [
                'status' => 'delivered',
                'created_at' => now()->subDays(10),
                'products' => [$products[0], $products[4]],
                'quantities' => [1, 1],
            ],
            [
                'status' => 'delivered',
                'created_at' => now()->subDays(15),
                'products' => [$products[1], $products[2], $products[3]],
                'quantities' => [2, 1, 1],
            ],
        ];

        DB::beginTransaction();

        try {
            foreach ($orders as $orderData) {
                // Calculate totals
                $subtotal = 0;
                $orderItems = [];

                foreach ($orderData['products'] as $index => $product) {
                    $quantity = $orderData['quantities'][$index];
                    $unitPrice = $product->price;
                    $totalPrice = $unitPrice * $quantity;
                    $subtotal += $totalPrice;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total_price' => $totalPrice,
                    ];
                }

                $taxAmount = $subtotal * 0.12; // 12% tax
                $shippingAmount = 100; // Fixed shipping
                $totalAmount = $subtotal + $taxAmount + $shippingAmount;

                // Create the order
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => Order::generateOrderNumber(),
                    'status' => $orderData['status'],
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'shipping_amount' => $shippingAmount,
                    'total_amount' => $totalAmount,
                    'currency' => 'PHP',
                    'billing_address' => [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'phone' => $user->phone ?? 'N/A',
                        'street' => $user->street ?? 'N/A',
                        'barangay' => $user->barangay ?? 'N/A',
                        'city' => $user->city ?? 'N/A',
                        'province' => $user->province ?? 'N/A',
                        'zip_code' => $user->zip_code ?? 'N/A',
                    ],
                    'shipping_address' => [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'phone' => $user->phone ?? 'N/A',
                        'street' => $user->street ?? 'N/A',
                        'barangay' => $user->barangay ?? 'N/A',
                        'city' => $user->city ?? 'N/A',
                        'province' => $user->province ?? 'N/A',
                        'zip_code' => $user->zip_code ?? 'N/A',
                    ],
                    'payment_method' => 'credit_card',
                    'payment_status' => 'paid',
                    'notes' => 'Test order created by seeder',
                    'created_at' => $orderData['created_at'],
                    'updated_at' => $orderData['created_at'],
                ]);

                // Create order items
                foreach ($orderItems as $itemData) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $itemData['product_id'],
                        'product_name' => $itemData['product_name'],
                        'product_sku' => $itemData['product_sku'],
                        'unit_price' => $itemData['unit_price'],
                        'quantity' => $itemData['quantity'],
                        'total_price' => $itemData['total_price'],
                    ]);
                }

                $this->command->info("Created order #{$order->order_number} with status: {$order->status}");
            }

            DB::commit();
            $this->command->info('Successfully created 9 orders for user_id 1!');
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('Failed to create orders: '.$e->getMessage());
        }
    }
}
