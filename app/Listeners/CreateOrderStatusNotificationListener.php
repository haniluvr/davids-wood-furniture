<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Mail\OrderStatusChangedMail;
use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class CreateOrderStatusNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;
        $oldStatus = $event->oldStatus;
        $newStatus = $event->newStatus;

        // Load user relationship if not already loaded
        if (! $order->relationLoaded('user')) {
            $order->load('user');
        }

        // Get customer name
        $customerName = 'Guest';
        if ($order->user) {
            $customerName = ($order->user->first_name ?? '').' '.($order->user->last_name ?? '');
            $customerName = trim($customerName) ?: ($order->user->email ?? 'Guest');
        }

        // Create notification and send email to the user who owns the order
        if ($order->user) {
            // Create database notification
            Notification::createForUser(
                $order->user,
                'Order Status Updated',
                "Your order #{$order->order_number} status has been updated from ".ucfirst($oldStatus).' to '.ucfirst($newStatus),
                'order_status',
                [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'link' => route('account').'#orders', // Link to user's account orders page
                ]
            );

            // Send email notification
            try {
                Mail::to($order->user->email)->send(new OrderStatusChangedMail($order, $oldStatus, $newStatus));
            } catch (\Exception $e) {
                \Log::error('Failed to send order status change email', [
                    'order_id' => $order->id,
                    'user_id' => $order->user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Get all active admins who should receive order status notifications
        $admins = Admin::active()->get();

        foreach ($admins as $admin) {
            // Check if admin wants to receive order status update notifications
            if (! $admin->wantsNotification('order_status_updates')) {
                continue;
            }

            // Create notification for this admin
            Notification::createForAdmin(
                $admin,
                'Order Status Updated',
                "Order #{$order->order_number} status changed from ".ucfirst($oldStatus).' to '.ucfirst($newStatus),
                'order_status',
                [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'customer_name' => $customerName,
                    'link' => admin_route('orders.show', $order->id),
                ]
            );
        }
    }
}
