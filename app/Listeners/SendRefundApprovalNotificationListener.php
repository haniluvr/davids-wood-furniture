<?php

namespace App\Listeners;

use App\Events\RefundRequestApproved;
use App\Mail\RefundApprovedMail;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRefundApprovalNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(RefundRequestApproved $event): void
    {
        $returnRepair = $event->returnRepair;

        // Load user relationship if not already loaded
        if (! $returnRepair->relationLoaded('user')) {
            $returnRepair->load('user');
        }

        if (! $returnRepair->user) {
            Log::warning('RefundRequestApproved event: User not found', [
                'return_repair_id' => $returnRepair->id,
            ]);

            return;
        }

        // Load order relationship if not already loaded
        if (! $returnRepair->relationLoaded('order')) {
            $returnRepair->load('order');
        }

        // Create database notification
        Notification::createForUser(
            $returnRepair->user,
            'Refund Request Approved',
            "Your refund request {$returnRepair->rma_number} for order #{$returnRepair->order->order_number} has been approved.",
            'refund_approved',
            [
                'rma_number' => $returnRepair->rma_number,
                'order_id' => $returnRepair->order_id,
                'order_number' => $returnRepair->order->order_number ?? null,
                'status' => $returnRepair->status,
                'link' => route('account').'#orders',
            ]
        );

        // Send email notification
        try {
            Mail::to($returnRepair->user->email)->send(new RefundApprovedMail($returnRepair));
        } catch (\Exception $e) {
            Log::error('Failed to send refund approval email', [
                'return_repair_id' => $returnRepair->id,
                'user_id' => $returnRepair->user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
