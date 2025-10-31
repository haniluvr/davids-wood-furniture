<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class ProductReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalReviews = 600;

        // Get all order items that have been delivered or shipped (customers can only review items they received)
        // Load relationships to avoid N+1 queries
        $orderItems = OrderItem::whereHas('order', function ($query) {
            $query->whereIn('status', ['delivered', 'shipped']);
        })
            ->with(['order' => function ($query) {
                $query->select('id', 'user_id', 'created_at', 'shipped_at', 'delivered_at', 'status');
            }, 'product' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();

        if ($orderItems->isEmpty()) {
            $this->command->error('No order items found. Please run OrderSeeder first.');

            return;
        }

        if ($orderItems->count() < $totalReviews) {
            $this->command->warn('Only '.$orderItems->count().' order items found. Creating reviews for all available items.');
            $totalReviews = $orderItems->count();
        }

        // Review templates with titles and content
        $reviewTemplates = [
            // 5-star reviews
            [
                'rating' => 5,
                'titles' => [
                    'Excellent quality!',
                    'Perfect purchase!',
                    'Highly recommended!',
                    'Absolutely love it!',
                    'Exceeded my expectations!',
                ],
                'reviews' => [
                    'This product is absolutely fantastic! The quality is outstanding and it looks even better in person. Very happy with my purchase.',
                    'I am extremely satisfied with this purchase. The craftsmanship is excellent and the product arrived in perfect condition. Highly recommend!',
                    'Couldn\'t be happier with this product! The quality is top-notch and it fits perfectly in my space. Worth every peso spent.',
                    'Excellent product with great attention to detail. The finish is beautiful and it\'s very well constructed. Will definitely buy again.',
                    'This is exactly what I was looking for! The quality exceeds my expectations and the delivery was fast. Very pleased with everything.',
                ],
            ],
            // 4-star reviews
            [
                'rating' => 4,
                'titles' => [
                    'Very good quality',
                    'Good purchase overall',
                    'Satisfied with product',
                    'Nice furniture piece',
                    'Good value for money',
                ],
                'reviews' => [
                    'Good quality product that meets my expectations. The finish is nice and it\'s well built. Minor issues but nothing major.',
                    'I\'m quite satisfied with this purchase. The product is well made and looks good. Some minor improvements could be made.',
                    'Overall a good product. The quality is decent and it serves its purpose well. Would recommend with minor reservations.',
                    'Nice piece of furniture that fits my needs. The quality is good and the price is reasonable. Minor issues but overall happy.',
                    'Good value for money. The product is well constructed and looks nice. Could use some improvements but still satisfied.',
                ],
            ],
        ];

        // For ratings between 3.5-5, we'll use 4 and 5 stars
        // Distribution: 60% 5-star, 40% 4-star
        $createdReviews = 0;
        $now = time();

        // Shuffle order items to randomize selection
        $orderItemsArray = $orderItems->shuffle();

        $processed = 0;
        foreach ($orderItemsArray as $orderItem) {
            if ($processed >= $totalReviews) {
                break;
            }

            // Check if we already have a review for this order item
            $existingReview = ProductReview::where('order_id', $orderItem->order_id)
                ->where('product_id', $orderItem->product_id)
                ->where('user_id', $orderItem->order->user_id)
                ->first();

            if ($existingReview) {
                continue;
            }

            // Get order from loaded relationship
            $order = $orderItem->order;
            if (! $order || ! $order->user_id) {
                continue;
            }

            // Determine rating (60% chance of 5 stars, 40% chance of 4 stars)
            $rating = rand(1, 100) <= 60 ? 5 : 4;

            // Select review template based on rating
            $templateIndex = $rating === 5 ? 0 : 1;
            $template = $reviewTemplates[$templateIndex];
            $title = $template['titles'][array_rand($template['titles'])];
            $review = $template['reviews'][array_rand($template['reviews'])];

            // Customize review to mention product name occasionally
            if (rand(1, 100) <= 30 && $orderItem->product) {
                $productName = strtolower($orderItem->product->name);
                $review = str_replace('This product', 'This '.$productName, $review);
                $review = str_replace('this product', 'this '.$productName, $review);
            }

            // Generate review date (after order was delivered, within past 3 years)
            $orderCreatedAt = strtotime($order->created_at);
            $deliveredAt = $order->delivered_at ? strtotime($order->delivered_at) : ($order->shipped_at ? strtotime($order->shipped_at) + 86400 * 3 : $orderCreatedAt + 86400 * 7);

            // Review should be after delivery, but within reasonable time (1-90 days after delivery)
            $reviewCreatedAt = date('Y-m-d H:i:s', rand($deliveredAt + 86400, min($deliveredAt + 7776000, $now))); // 1-90 days after delivery
            $reviewUpdatedAt = date('Y-m-d H:i:s', rand(strtotime($reviewCreatedAt), min($now, strtotime($reviewCreatedAt) + 86400 * 7)));

            // Determine if review is approved (most reviews are approved)
            $isApproved = rand(1, 100) <= 85; // 85% approved

            // Some reviews have helpful counts
            $helpfulCount = rand(1, 100) <= 30 ? rand(0, 15) : 0;

            // Some reviews might have admin responses (for lower ratings or specific issues)
            $adminResponse = null;
            $respondedAt = null;
            if ($rating === 4 && rand(1, 100) <= 20) {
                $adminResponses = [
                    'Thank you for your feedback! We\'re glad you\'re satisfied with your purchase. If you have any concerns, please don\'t hesitate to contact us.',
                    'We appreciate your review! We\'re continuously working to improve our products based on customer feedback.',
                    'Thank you for taking the time to review. We value your input and will use it to enhance our products.',
                ];
                $adminResponse = $adminResponses[array_rand($adminResponses)];
                $respondedAt = date('Y-m-d H:i:s', rand(strtotime($reviewCreatedAt) + 86400, min(strtotime($reviewCreatedAt) + 604800, $now)));
            }

            ProductReview::create([
                'product_id' => $orderItem->product_id,
                'user_id' => $order->user_id,
                'order_id' => $orderItem->order_id,
                'rating' => $rating,
                'title' => $title,
                'review' => $review,
                'is_verified_purchase' => true, // All reviews from orders are verified
                'is_approved' => $isApproved,
                'helpful_count' => $helpfulCount,
                'admin_response' => $adminResponse,
                'responded_at' => $respondedAt,
                'created_at' => $reviewCreatedAt,
                'updated_at' => $reviewUpdatedAt,
            ]);

            $createdReviews++;
            $processed++;
        }

        $this->command->info("Created {$createdReviews} product reviews successfully!");
        $this->command->info('Reviews by rating:');
        for ($rating = 4; $rating <= 5; $rating++) {
            $count = ProductReview::where('rating', $rating)->count();
            $this->command->line("  - {$rating} stars: {$count}");
        }
        $this->command->info('Approved reviews: '.ProductReview::where('is_approved', true)->count());
        $this->command->info('Reviews with admin responses: '.ProductReview::whereNotNull('admin_response')->count());
    }
}
