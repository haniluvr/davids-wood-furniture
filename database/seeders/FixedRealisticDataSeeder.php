<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\CartItem;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\PhilippineDataHelper;

class FixedRealisticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Fixed version that works with the current database structure
     * (no carts table, uses cart_items directly)
     */
    public function run(): void
    {
        $this->command->info('🧹 Clearing existing user-related data...');
        
        // Clear existing user-related data (using delete instead of truncate due to foreign key constraints)
        if (Schema::hasTable('product_reviews')) {
            ProductReview::query()->delete();
        }
        if (Schema::hasTable('order_items')) {
            OrderItem::query()->delete();
        }
        if (Schema::hasTable('orders')) {
            Order::query()->delete();
        }
        if (Schema::hasTable('cart_items')) {
            CartItem::query()->delete();
        }
        if (Schema::hasTable('wishlist_items')) {
            WishlistItem::query()->delete();
        }
        User::query()->delete(); // Clear all users to start IDs from 1
        
        $this->command->info('✅ Cleared existing data');
        
        // Generate realistic users
        $this->generateUsers();
        
        // Generate shopping carts (using new structure)
        $this->generateShoppingCarts();
        
        // Generate wishlists (using new structure)
        $this->generateWishlists();
        
        // Generate orders
        $this->generateOrders();
        
        // Generate product reviews
        $this->generateProductReviews();
        
        $this->command->info('🎉 Realistic data population completed!');
    }
    
    private function generateUsers()
    {
        $this->command->info('👥 Generating realistic users...');
        
        $users = collect();
        
        for ($i = 1; $i <= 75; $i++) {
            // Get Filipino name
            $name = PhilippineDataHelper::getRandomFilipinoName();
            
            // Get Philippine address
            $address = PhilippineDataHelper::getRandomPhilippineAddress();
            
            // Generate email
            $email = PhilippineDataHelper::generateFilipinoEmail($name['first_name'], $name['last_name']);
            
            // Get phone number
            $phone = PhilippineDataHelper::getRandomPhoneNumber();
            
            $user = User::create([
                'first_name' => $name['first_name'],
                'last_name' => $name['last_name'],
                'email' => $email,
                'email_verified_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
                'password' => Hash::make('password123'),
                'phone' => $phone,
                'street' => $address['street'],
                'barangay' => $address['barangay'],
                'city' => $address['city'],
                'province' => $address['province'],
                'zip_code' => $address['zip_code'],
                'region' => $address['region'],
                'newsletter_product_updates' => fake()->boolean(70), // 70% chance of true
                'newsletter_special_offers' => fake()->boolean(40), // 40% chance of true
                'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ]);
            
            $users->push($user);
        }
        
        $this->command->info("✅ Created {$users->count()} users");
        return $users;
    }
    
    private function generateShoppingCarts()
    {
        $this->command->info('🛒 Generating shopping carts...');
        
        if (!Schema::hasTable('cart_items')) {
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
        
        // 20-30% of users have active carts (15-22 users for 75 total)
        $usersWithCarts = $users->random(rand(15, 22));
        
        foreach ($usersWithCarts as $user) {
            // 1-5 items per cart
            $itemCount = rand(1, 5);
            $cartProducts = $products->random($itemCount);
            
            foreach ($cartProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->sale_price ?? $product->price;
                $totalPrice = $unitPrice * $quantity;
                
                CartItem::create([
                    'user_id' => $user->id,
                    'session_id' => null, // NULL for logged-in users
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
        
        $this->command->info("✅ Created {$cartItemCount} cart items for users");
    }
    
    private function generateWishlists()
    {
        $this->command->info('❤️ Generating wishlists...');
        
        if (!Schema::hasTable('wishlist_items')) {
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
        
        // 30-40% of users have wishlists (22-30 users for 75 total)
        $usersWithWishlists = $users->random(rand(22, 30));
        
        foreach ($usersWithWishlists as $user) {
            // 1-8 items per wishlist
            $itemCount = rand(1, 8);
            $wishlistProducts = $products->random($itemCount);
            
            foreach ($wishlistProducts as $product) {
                WishlistItem::create([
                    'user_id' => $user->id,
                    'session_id' => null, // NULL for logged-in users
                    'product_id' => $product->id,
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);
                
                $wishlistItemCount++;
            }
        }
        
        $this->command->info("✅ Created {$wishlistItemCount} wishlist items for users");
    }
    
    private function generateOrders()
    {
        $this->command->info('📦 Generating orders...');
        
        $users = User::all();
        $products = Product::where('is_active', true)->get();
        
        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('⚠️ No users or products found for order generation');
            return;
        }
        
        $totalOrders = 150;
        $orderCount = 0;
        $orderItemCount = 0;
        
        // Status distribution (weighted)
        $statusDistribution = [
            'delivered' => 50,  // 50% delivered
            'shipped' => 20,    // 20% shipped
            'processing' => 15, // 15% processing
            'pending' => 10,    // 10% pending
            'cancelled' => 5    // 5% cancelled
        ];
        
        $paymentMethods = ['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'gcash', 'maya'];
        $paymentStatuses = ['paid', 'pending', 'failed', 'refunded'];
        
        for ($i = 0; $i < $totalOrders; $i++) {
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
            
            $taxAmount = $subtotal * 0.12; // 12% tax (Philippine VAT)
            $shippingCost = $subtotal > 5000 ? 0 : 150; // Free shipping over ₱5,000
            $discountAmount = fake()->optional(0.2)->randomFloat(2, 5, 50) ?? 0; // 20% chance of discount, default to 0
            $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;
            
            // Create addresses using user's Philippine address
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
            
            $shippingAddress = $billingAddress; // Same as billing for simplicity
            
            // Create order with proper order number format
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
                'tracking_number' => null, // Will be generated after order is created
                'notes' => fake()->optional(0.3)->sentence(),
                'shipped_at' => in_array($status, ['shipped', 'delivered']) ? fake()->dateTimeBetween('-6 months', '-1 day') : null,
                'delivered_at' => $status === 'delivered' ? fake()->dateTimeBetween('-3 months', 'now') : null,
                'created_at' => fake()->dateTimeBetween('-12 months', 'now'),
            ]);
            
            // Generate tracking number for shipped/delivered orders using the proper format
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
        
        $this->command->info("✅ Created {$orderCount} orders with {$orderItemCount} items");
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
        
        return 'delivered'; // Fallback
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
            
            // 60-80% chance of leaving a review for each item
            foreach ($orderItems as $orderItem) {
                if (fake()->boolean(70)) { // 70% chance
                    $rating = fake()->numberBetween(3, 5); // Mostly positive reviews (3-5 stars)
                    $reviewText = fake()->randomElement($reviewTemplates);
                    
                    // Add some variation to reviews
                    if ($rating >= 4) {
                        $reviewText = fake()->randomElement([
                            'Amazing quality! ' . $reviewText,
                            'Perfect! ' . $reviewText,
                            'Love it! ' . $reviewText,
                            'Excellent! ' . $reviewText,
                        ]);
                    } else {
                        $reviewText = fake()->randomElement([
                            'Good product. ' . $reviewText,
                            'Decent quality. ' . $reviewText,
                            'Satisfied with purchase. ' . $reviewText,
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
                            'Exceeded expectations'
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
