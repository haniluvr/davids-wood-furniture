<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class TestNotificationsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $userId = 76;
        
        // Check if user exists
        $user = User::find($userId);
        if (!$user) {
            $this->command->error("User with ID {$userId} not found!");
            return;
        }

        $this->command->info("Creating test notifications for user {$userId} ({$user->username})...");

        // Clear existing notifications for this user
        Notification::where('recipient_type', 'user')
            ->where('recipient_id', $userId)
            ->delete();

        // Create various types of notifications
        $notifications = [
            // Unread notifications (sent status)
            [
                'type' => 'order',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'Order Confirmed',
                'message' => 'Your order #ORD-2024-1234 has been confirmed and is being processed. Thank you for your purchase!',
                'data' => json_encode(['order_id' => 1234, 'order_number' => 'ORD-2024-1234']),
                'status' => 'sent',
                'sent_at' => Carbon::now()->subMinutes(5),
                'created_at' => Carbon::now()->subMinutes(5),
            ],
            [
                'type' => 'shipping',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'Order Shipped',
                'message' => 'Great news! Your order #ORD-2024-1200 has been shipped and is on its way to you.',
                'data' => json_encode(['order_id' => 1200, 'tracking_number' => 'TRACK123456']),
                'status' => 'sent',
                'sent_at' => Carbon::now()->subHours(2),
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'type' => 'system',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'Special Offer Just for You!',
                'message' => 'Get 20% off on your next purchase. Use code SPECIAL20 at checkout. Offer valid until the end of the month.',
                'data' => json_encode(['promo_code' => 'SPECIAL20', 'discount' => 20]),
                'status' => 'sent',
                'sent_at' => Carbon::now()->subHours(4),
                'created_at' => Carbon::now()->subHours(4),
            ],
            [
                'type' => 'payment',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'Payment Successful',
                'message' => 'Your payment of ₱15,999.00 has been successfully processed for order #ORD-2024-1234.',
                'data' => json_encode(['amount' => 15999.00, 'order_id' => 1234]),
                'status' => 'sent',
                'sent_at' => Carbon::now()->subMinutes(10),
                'created_at' => Carbon::now()->subMinutes(10),
            ],

            // Read notifications
            [
                'type' => 'order',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'Order Delivered',
                'message' => 'Your order #ORD-2024-1150 has been delivered successfully. We hope you enjoy your purchase!',
                'data' => json_encode(['order_id' => 1150, 'delivered_at' => Carbon::now()->subDays(2)->toDateTimeString()]),
                'status' => 'read',
                'sent_at' => Carbon::now()->subDays(2),
                'read_at' => Carbon::now()->subDays(1)->subHours(5),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'type' => 'system',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'Welcome Back!',
                'message' => 'We noticed you haven\'t shopped with us in a while. Check out our new collection of handcrafted furniture.',
                'data' => json_encode(['campaign' => 'reengagement']),
                'status' => 'read',
                'sent_at' => Carbon::now()->subDays(5),
                'read_at' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'type' => 'email',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'Review Your Recent Purchase',
                'message' => 'How was your experience with the Classic Oak Dining Table? We\'d love to hear your feedback!',
                'data' => json_encode(['product_id' => 45, 'order_id' => 1150]),
                'status' => 'read',
                'sent_at' => Carbon::now()->subDays(3),
                'read_at' => Carbon::now()->subDays(3)->subHours(2),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'type' => 'shipping',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'Out for Delivery',
                'message' => 'Your order #ORD-2024-1150 is out for delivery today. Please be available to receive your package.',
                'data' => json_encode(['order_id' => 1150, 'estimated_time' => '2-5 PM']),
                'status' => 'read',
                'sent_at' => Carbon::now()->subDays(2)->subHours(6),
                'read_at' => Carbon::now()->subDays(2)->subHours(4),
                'created_at' => Carbon::now()->subDays(2)->subHours(6),
            ],

            // Older unread notification
            [
                'type' => 'system',
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'title' => 'New Products Added',
                'message' => 'Check out our latest collection of modern minimalist furniture. Perfect for contemporary homes!',
                'data' => json_encode(['category' => 'modern', 'product_count' => 12]),
                'status' => 'sent',
                'sent_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info("Successfully created " . count($notifications) . " test notifications for user {$userId}!");
        $this->command->info("Unread notifications: " . collect($notifications)->where('status', 'sent')->count());
        $this->command->info("Read notifications: " . collect($notifications)->where('status', 'read')->count());
    }
}

