<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductPopularitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📊 Calculating product popularity from wishlist and cart data...');
        
        // Clear existing popularity data
        DB::table('product_popularity')->truncate();
        
        // Get all products
        $products = Product::all();
        $totalProducts = $products->count();
        
        $this->command->info("Processing {$totalProducts} products...");
        
        $processed = 0;
        $popularityData = [];
        
        foreach ($products as $product) {
            // Count wishlist items for this product
            $wishlistCount = DB::table('wishlist_items')
                ->where('product_id', $product->id)
                ->count();
            
            // Count cart items for this product
            $cartCount = DB::table('cart_items')
                ->where('product_id', $product->id)
                ->count();
            
            // Calculate total popularity score
            $totalScore = $wishlistCount + $cartCount;
            
            $popularityData[] = [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'product_name' => $product->name,
                'wishlist_count' => $wishlistCount,
                'cart_count' => $cartCount,
                'total_popularity_score' => $totalScore,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $processed++;
            
            // Show progress every 25 products
            if ($processed % 25 == 0 || $processed == $totalProducts) {
                $this->command->info("Processed {$processed}/{$totalProducts} products...");
            }
        }
        
        // Insert all popularity data in batches
        $chunks = array_chunk($popularityData, 100);
        foreach ($chunks as $chunk) {
            DB::table('product_popularity')->insert($chunk);
        }
        
        // Show top 10 most popular products
        $topProducts = DB::table('product_popularity')
            ->orderBy('total_popularity_score', 'desc')
            ->limit(10)
            ->get(['product_name', 'sku', 'wishlist_count', 'cart_count', 'total_popularity_score']);
        
        $this->command->info('🏆 Top 10 Most Popular Products:');
        $this->command->info('=====================================');
        
        foreach ($topProducts as $index => $product) {
            $rank = $index + 1;
            $this->command->info("{$rank}. {$product->product_name} (SKU: {$product->sku})");
            $this->command->info("   Wishlist: {$product->wishlist_count} | Cart: {$product->cart_count} | Total: {$product->total_popularity_score}");
        }
        
        $this->command->info('✅ Product popularity calculation completed!');
    }
}
