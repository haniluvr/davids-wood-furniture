<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SafeRealisticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Safe version that only adds data without clearing existing data
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Safe Realistic Data Seeder...');
        $this->command->info('⚠️  This seeder will ADD data without clearing existing data');

        // Check if we already have users to avoid duplicates
        $existingUserCount = User::count();
        if ($existingUserCount > 0) {
            $this->command->info("📊 Found {$existingUserCount} existing users - will add additional data only");
        }

        // Generate additional users (only if we have less than 100 users)
        if ($existingUserCount < 100) {
            $this->generateAdditionalUsers();
        }

        // Generate shopping carts
        $this->generateShoppingCarts();

        // Generate wishlists
        $this->generateWishlists();

        // Generate additional orders (only if we have less than 200 orders)
        $existingOrderCount = Order::count();
        if ($existingOrderCount < 200) {
            $this->generateAdditionalOrders();
        }

        // Generate product reviews
        $this->generateProductReviews();

        $this->command->info('🎉 Safe realistic data population completed!');
    }

    private function generateAdditionalUsers()
    {
        $this->command->info('👥 Generating additional users...');

        $existingUserCount = User::count();
        $usersToCreate = min(25, 100 - $existingUserCount); // Add up to 25 more users, max 100 total

        if ($usersToCreate <= 0) {
            $this->command->info('✅ Sufficient users already exist, skipping user generation');
            return;
        }

        $users = collect();

        for ($i = 1; $i <= $usersToCreate; $i++) {
            $user = User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
                'password' => Hash::make('password123'),
                'phone' => fake()->phoneNumber(),
                'street' => fake()->streetAddress(),
                'barangay' => fake()->randomElement(['Poblacion', 'San Agustin', 'Poblacion 1', 'Salitran', 'Diliman', 'Ermita']),
                'city' => fake()->randomElement(['Manila', 'Quezon City', 'Makati', 'Taguig', 'Pasig', 'Mandaluyong', 'San Juan', 'Marikina']),
                'province' => fake()->randomElement(['Metro Manila', 'Bulacan', 'Pampanga', 'Laguna', 'Cavite', 'Batangas']),
                'zip_code' => fake()->numerify('####'),
                'region' => fake()->randomElement(['National Capital Region (NCR)', 'Region III (Central Luzon)', 'Region IV-A (CALABARZON)']),
                'newsletter_product_updates' => fake()->boolean(70),
                'newsletter_special_offers' => fake()->boolean(40),
                'marketing_emails' => fake()->boolean(30),
                'newsletter_subscribed' => fake()->boolean(50),
                'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ]);

            $users->push($user);
        }

        $this->command->info("✅ Created {$users->count()} additional users");
    }

    private function generateShoppingCarts()
    {
        $this->command->info('🛒 Generating shopping carts...');

        if (! Schema::hasTable('cart_items')) {
            $this->command->warn('⚠️ Cart items table does not exist, skipping cart generation');
            return;
        }

        $users = User::all();
        $products = Product::where('is_active', true)->get();

        if ($products->isEmpty()) {
            $this->command->warn('⚠️ No products found for cart generation');
            return;
        }

        $cartItemCount = 0;

        // 20-30% of users have active carts
        $usersWithCarts = $users->random(rand(15, 22));

        foreach ($usersWithCarts as $user) {
            // 1-5 items per cart
            $itemCount = rand(1, 5);
            $cartProducts = $products->random($itemCount);

            foreach ($cartProducts as $product) {
                // Check if this user already has this product in their cart
                $existingCartItem = CartItem::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (!$existingCartItem) {
                    $quantity = rand(1, 3);
                    $unitPrice = $product->sale_price ?? $product->price;
                    $totalPrice = $unitPrice * $quantity;

                    CartItem::create([
                        'user_id' => $user->id,
                        'session_id' => null,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'product_data' => [
                            'name' => $product->name,
                            'image' => $product->images[0] ?? null,
                            'category' => $product->category->name ?? null,
                        ],
                        'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
                        'updated_at' => now(),
                    ]);

                    $cartItemCount++;
                }
            }
        }

        $this->command->info("✅ Created {$cartItemCount} cart items for users");
    }

    private function generateWishlists()
    {
        $this->command->info('❤️ Generating wishlists...');

        if (! Schema::hasTable('wishlist_items')) {
            $this->command->warn('⚠️ Wishlist items table does not exist, skipping wishlist generation');
            return;
        }

        $users = User::all();
        $products = Product::where('is_active', true)->get();

        if ($products->isEmpty()) {
            $this->command->warn('⚠️ No products found for wishlist generation');
            return;
        }

        $wishlistItemCount = 0;

        // 30-40% of users have wishlists
        $usersWithWishlists = $users->random(rand(22, 30));

        foreach ($usersWithWishlists as $user) {
            // 1-8 items per wishlist
            $itemCount = rand(1, 8);
            $wishlistProducts = $products->random($itemCount);

            foreach ($wishlistProducts as $product) {
                // Check if this user already has this product in their wishlist
                $existingWishlistItem = WishlistItem::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (!$existingWishlistItem) {
                    WishlistItem::create([
                        'user_id' => $user->id,
                        'session_id' => null,
                        'product_id' => $product->id,
                        'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                    ]);

                    $wishlistItemCount++;
                }
            }
        }

        $this->command->info("✅ Created {$wishlistItemCount} wishlist items for users");
    }

    private function generateAdditionalOrders()
    {
        $this->command->info('📦 Generating additional orders...');

        $users = User::all();
        $products = Product::where('is_active', true)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('⚠️ No users or products found for order generation');
            return;
        }

        $existingOrderCount = Order::count();
        $ordersToCreate = min(50, 200 - $existingOrderCount); // Add up to 50 more orders, max 200 total

        if ($ordersToCreate <= 0) {
            $this->command->info('✅ Sufficient orders already exist, skipping order generation');
            return;
        }

        $orderCount = 0;
        $orderItemCount = 0;

        // Status distribution
        $statusDistribution = [
            'delivered' => 50,
            'shipped' => 20,
            'processing' => 15,
            'pending' => 10,
            'cancelled' => 5,
        ];

        $paymentMethods = ['credit_card', 'debit_card', 'paypal', 'bank_transfer'];
        $paymentStatuses = ['paid', 'pending', 'failed', 'refunded'];

        for ($i = 0; $i < $ordersToCreate; $i++) {
            $user = $users->random();

            // Determine status based on distribution
            $status = $this->getWeightedRandomStatus($statusDistribution);
            $paymentStatus = $status === 'cancelled' ? 'failed' :
                           ($status === 'pending' ? 'pending' : 'paid');

            // Calculate order details
            $subtotal = 0;
            $orderItems = [];
            $numItems = rand(1, 5);
            $orderProducts = $products->random($numItems);

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->sale_price ?? $product->price;
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
                        'image' => $product->images[0] ?? null,
                        'category' => $product->category->name ?? null,
                    ],
                ];
            }

            $taxAmount = $subtotal * 0.12; // 12% tax
            $shippingCost = $subtotal > 5000 ? 0 : 150;
            $discountAmount = fake()->optional(0.2)->randomFloat(2, 5, 50) ?? 0;
            $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;

            // Create addresses
            $billingAddress = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
                'street' => $user->street ?? 'N/A',
                'barangay' => $user->barangay ?? 'N/A',
                'city' => $user->city ?? 'N/A',
                'province' => $user->province ?? 'N/A',
                'zip_code' => $user->zip_code ?? 'N/A',
                'region' => $user->region ?? 'N/A',
            ];

            $shippingAddress = $billingAddress;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => $status,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'PHP',
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,
                'payment_method' => fake()->randomElement($paymentMethods),
                'payment_status' => $paymentStatus,
                'shipping_method' => $shippingCost > 0 ? 'standard' : 'free',
                'tracking_number' => null,
                'notes' => fake()->optional(0.3)->sentence(),
                'shipped_at' => in_array($status, ['shipped', 'delivered']) ? fake()->dateTimeBetween('-6 months', '-1 day') : null,
                'delivered_at' => $status === 'delivered' ? fake()->dateTimeBetween('-3 months', 'now') : null,
                'created_at' => fake()->dateTimeBetween('-12 months', 'now'),
            ]);

            // Generate tracking number for shipped/delivered orders
            if (in_array($status, ['shipped', 'delivered'])) {
                $order->update(['tracking_number' => $order->generateTrackingNumber()]);
            }

            $orderCount++;

            // Create order items
            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
                $orderItemCount++;
            }
        }

        $this->command->info("✅ Created {$orderCount} additional orders with {$orderItemCount} items");
    }

    private function getWeightedRandomStatus($statusDistribution)
    {
        $totalWeight = array_sum($statusDistribution);
        $random = mt_rand(1, $totalWeight);

        $currentWeight = 0;
        foreach ($statusDistribution as $status => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $status;
            }
        }

        return 'delivered';
    }

    private function generateProductReviews()
    {
        $this->command->info('⭐ Generating product reviews...');

        $deliveredOrders = Order::where('status', 'delivered')
            ->with(['orderItems.product', 'user'])
            ->get();

        if ($deliveredOrders->isEmpty()) {
            $this->command->warn('⚠️ No delivered orders found for review generation');
            return;
        }

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

            // 70% chance of leaving a review for each item
            foreach ($orderItems as $orderItem) {
                // Check if this user already reviewed this product for this order
                $existingReview = ProductReview::where('user_id', $order->user_id)
                    ->where('product_id', $orderItem->product_id)
                    ->where('order_id', $order->id)
                    ->first();

                if (!$existingReview && fake()->boolean(70)) {
                    $rating = fake()->numberBetween(3, 5);
                    $reviewText = fake()->randomElement($reviewTemplates);

                    if ($rating >= 4) {
                        $reviewText = fake()->randomElement([
                            'Amazing quality! '.$reviewText,
                            'Perfect! '.$reviewText,
                            'Love it! '.$reviewText,
                            'Excellent! '.$reviewText,
                        ]);
                    } else {
                        $reviewText = fake()->randomElement([
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
                        'title' => fake()->randomElement([
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
                        'created_at' => fake()->dateTimeBetween($order->created_at, 'now'),
                        'updated_at' => now(),
                    ]);

                    $reviewCount++;
                }
            }
        }

        $this->command->info("✅ Created {$reviewCount} product reviews for delivered orders");
    }
}
