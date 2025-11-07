<?php

namespace App\Listeners;

use App\Events\NewReview;
use App\Models\Admin;
use App\Models\Notification;

class CreateReviewNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(NewReview $event): void
    {
        $review = $event->review;

        // Get all active admins who should receive review notifications
        $admins = Admin::active()->get();

        foreach ($admins as $admin) {
            // Check if admin wants to receive product review notifications
            if (! $admin->wantsNotification('product_reviews')) {
                continue;
            }

            // Get customer name
            $customerName = 'Guest';
            if ($review->user) {
                $customerName = ($review->user->first_name ?? '').' '.($review->user->last_name ?? '');
                $customerName = trim($customerName) ?: ($review->user->email ?? 'Guest');
            }

            // Create notification for this admin
            Notification::createForAdmin(
                $admin,
                'New Product Review',
                "New review for '{$review->product->name}' by {$customerName}. Rating: {$review->rating}/5",
                'review',
                [
                    'review_id' => $review->id,
                    'product_id' => $review->product_id,
                    'product_name' => $review->product->name,
                    'customer_name' => $customerName,
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'link' => admin_route('reviews.index'),
                ]
            );
        }
    }
}
