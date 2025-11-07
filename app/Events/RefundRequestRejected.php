<?php

namespace App\Events;

use App\Models\ReturnRepair;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefundRequestRejected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $returnRepair;

    public $rejectionReason;

    /**
     * Create a new event instance.
     */
    public function __construct(ReturnRepair $returnRepair, string $rejectionReason)
    {
        $this->returnRepair = $returnRepair;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->returnRepair->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'refund.request.rejected';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'rma_number' => $this->returnRepair->rma_number,
            'order_id' => $this->returnRepair->order_id,
            'order_number' => $this->returnRepair->order->order_number ?? null,
            'status' => $this->returnRepair->status,
            'rejection_reason' => $this->rejectionReason,
            'message' => 'Your refund request '.$this->returnRepair->rma_number.' has been rejected',
            'type' => 'refund_rejected',
        ];
    }
}
