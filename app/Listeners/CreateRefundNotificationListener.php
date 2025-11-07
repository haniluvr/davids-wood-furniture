<?php

namespace App\Listeners;

use App\Events\NewRefundRequest;
use App\Models\Admin;
use App\Models\Notification;

class CreateRefundNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(NewRefundRequest $event): void
    {
        $returnRepair = $event->returnRepair;
        $order = $returnRepair->order;

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

        // Get all active admins who should receive refund request notifications
        $admins = Admin::active()->get();

        foreach ($admins as $admin) {
            // Check if admin wants to receive refund request notifications
            if (! $admin->wantsNotification('refund_requests')) {
                continue;
            }

            // Create notification for this admin
            Notification::createForAdmin(
                $admin,
                'Refund Request',
                "Refund request received for Order #{$order->order_number}".($returnRepair->rma_number ? " with RMA #{$returnRepair->rma_number}" : ''),
                'refund',
                [
                    'return_repair_id' => $returnRepair->id,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'rma_number' => $returnRepair->rma_number,
                    'customer_name' => $customerName,
                    'type' => $returnRepair->type,
                    'link' => admin_route('orders.returns-repairs.show', $returnRepair->id),
                ]
            );
        }
    }
}
