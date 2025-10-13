<?php

namespace Database\Seeders;

use App\Models\ProductReview;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get user ID 1 and their delivered orders
        $user = User::find(1);
        
        if (!$user) {
            $this->command->info('❌ User with ID 1 not found. Please create a user first.');
            return;
        }

        $deliveredOrders = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->with('orderItems.product')
            ->get();

        if ($deliveredOrders->isEmpty()) {
            $this->command->info('❌ No delivered orders found for user ID 1.');
            return;
        }

        $reviewsCreated = 0;
        $reviews = [
            [
                'rating' => 5,
                'title' => 'Excellent quality!',
                'review' => 'This furniture exceeded my expectations. The craftsmanship is outstanding and it looks even better in person. Highly recommend!'
            ],
            [
                'rating' => 4,
                'title' => 'Great purchase',
                'review' => 'Very satisfied with this product. Good quality materials and sturdy construction. Only minor issue was the delivery took a bit longer than expected.'
            ],
            [
                'rating' => 5,
                'title' => 'Perfect for my living room',
                'review' => 'Absolutely love this piece! It fits perfectly in my space and the wood finish is beautiful. Great value for money.'
            ],
            [
                'rating' => 3,
                'title' => 'Decent but could be better',
                'review' => 'The product is okay. It serves its purpose but I expected a bit more for the price. Assembly was straightforward though.'
            ],
            [
                'rating' => 5,
                'title' => 'Best furniture purchase ever!',
                'review' => 'I am extremely happy with this furniture. The design is timeless and the quality is top-notch. Will definitely buy from David\'s Wood again!'
            ],
        ];

        $reviewIndex = 0;

        foreach ($deliveredOrders as $order) {
            foreach ($order->orderItems as $item) {
                // Skip if product doesn't exist
                if (!$item->product_id) {
                    continue;
                }

                // Check if review already exists
                $existingReview = ProductReview::where('user_id', $user->id)
                    ->where('product_id', $item->product_id)
                    ->where('order_id', $order->id)
                    ->first();

                if ($existingReview) {
                    continue;
                }

                // Use a review from the array (cycle through them)
                $reviewData = $reviews[$reviewIndex % count($reviews)];
                
                ProductReview::create([
                    'product_id' => $item->product_id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'rating' => $reviewData['rating'],
                    'title' => $reviewData['title'],
                    'review' => $reviewData['review'],
                    'is_verified_purchase' => true,
                    'is_approved' => true, // Auto-approve for demo
                ]);

                $reviewsCreated++;
                $reviewIndex++;

                $this->command->info("✅ Created review for product ID {$item->product_id}");
            }
        }

        $this->command->info("✅ Created {$reviewsCreated} product reviews for user ID 1");
    }
}
