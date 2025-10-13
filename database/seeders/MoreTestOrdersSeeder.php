<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;

class MoreTestOrdersSeeder extends Seeder
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
        
        // Create 8 more orders to test pagination (total will be 13 orders)
        for ($i = 6; $i <= 13; $i++) {
            $status = $statuses[array_rand($statuses)];
            
            // Create order with different dates
            $createdAt = Carbon::now()->subDays(rand(1, 30));
            $shippedAt = in_array($status, ['shipped', 'delivered']) ? $createdAt->copy()->addDays(rand(1, 3)) : null;
            $deliveredAt = $status === 'delivered' ? $shippedAt?->copy()->addDays(rand(1, 5)) : null;

            $order = Order::create([
                'user_id' => 1,
                'order_number' => Order::generateOrderNumber(),
                'status' => $status,
                'subtotal' => 0, // Will calculate after adding items
                'tax_amount' => 0,
                'shipping_amount' => 15.00,
                'total_amount' => 0, // Will calculate after adding items
                'currency' => 'PHP',
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
                'notes' => "Additional test order #{$i} with {$status} status",
                'shipped_at' => $shippedAt,
                'delivered_at' => $deliveredAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Add 1-3 random items to each order
            $itemCount = rand(1, 3);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 2);
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

        $this->command->info('Successfully created 8 additional test orders for pagination testing!');
    }
}