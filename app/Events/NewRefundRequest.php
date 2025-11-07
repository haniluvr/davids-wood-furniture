<?php

namespace App\Events;

use App\Models\ReturnRepair;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRefundRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $returnRepair;

    /**
     * Create a new event instance.
     */
    public function __construct(ReturnRepair $returnRepair)
    {
        $this->returnRepair = $returnRepair;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.returns'),
            new PrivateChannel('admin.notifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'refund.request.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $order = $this->returnRepair->order;
        $customerName = $order->user
            ? ($order->user->first_name.' '.$order->user->last_name)
            : 'Guest';

        return [
            'return_repair' => [
                'id' => $this->returnRepair->id,
                'rma_number' => $this->returnRepair->rma_number,
                'order_number' => $order->order_number,
                'type' => $this->returnRepair->type,
                'status' => $this->returnRepair->status,
                'customer_name' => $customerName,
                'created_at' => $this->returnRepair->created_at->toISOString(),
            ],
            'message' => 'New refund request for Order #'.$order->order_number.' with RMA #'.$this->returnRepair->rma_number,
            'type' => 'refund',
            'priority' => 'high',
        ];
    }
}
