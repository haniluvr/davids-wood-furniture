<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;

class TestOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products to use in orders
        $products = Product::limit(10)->get();
        
        if ($products->count() < 3) {
            $this->command->error('Not enough products in database. Please seed products first.');
            return;
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        foreach ($statuses as $index => $status) {
            // Create order with different dates based on status
            $createdAt = match($status) {
                'pending' => Carbon::now()->subDays(1),
                'processing' => Carbon::now()->subDays(3),
                'shipped' => Carbon::now()->subDays(7),
                'delivered' => Carbon::now()->subDays(14),
                'cancelled' => Carbon::now()->subDays(5),
            };

            $shippedAt = in_array($status, ['shipped', 'delivered']) ? $createdAt->copy()->addDays(2) : null;
            $deliveredAt = $status === 'delivered' ? $shippedAt?->copy()->addDays(3) : null;

            $order = Order::create([
                'user_id' => 1,
                'order_number' => 'ORD-2024-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'status' => $status,
                'subtotal' => 0, // Will calculate after adding items
                'tax_amount' => 0,
                'shipping_amount' => 15.00,
                'total_amount' => 0, // Will calculate after adding items
                'currency' => 'USD',
                'billing_address' => [
                    'name' => 'John Doe',
                    'street' => '123 Main Street',
                    'city' => 'Manila',
                    'province' => 'Metro Manila',
                    'zip_code' => '1000',
                    'country' => 'Philippines'
                ],
                'shipping_address' => [
                    'name' => 'John Doe',
                    'street' => '123 Main Street',
                    'city' => 'Manila',
                    'province' => 'Metro Manila',
                    'zip_code' => '1000',
                    'country' => 'Philippines'
                ],
                'payment_method' => 'credit_card',
                'payment_status' => match($status) {
                    'cancelled' => 'refunded',
                    'pending' => 'pending',
                    default => 'paid'
                },
                'notes' => "Test order with {$status} status",
                'shipped_at' => $shippedAt,
                'delivered_at' => $deliveredAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Add 2-4 random items to each order
            $itemCount = rand(2, 4);
            $subtotal = 0;

            for ($i = 0; $i < $itemCount; $i++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $quantity;
                $subtotal += $totalPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku ?? 'SKU-' . $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_data' => [
                        'category' => $product->category?->name,
                        'material' => $product->material,
                        'dimensions' => $product->dimensions,
                        'image' => $product->image,
                    ],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Calculate tax (12% VAT for Philippines)
            $taxAmount = $subtotal * 0.12;
            $totalAmount = $subtotal + $taxAmount + 15.00; // subtotal + tax + shipping

            // Update order totals
            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);

            $this->command->info("Created {$status} order: {$order->order_number} with {$itemCount} items (Total: $" . number_format($totalAmount, 2) . ")");
        }

        $this->command->info('Successfully created 5 test orders with different statuses for user ID 1!');
    }
}