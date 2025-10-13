<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NewDeliveredOrderSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $user = User::find(1);
        
        if (!$user) {
            $this->command->error('❌ User with ID 1 not found. Please create a user first.');
            return;
        }

        // Get some products that haven't been reviewed yet
        $products = Product::whereNotIn('id', [3, 8]) // Exclude already reviewed products
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        if ($products->count() < 1) {
            $this->command->error('❌ No products available for the order.');
            return;
        }

        // Calculate order totals
        $subtotal = 0;
        $orderItems = [];

        foreach ($products as $product) {
            $quantity = rand(1, 2);
            $unitPrice = $product->sale_price ?? $product->price;
            $totalPrice = $unitPrice * $quantity;
            $subtotal += $totalPrice;

            $orderItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ];
        }

        $taxAmount = $subtotal * 0.12; // 12% VAT
        $shippingAmount = 150.00;
        $totalAmount = $subtotal + $taxAmount + $shippingAmount;

        // Create the order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'status' => 'delivered',
            'payment_status' => 'paid',
            'payment_method' => 'Online Payment',
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_amount' => $shippingAmount,
            'discount_amount' => 0,
            'total_amount' => $totalAmount,
            'currency' => 'PHP',
            'shipping_address' => json_encode([
                'street' => $user->street,
                'barangay' => $user->barangay,
                'city' => $user->city,
                'province' => $user->province,
                'zip_code' => $user->zip_code,
            ]),
            'billing_address' => json_encode([
                'street' => $user->street,
                'barangay' => $user->barangay,
                'city' => $user->city,
                'province' => $user->province,
                'zip_code' => $user->zip_code,
            ]),
            'notes' => null,
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(2),
            'shipped_at' => Carbon::now()->subDays(5),
            'delivered_at' => Carbon::now()->subDays(2),
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'product_sku' => $item['product']->sku,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);

            $this->command->info("✅ Added product: {$item['product']->name} (ID: {$item['product']->id})");
        }

        $this->command->info("✅ Created delivered order #{$order->order_number} with {$products->count()} items");
        $this->command->info("📦 Order Total: ₱" . number_format($totalAmount, 2));
        $this->command->info("🎯 This order is ready for reviews!");
    }
}
