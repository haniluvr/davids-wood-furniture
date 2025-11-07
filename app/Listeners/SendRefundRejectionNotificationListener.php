<?php

namespace App\Listeners;

use App\Events\RefundRequestRejected;
use App\Mail\RefundRejectedMail;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRefundRejectionNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(RefundRequestRejected $event): void
    {
        $returnRepair = $event->returnRepair;
        $rejectionReason = $event->rejectionReason;

        // Load user relationship if not already loaded
        if (! $returnRepair->relationLoaded('user')) {
            $returnRepair->load('user');
        }

        if (! $returnRepair->user) {
            Log::warning('RefundRequestRejected event: User not found', [
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
            'Refund Request Rejected',
            "Your refund request {$returnRepair->rma_number} for order #{$returnRepair->order->order_number} has been rejected. Reason: {$rejectionReason}",
            'refund_rejected',
            [
                'rma_number' => $returnRepair->rma_number,
                'order_id' => $returnRepair->order_id,
                'order_number' => $returnRepair->order->order_number ?? null,
                'status' => $returnRepair->status,
                'rejection_reason' => $rejectionReason,
                'link' => route('account').'#orders',
            ]
        );

        // Send email notification
        try {
            Mail::to($returnRepair->user->email)->send(new RefundRejectedMail($returnRepair, $rejectionReason));
        } catch (\Exception $e) {
            Log::error('Failed to send refund rejection email', [
                'return_repair_id' => $returnRepair->id,
                'user_id' => $returnRepair->user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
