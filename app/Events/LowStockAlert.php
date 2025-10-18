<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $currentStock;
    public $threshold;

    /**
     * Create a new event instance.
     */
    public function __construct(Product $product, int $currentStock, int $threshold = 10)
    {
        $this->product = $product;
        $this->currentStock = $currentStock;
        $this->threshold = $threshold;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.inventory'),
            new PrivateChannel('admin.notifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'inventory.low.stock';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'sku' => $this->product->sku,
                'current_stock' => $this->currentStock,
                'threshold' => $this->threshold,
            ],
            'message' => 'Low stock alert: ' . $this->product->name . ' has only ' . $this->currentStock . ' units remaining',
            'type' => 'inventory',
            'priority' => 'high',
            'action_required' => true,
        ];
    }
}
