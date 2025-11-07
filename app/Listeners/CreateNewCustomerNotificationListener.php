<?php

namespace App\Listeners;

use App\Events\NewCustomerRegistered;
use App\Models\Admin;
use App\Models\Notification;

class CreateNewCustomerNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(NewCustomerRegistered $event): void
    {
        $user = $event->user;

        // Get all active admins who should receive new customer notifications
        $admins = Admin::active()->get();

        foreach ($admins as $admin) {
            // Check if admin wants to receive new customer notifications
            if (! $admin->wantsNotification('new_customers')) {
                continue;
            }

            // Create notification for this admin
            Notification::createForAdmin(
                $admin,
                'New Customer Registration',
                "New customer registered: {$user->first_name} {$user->last_name} ({$user->email})",
                'customer',
                [
                    'user_id' => $user->id,
                    'customer_name' => $user->first_name.' '.$user->last_name,
                    'customer_email' => $user->email,
                    'link' => admin_route('users.show', $user->id),
                ]
            );
        }
    }
}
