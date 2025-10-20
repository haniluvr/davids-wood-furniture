<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public $oldStatus;

    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.orders'),
            new PrivateChannel('admin.notifications'),
            new PrivateChannel('user.'.$this->order->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'order.status.changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'order' => [
                'id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'customer_name' => $this->order->user->name ?? 'Guest',
                'total' => $this->order->total,
                'status' => $this->newStatus,
                'updated_at' => $this->order->updated_at->toISOString(),
            ],
            'status_change' => [
                'from' => $this->oldStatus,
                'to' => $this->newStatus,
            ],
            'message' => 'Order #'.$this->order->order_number.' status changed from '.ucfirst($this->oldStatus).' to '.ucfirst($this->newStatus),
            'type' => 'order_status',
            'priority' => 'medium',
        ];
    }
}
