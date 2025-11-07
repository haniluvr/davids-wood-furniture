<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdminNotificationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('🔔 Creating admin notifications for each notification type...');

        // Get Hannah Marquez (ID 2)
        $admin = Admin::find(2);

        if (! $admin) {
            $this->command->error('Admin with ID 2 (Hannah Marquez) not found!');
            $this->command->info('Available admins:');
            $admins = Admin::all();
            foreach ($admins as $a) {
                $this->command->info("  - ID: {$a->id}, Name: {$a->first_name} {$a->last_name}, Email: {$a->email}");
            }

            return;
        }

        $this->command->info("Using admin: {$admin->first_name} {$admin->last_name} (ID: {$admin->id}, Email: {$admin->email})");

        // Clear existing notifications for this admin
        Notification::where('recipient_type', 'admin')
            ->where('recipient_id', $admin->id)
            ->delete();

        // Get sample data
        $order = Order::first();
        $product = Product::first();
        $user = User::first();
        $contactMessage = ContactMessage::first();
        $review = ProductReview::first();

        $notifications = [];

        // 1. New Order Notification
        if ($order && $user) {
            $notifications[] = [
                'type' => 'order',
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'title' => 'New Order Received',
                'message' => "New order #{$order->order_number} has been placed by ".($user->first_name.' '.$user->last_name).'. Total: '.number_format($order->total_amount ?? 0, 2),
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $user->first_name.' '.$user->last_name,
                    'total' => $order->total_amount ?? 0,
                    'status' => $order->status,
                    'link' => '/admin/orders/'.$order->id,
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subMinutes(10),
                'created_at' => Carbon::now()->subMinutes(10),
            ];
        }

        // 2. Order Status Update Notification
        if ($order) {
            $notifications[] = [
                'type' => 'order_status',
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'title' => 'Order Status Updated',
                'message' => "Order #{$order->order_number} status changed from Pending to Processing",
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'old_status' => 'pending',
                    'new_status' => $order->status ?? 'processing',
                    'customer_name' => $order->user ? ($order->user->first_name.' '.$order->user->last_name) : 'Guest',
                    'link' => '/admin/orders/'.$order->id,
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subMinutes(20),
                'created_at' => Carbon::now()->subMinutes(20),
            ];
        }

        // 3. Customer Message Notification
        if ($contactMessage) {
            $notifications[] = [
                'type' => 'message',
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'title' => 'New Customer Message',
                'message' => "New message from {$contactMessage->name} ({$contactMessage->email}): ".\Str::limit($contactMessage->message, 100),
                'data' => [
                    'message_id' => $contactMessage->id,
                    'customer_name' => $contactMessage->name,
                    'customer_email' => $contactMessage->email,
                    'message_preview' => \Str::limit($contactMessage->message, 200),
                    'link' => '/admin/messages/'.$contactMessage->id,
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subMinutes(30),
                'created_at' => Carbon::now()->subMinutes(30),
            ];
        }

        // 4. Low Stock Alert Notification
        if ($product) {
            $notifications[] = [
                'type' => 'inventory',
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'title' => 'Low Stock Alert',
                'message' => "Product '{$product->name}' (SKU: {$product->sku}) is running low on stock. Current quantity: 5 (threshold: 10)",
                'data' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'stock_quantity' => 5,
                    'threshold' => 10,
                    'link' => '/admin/inventory',
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subHours(1),
                'created_at' => Carbon::now()->subHours(1),
            ];
        }

        // 5. New Customer Notification
        if ($user) {
            $notifications[] = [
                'type' => 'customer',
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'title' => 'New Customer Registration',
                'message' => "New customer registered: {$user->first_name} {$user->last_name} ({$user->email})",
                'data' => [
                    'user_id' => $user->id,
                    'customer_name' => $user->first_name.' '.$user->last_name,
                    'customer_email' => $user->email,
                    'link' => '/admin/all-customers/'.$user->id,
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subHours(2),
                'created_at' => Carbon::now()->subHours(2),
            ];
        }

        // 6. Product Review Notification
        if ($review && $review->product) {
            $notifications[] = [
                'type' => 'review',
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'title' => 'New Product Review',
                'message' => "New review for '{$review->product->name}' by {$review->user->name}. Rating: {$review->rating}/5",
                'data' => [
                    'review_id' => $review->id,
                    'product_id' => $review->product_id,
                    'product_name' => $review->product->name,
                    'customer_name' => $review->user->name,
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'link' => '/admin/reviews',
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subHours(3),
                'created_at' => Carbon::now()->subHours(3),
            ];
        }

        // 7. Refund Request Notification
        if ($order && $order->rma_number) {
            $notifications[] = [
                'type' => 'refund',
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'title' => 'Refund Request',
                'message' => "Refund request received for Order #{$order->order_number} with RMA #{$order->rma_number}",
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'rma_number' => $order->rma_number,
                    'customer_name' => $order->user ? ($order->user->first_name.' '.$order->user->last_name) : 'Guest',
                    'link' => '/admin/orders/'.$order->id,
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subHours(4),
                'created_at' => Carbon::now()->subHours(4),
            ];
        } elseif ($order) {
            // Create a refund notification even without RMA number
            $notifications[] = [
                'type' => 'refund',
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'title' => 'Refund Request',
                'message' => "Refund request received for Order #{$order->order_number}",
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user ? ($order->user->first_name.' '.$order->user->last_name) : 'Guest',
                    'link' => '/admin/orders/'.$order->id,
                ],
                'status' => 'sent',
                'sent_at' => Carbon::now()->subHours(4),
                'created_at' => Carbon::now()->subHours(4),
            ];
        }

        // Create all notifications
        foreach ($notifications as $notificationData) {
            $notification = new Notification;
            $notification->type = $notificationData['type'];
            $notification->recipient_type = $notificationData['recipient_type'];
            $notification->recipient_id = $notificationData['recipient_id'];
            $notification->title = $notificationData['title'];
            $notification->message = $notificationData['message'];
            $notification->data = $notificationData['data'];
            $notification->status = $notificationData['status'];
            $notification->sent_at = $notificationData['sent_at'];
            $notification->created_at = $notificationData['created_at'];
            $notification->save();
        }

        $this->command->info('✅ Successfully created '.count($notifications).' admin notifications!');
        $this->command->info('📋 Notification types created:');

        $types = collect($notifications)->pluck('type')->unique();
        foreach ($types as $type) {
            $count = collect($notifications)->where('type', $type)->count();
            $this->command->info("   - {$type}: {$count}");
        }
    }
}
