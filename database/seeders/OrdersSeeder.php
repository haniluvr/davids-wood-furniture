<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📦 Generating orders with realistic data...');

        // Clear existing data
        DB::table('product_reviews')->delete();
        DB::table('order_items')->delete();
        DB::table('orders')->delete();

        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('⚠️ No users or products found. Skipping order generation.');

            return;
        }

        $this->command->info("Found {$users->count()} users and {$products->count()} products");

        // Generate 150 orders (as specified in the original plan)
        $totalOrders = 150;
        $this->generateOrders($users, $products, $totalOrders);

        $this->command->info('✅ Order generation completed!');
    }

    private function generateOrders($users, $products, $totalOrders)
    {
        $this->command->info("📦 Creating {$totalOrders} orders...");

        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $orderStatusWeights = [
            'pending' => 10,
            'processing' => 15,
            'shipped' => 20,
            'delivered' => 50, // Most orders should be delivered
            'cancelled' => 5,
        ];

        $deliveredOrders = [];

        for ($i = 0; $i < $totalOrders; $i++) {
            $user = $users->random();
            $orderStatus = $this->getWeightedRandomStatus($orderStatuses, $orderStatusWeights);

            // Generate order number using the proper format (ORD-YYYY-NNNN)
            $orderNumber = Order::generateOrderNumber();

            // Generate tracking number using the proper format (RRPCC-ZZZZ-NNNN)
            $trackingNumber = null; // Will be generated after order creation

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'status' => $orderStatus,
                'tracking_number' => null, // Will be generated for shipped/delivered orders
                'subtotal' => 0, // Will be calculated
                'tax_amount' => 0, // Will be calculated
                'shipping_amount' => $this->faker->randomFloat(2, 50, 200),
                'total_amount' => 0, // Will be calculated
                'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed', 'refunded']),
                'payment_method' => $this->faker->randomElement(['credit_card', 'debit_card', 'paypal', 'bank_transfer']),
                'shipping_address' => json_encode([
                    'name' => $user->first_name.' '.$user->last_name,
                    'street' => $user->street,
                    'city' => $user->city,
                    'province' => $user->province,
                    'zip_code' => $user->zip_code,
                    'region' => $user->region,
                ]),
                'billing_address' => json_encode([
                    'name' => $user->first_name.' '.$user->last_name,
                    'street' => $user->street,
                    'city' => $user->city,
                    'province' => $user->province,
                    'zip_code' => $user->zip_code,
                    'region' => $user->region,
                ]),
                'notes' => $this->faker->optional(0.3)->sentence(),
                'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);

            // Add 1-5 random products to order
            $orderItemCount = rand(1, 5);
            $randomProducts = $products->random($orderItemCount);

            $subtotal = 0;
            foreach ($randomProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->sale_price ?? $product->price;
                $totalPrice = $unitPrice * $quantity;
                $subtotal += $totalPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'created_at' => $order->created_at,
                    'updated_at' => now(),
                ]);
            }

            // Calculate totals
            $taxAmount = $subtotal * 0.12; // 12% tax
            $totalAmount = $subtotal + $taxAmount + $order->shipping_amount;

            // Update order with calculated totals
            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);

            // Generate tracking number for shipped/delivered orders using the proper format
            if (in_array($orderStatus, ['shipped', 'delivered'])) {
                $order->update(['tracking_number' => $order->generateTrackingNumber()]);
            }

            // Store delivered orders for review generation
            if ($orderStatus === 'delivered') {
                $deliveredOrders[] = $order;
            }

            if (($i + 1) % 25 == 0 || $i + 1 == $totalOrders) {
                $this->command->info('Created '.($i + 1)."/{$totalOrders} orders...");
            }
        }

        $totalOrderItems = OrderItem::count();
        $this->command->info("✅ Created {$totalOrders} orders with {$totalOrderItems} total items");

        // Generate product reviews for delivered orders
        if (! empty($deliveredOrders)) {
            $this->generateProductReviews($deliveredOrders, $products);
        }
    }

    private function getWeightedRandomStatus($statuses, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = mt_rand(1, $totalWeight);

        $currentWeight = 0;
        foreach ($statuses as $status) {
            $currentWeight += $weights[$status];
            if ($random <= $currentWeight) {
                return $status;
            }
        }

        return 'delivered'; // Fallback
    }

    private function generateProductReviews($deliveredOrders, $products)
    {
        $this->command->info('⭐ Generating product reviews for delivered orders...');

        $reviewCount = 0;
        $reviewTemplates = [
            'Great product! Very satisfied with the quality.',
            'Excellent craftsmanship and beautiful design.',
            'Fast delivery and product exceeded expectations.',
            'Good value for money, would recommend.',
            'Perfect addition to my home, love it!',
            'High quality materials and construction.',
            'Beautiful piece, exactly as described.',
            'Very happy with this purchase, thank you!',
            'Great customer service and product quality.',
            'Love the design, fits perfectly in my space.',
        ];

        foreach ($deliveredOrders as $order) {
            $orderItems = $order->orderItems;

            // 60-80% chance of leaving a review for each item
            foreach ($orderItems as $orderItem) {
                if ($this->faker->boolean(70)) { // 70% chance
                    $rating = $this->faker->numberBetween(3, 5); // Mostly positive reviews (3-5 stars)
                    $reviewText = $this->faker->randomElement($reviewTemplates);

                    // Add some variation to reviews
                    if ($rating >= 4) {
                        $reviewText = $this->faker->randomElement([
                            'Amazing quality! '.$reviewText,
                            'Perfect! '.$reviewText,
                            'Love it! '.$reviewText,
                            'Excellent! '.$reviewText,
                        ]);
                    } else {
                        $reviewText = $this->faker->randomElement([
                            'Good product. '.$reviewText,
                            'Decent quality. '.$reviewText,
                            'Satisfied with purchase. '.$reviewText,
                        ]);
                    }

                    ProductReview::create([
                        'product_id' => $orderItem->product_id,
                        'user_id' => $order->user_id,
                        'order_id' => $order->id,
                        'rating' => $rating,
                        'title' => $this->faker->randomElement([
                            'Great product!',
                            'Excellent quality',
                            'Very satisfied',
                            'Good value',
                            'Beautiful design',
                            'Perfect fit',
                            'High quality',
                            'Love it!',
                            'Recommended',
                            'Exceeded expectations',
                        ]),
                        'review' => $reviewText,
                        'is_verified_purchase' => true,
                        'is_approved' => true,
                        'created_at' => $this->faker->dateTimeBetween($order->created_at, 'now'),
                        'updated_at' => now(),
                    ]);

                    $reviewCount++;
                }
            }
        }

        $this->command->info("✅ Created {$reviewCount} product reviews for delivered orders");
    }
}
