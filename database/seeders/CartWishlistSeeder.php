<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\DB;

class CartWishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🛒 Generating shopping carts and wishlists...');
        
        // Clear existing data
        DB::table('cart_items')->delete();
        DB::table('wishlist_items')->delete();
        
        $users = User::all();
        $products = Product::all();
        
        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('⚠️ No users or products found. Skipping cart/wishlist generation.');
            return;
        }
        
        $this->command->info("Found {$users->count()} users and {$products->count()} products");
        
        // Generate shopping cart items (30-40% of users)
        $usersWithCarts = $users->random(rand(22, 30)); // 30-40% of 75 users
        $this->generateCartItems($usersWithCarts, $products);
        
        // Generate wishlist items (40-50% of users)
        $usersWithWishlists = $users->random(rand(30, 37)); // 40-50% of 75 users
        $this->generateWishlistItems($usersWithWishlists, $products);
        
        $this->command->info('✅ Cart and wishlist generation completed!');
    }
    
    private function generateCartItems($users, $products)
    {
        $this->command->info('🛒 Creating cart items...');
        
        foreach ($users as $user) {
            // Add 1-5 random products to user's cart
            $cartItemCount = rand(1, 5);
            $randomProducts = $products->random($cartItemCount);
            
            foreach ($randomProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->sale_price ?? $product->price;
                $totalPrice = $unitPrice * $quantity;
                
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_data' => [
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'image' => $product->image ?? null,
                    ],
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
            
            $this->command->info("Created {$cartItemCount} cart items for {$user->first_name} {$user->last_name}");
        }
        
        $totalCartItems = CartItem::count();
        $this->command->info("✅ Created {$totalCartItems} total cart items");
    }
    
    private function generateWishlistItems($users, $products)
    {
        $this->command->info('❤️ Creating wishlist items...');
        
        foreach ($users as $user) {
            // Add 2-8 random products to user's wishlist
            $wishlistItemCount = rand(2, 8);
            $randomProducts = $products->random($wishlistItemCount);
            
            foreach ($randomProducts as $product) {
                WishlistItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                ]);
            }
            
            $this->command->info("Created {$wishlistItemCount} wishlist items for {$user->first_name} {$user->last_name}");
        }
        
        $totalWishlistItems = WishlistItem::count();
        $this->command->info("✅ Created {$totalWishlistItems} total wishlist items");
    }
}
