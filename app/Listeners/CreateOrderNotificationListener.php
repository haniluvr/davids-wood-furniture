<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Admin;
use App\Models\Notification;

class CreateOrderNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        \Log::info('CreateOrderNotificationListener called', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        // Load user relationship if not already loaded
        if (! $order->relationLoaded('user')) {
            $order->load('user');
        }

        // Create notification for the user who placed the order
        if ($order->user) {
            Notification::createForUser(
                $order->user,
                'Order Confirmed',
                "Your order #{$order->order_number} has been confirmed and is being processed.",
                'order',
                [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'link' => route('account').'#orders',
                ]
            );
        }

        // Get all active admins who should receive order notifications
        $admins = Admin::active()->get();

        \Log::info('Active admins found', [
            'count' => $admins->count(),
            'admin_ids' => $admins->pluck('id')->toArray(),
        ]);

        foreach ($admins as $admin) {
            // Check if admin wants to receive new order notifications
            $wantsNotification = $admin->wantsNotification('new_orders');

            \Log::info('Checking admin notification preference', [
                'admin_id' => $admin->id,
                'wants_notification' => $wantsNotification,
            ]);

            if (! $wantsNotification) {
                continue;
            }

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

            // Create notification for this admin
            $notification = Notification::createForAdmin(
                $admin,
                'New Order Received',
                "New order #{$order->order_number} has been placed by {$customerName}. Total: ".number_format($order->total_amount ?? 0, 2),
                'order',
                [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $customerName,
                    'total' => $order->total_amount ?? 0,
                    'status' => $order->status,
                    'link' => admin_route('orders.show', $order->id),
                ]
            );

            \Log::info('Notification created', [
                'notification_id' => $notification->id,
                'admin_id' => $admin->id,
                'status' => $notification->status,
            ]);
        }
    }
}
