<?php

namespace App\Listeners;

use App\Events\NewCustomerMessage;
use App\Models\Admin;
use App\Models\Notification;

class CreateMessageNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(NewCustomerMessage $event): void
    {
        $message = $event->message;

        // Get all active admins who should receive message notifications
        $admins = Admin::active()->get();

        foreach ($admins as $admin) {
            // Check if admin wants to receive customer message notifications
            if (! $admin->wantsNotification('customer_messages')) {
                continue;
            }

            // Create notification for this admin
            Notification::createForAdmin(
                $admin,
                'New Customer Message',
                "New message from {$message->name} ({$message->email}): ".\Str::limit($message->message, 100),
                'message',
                [
                    'message_id' => $message->id,
                    'customer_name' => $message->name,
                    'customer_email' => $message->email,
                    'message_preview' => \Str::limit($message->message, 200),
                    'link' => admin_route('messages.show', $message->id),
                ]
            );
        }
    }
}
