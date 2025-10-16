<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Support\Facades\DB;

class UpdateProductRatingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⭐ Updating product ratings from reviews...');
        
        $products = Product::all();
        $updatedCount = 0;
        
        foreach ($products as $product) {
            // Get all reviews for this product
            $reviews = ProductReview::where('product_id', $product->id)->get();
            
            if ($reviews->count() > 0) {
                // Calculate average rating
                $averageRating = $reviews->avg('rating');
                $reviewCount = $reviews->count();
                
                // Update product with new rating data
                $product->update([
                    'average_rating' => round($averageRating, 1),
                    'review_count' => $reviewCount,
                ]);
                
                $updatedCount++;
                
                $this->command->info("Updated {$product->name} (SKU: {$product->sku}) - Rating: {$averageRating} ({$reviewCount} reviews)");
            }
        }
        
        $this->command->info("✅ Updated ratings for {$updatedCount} products");
        
        // Show top rated products
        $topRatedProducts = Product::where('review_count', '>', 0)
            ->orderBy('average_rating', 'desc')
            ->orderBy('review_count', 'desc')
            ->take(10)
            ->get(['name', 'sku', 'average_rating', 'review_count']);
        
        $this->command->info('🏆 Top 10 Highest Rated Products:');
        $this->command->info('=====================================');
        
        foreach ($topRatedProducts as $index => $product) {
            $rank = $index + 1;
            $stars = str_repeat('⭐', round($product->average_rating));
            $this->command->info("{$rank}. {$product->name} (SKU: {$product->sku})");
            $this->command->info("   Rating: {$product->average_rating}/5 {$stars} ({$product->review_count} reviews)");
        }
    }
}
