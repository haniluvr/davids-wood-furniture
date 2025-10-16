<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\PhilippineDataHelper;

class RealisticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
        if (Schema::hasTable('carts')) {
            Cart::query()->delete();
        }
        if (Schema::hasTable('wishlist_items')) {
            WishlistItem::query()->delete();
        }
        if (Schema::hasTable('wishlists')) {
            Wishlist::query()->delete();
        }
        User::query()->delete(); // Clear all users to start IDs from 1
        
        $this->command->info('✅ Cleared existing data');
        
        // Generate realistic users
        $this->generateUsers();
        
        // Generate shopping carts
        $this->generateShoppingCarts();
        
        // Generate wishlists
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
        
        $users = [];
        $userCount = 75; // Medium population: 50-100 users (using 75)
        
        for ($i = 0; $i < $userCount; $i++) {
            if ($i % 10 == 0) {
                $this->command->info("Creating user " . ($i + 1) . "/{$userCount}...");
            }
            // Get Filipino name
            $name = PhilippineDataHelper::getRandomFilipinoName();
            $firstName = $name['first_name'];
            $lastName = $name['last_name'];
            
            // Generate Filipino-based email
            $email = PhilippineDataHelper::generateFilipinoEmail($firstName, $lastName);
            
            // Get Philippine address (with fallback)
            try {
                $address = PhilippineDataHelper::getRandomPhilippineAddress();
            } catch (\Exception $e) {
                // Use fallback if API fails
                $address = [
                    'region' => fake()->randomElement(['National Capital Region (NCR)', 'Region III (Central Luzon)', 'Region IV-A (CALABARZON)']),
                    'province' => fake()->optional(0.7)->randomElement(['Bulacan', 'Laguna', 'Cavite', 'Rizal']),
                    'city' => fake()->randomElement(['Quezon City', 'Manila', 'Makati', 'Taguig', 'Pasig']),
                    'barangay' => fake()->randomElement(['Poblacion', 'Diliman', 'Ermita', 'Bel-Air']),
                    'zip_code' => fake()->numerify('####'),
                    'street' => fake()->streetName()
                ];
            }
            
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make('password123'),
                'username' => fake()->unique()->userName(),
                'phone' => fake()->optional(0.8)->passthrough(PhilippineDataHelper::getRandomPhoneNumber()),
                'region' => $address['region'],
                'street' => $address['street'],
                'barangay' => $address['barangay'],
                'city' => $address['city'],
                'province' => $address['province'],
                'zip_code' => $address['zip_code'],
                'newsletter_product_updates' => fake()->boolean(30),
                'newsletter_special_offers' => fake()->boolean(35),
                'email_verified_at' => fake()->optional(0.9)->dateTimeBetween('-1 year', 'now'),
                'created_at' => fake()->dateTimeBetween('-2 years', '-1 month'),
            ]);
            
            $users[] = $user;
        }
        
        $this->command->info("✅ Created {$userCount} users");
        return $users;
    }
    
    private function generateShoppingCarts()
    {
        $this->command->info('🛒 Generating shopping carts...');
        
        if (!Schema::hasTable('carts') || !Schema::hasTable('cart_items')) {
            $this->command->warn('⚠️ Cart tables do not exist, skipping cart generation');
            return;
        }
        
        $users = User::all();
        $products = Product::where('is_active', true)->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('⚠️ No products found for cart generation');
            return;
        }
        
        $cartCount = 0;
        $cartItemCount = 0;
        
        // 20-30% of users have active carts (15-22 users for 75 total)
        $usersWithCarts = $users->random(rand(15, 22));
        
        foreach ($usersWithCarts as $user) {
            $cart = Cart::create([
                'user_id' => $user->id,
                'session_id' => null,
                'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
                'updated_at' => now(),
            ]);
            
            $cartCount++;
            
            // 1-5 items per cart
            $itemCount = rand(1, 5);
            $cartProducts = $products->random($itemCount);
            
            foreach ($cartProducts as $product) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                    'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
                ]);
                
                $cartItemCount++;
            }
        }
        
        $this->command->info("✅ Created {$cartCount} carts with {$cartItemCount} items");
    }
    
    private function generateWishlists()
    {
        $this->command->info('❤️ Generating wishlists...');
        
        if (!Schema::hasTable('wishlists') || !Schema::hasTable('wishlist_items')) {
            $this->command->warn('⚠️ Wishlist tables do not exist, skipping wishlist generation');
            return;
        }
        
        $users = User::all();
        $products = Product::where('is_active', true)->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('⚠️ No products found for wishlist generation');
            return;
        }
        
        $wishlistCount = 0;
        $wishlistItemCount = 0;
        
        // 30-40% of users have wishlists (22-30 users for 75 total)
        $usersWithWishlists = $users->random(rand(22, 30));
        
        foreach ($usersWithWishlists as $user) {
            $wishlist = Wishlist::create([
                'user_id' => $user->id,
                'name' => fake()->randomElement(['My Wishlist', 'Favorites', 'Want to Buy', 'Dream Items', 'Future Purchases']),
                'is_public' => fake()->boolean(20), // 20% public
                'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            ]);
            
            $wishlistCount++;
            
            // 3-8 items per wishlist
            $itemCount = rand(3, 8);
            $wishlistProducts = $products->random($itemCount);
            
            foreach ($wishlistProducts as $product) {
                WishlistItem::create([
                    'wishlist_id' => $wishlist->id,
                    'product_id' => $product->id,
                    'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                ]);
                
                $wishlistItemCount++;
            }
        }
        
        $this->command->info("✅ Created {$wishlistCount} wishlists with {$wishlistItemCount} items");
    }
    
    private function generateOrders()
    {
        $this->command->info('📦 Generating orders...');
        
        if (!Schema::hasTable('orders') || !Schema::hasTable('order_items')) {
            $this->command->warn('⚠️ Order tables do not exist, skipping order generation');
            return;
        }
        
        $users = User::all();
        $products = Product::where('is_active', true)->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('⚠️ No products found for order generation');
            return;
        }
        
        $orderCount = 0;
        $orderItemCount = 0;
        
        // Generate medium population orders: 150 (medium population: 100-200)
        $totalOrders = 150;
        
        // Status distribution
        $statusDistribution = [
            'delivered' => 65,  // 65%
            'shipped' => 12,    // 12%
            'processing' => 10, // 10%
            'pending' => 8,     // 8%
            'cancelled' => 5,   // 5%
        ];
        
        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'cash_on_delivery'];
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
            
            $taxAmount = $subtotal * 0.1; // 10% tax
            $shippingCost = $subtotal > 100 ? 0 : 15; // Free shipping over $100
            $discountAmount = fake()->optional(0.2)->randomFloat(2, 5, 50) ?? 0; // 20% chance of discount, default to 0
            $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;
            
            // Create addresses using user's Philippine address
            $billingAddress = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'address_line_1' => $user->street,
                'address_line_2' => $user->barangay,
                'city' => $user->city,
                'state' => $user->province,
                'postal_code' => $user->zip_code,
                'country' => 'Philippines',
            ];
            
            // 20% chance of different shipping address
            $shippingAddress = fake()->boolean(20) ? $billingAddress : (function() use ($user) {
                $altAddress = PhilippineDataHelper::getRandomPhilippineAddress();
                return [
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'address_line_1' => $altAddress['street'],
                    'address_line_2' => $altAddress['barangay'],
                    'city' => $altAddress['city'],
                    'state' => $altAddress['province'],
                    'postal_code' => $altAddress['zip_code'],
                    'country' => 'Philippines',
                ];
            })();
            
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
                'currency' => 'USD',
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
    
    private function generateProductReviews()
    {
        $this->command->info('⭐ Generating product reviews...');
        
        if (!Schema::hasTable('product_reviews')) {
            $this->command->warn('⚠️ Product reviews table does not exist, skipping review generation');
            return;
        }
        
        $deliveredOrders = Order::where('status', 'delivered')
            ->with(['orderItems.product', 'user'])
            ->get();
        
        if ($deliveredOrders->isEmpty()) {
            $this->command->warn('⚠️ No delivered orders found for review generation');
            return;
        }
        
        $reviewCount = 0;
        
        // Rating distribution: 5★ (50%), 4★ (30%), 3★ (15%), 2★ (3%), 1★ (2%)
        $ratingDistribution = [5 => 50, 4 => 30, 3 => 15, 2 => 3, 1 => 2];
        
        $reviewTemplates = [
            5 => [
                ['title' => 'Excellent quality!', 'review' => 'This furniture exceeded my expectations. The craftsmanship is outstanding and it looks even better in person. Highly recommend!'],
                ['title' => 'Perfect addition to my home', 'review' => 'Absolutely love this piece! The quality is top-notch and it fits perfectly in my space. Great value for money.'],
                ['title' => 'Beautiful craftsmanship', 'review' => 'The attention to detail is remarkable. This is exactly what I was looking for and the delivery was fast too.'],
                ['title' => 'Best furniture purchase ever!', 'review' => 'I am extremely happy with this furniture. The design is timeless and the quality is exceptional. Will definitely buy from David\'s Wood again!'],
                ['title' => 'Exceeded expectations', 'review' => 'The product arrived in perfect condition and looks amazing. The wood finish is beautiful and the construction is solid.'],
                ['title' => 'Sulit na sulit!', 'review' => 'Ang ganda ng quality ng furniture na ito. Worth it ang price at ang delivery ay mabilis din. Recommended!'],
                ['title' => 'Perfect for Filipino homes', 'review' => 'This furniture fits perfectly in our Filipino home. The quality is excellent and the design is timeless.'],
            ],
            4 => [
                ['title' => 'Great purchase', 'review' => 'Very satisfied with this product. Good quality materials and sturdy construction. Only minor issue was the delivery took a bit longer than expected.'],
                ['title' => 'Good value for money', 'review' => 'Nice piece of furniture that looks good and functions well. Assembly was straightforward and the instructions were clear.'],
                ['title' => 'Happy with my purchase', 'review' => 'The furniture is well-made and looks great in my home. Would recommend to others looking for quality wooden furniture.'],
                ['title' => 'Solid product', 'review' => 'Good quality construction and attractive design. The wood finish is nice and it seems durable. Happy with the purchase overall.'],
                ['title' => 'Maganda ang quality', 'review' => 'Satisfied naman ako sa product na ito. Good quality at maganda ang design. Minor lang yung delivery time.'],
            ],
            3 => [
                ['title' => 'Decent but could be better', 'review' => 'The product is okay. It serves its purpose but I expected a bit more for the price. Assembly was straightforward though.'],
                ['title' => 'Average quality', 'review' => 'It\'s a decent piece of furniture but nothing extraordinary. The price seems fair for what you get.'],
                ['title' => 'Meets expectations', 'review' => 'The furniture is functional and looks okay. Not the best quality I\'ve seen but it does the job.'],
                ['title' => 'Okay lang', 'review' => 'Medyo okay naman yung product. Hindi naman masama pero hindi rin ganun kaganda. Fair price naman.'],
            ],
            2 => [
                ['title' => 'Disappointed', 'review' => 'The quality isn\'t what I expected for the price. Some issues with the finish and construction.'],
                ['title' => 'Could be better', 'review' => 'Not terrible but definitely not worth the full price. Some quality issues that should have been caught.'],
                ['title' => 'Hindi worth it', 'review' => 'Medyo disappointed ako sa quality. May mga issues sa finish at construction.'],
            ],
            1 => [
                ['title' => 'Poor quality', 'review' => 'Very disappointed with this purchase. The quality is much lower than expected and there were several issues.'],
                ['title' => 'Not recommended', 'review' => 'Waste of money. The product arrived damaged and the quality is subpar. Would not recommend.'],
                ['title' => 'Sayang ang pera', 'review' => 'Very disappointed sa purchase na ito. Poor quality at may damage pa. Hindi recommended.'],
            ],
        ];
        
        foreach ($deliveredOrders as $order) {
            foreach ($order->orderItems as $item) {
                if (!$item->product_id) continue;
                
                // Check if review already exists
                $existingReview = ProductReview::where('user_id', $order->user_id)
                    ->where('product_id', $item->product_id)
                    ->where('order_id', $order->id)
                    ->first();
                
                if ($existingReview) continue;
                
                // 70% chance of leaving a review
                if (fake()->boolean(70)) {
                    $rating = $this->getWeightedRandomRating($ratingDistribution);
                    $template = fake()->randomElement($reviewTemplates[$rating]);
                    
                    ProductReview::create([
                        'product_id' => $item->product_id,
                        'user_id' => $order->user_id,
                        'order_id' => $order->id,
                        'rating' => $rating,
                        'title' => $template['title'],
                        'review' => $template['review'],
                        'is_verified_purchase' => true,
                        'is_approved' => true,
                        'helpful_count' => fake()->numberBetween(0, 15),
                        'created_at' => fake()->dateTimeBetween($order->delivered_at, 'now'),
                    ]);
                    
                    $reviewCount++;
                }
            }
        }
        
        $this->command->info("✅ Created {$reviewCount} product reviews");
    }
    
    private function getWeightedRandomStatus($distribution)
    {
        $total = array_sum($distribution);
        $random = rand(1, $total);
        
        $current = 0;
        foreach ($distribution as $status => $weight) {
            $current += $weight;
            if ($random <= $current) {
                return $status;
            }
        }
        
        return 'delivered'; // fallback
    }
    
    private function getWeightedRandomRating($distribution)
    {
        $total = array_sum($distribution);
        $random = rand(1, $total);
        
        $current = 0;
        foreach ($distribution as $rating => $weight) {
            $current += $weight;
            if ($random <= $current) {
                return $rating;
            }
        }
        
        return 5; // fallback
    }
}
