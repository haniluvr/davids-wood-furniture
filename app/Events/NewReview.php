<?php

namespace App\Events;

use App\Models\ProductReview;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReview implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;

    /**
     * Create a new event instance.
     */
    public function __construct(ProductReview $review)
    {
        $this->review = $review;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.reviews'),
            new PrivateChannel('admin.notifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'review.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'review' => [
                'id' => $this->review->id,
                'product_name' => $this->review->product->name,
                'customer_name' => $this->review->user->name,
                'rating' => $this->review->rating,
                'title' => $this->review->title,
                'status' => $this->review->status,
                'created_at' => $this->review->created_at->toISOString(),
            ],
            'message' => 'New review for ' . $this->review->product->name . ' by ' . $this->review->user->name,
            'type' => 'review',
            'priority' => 'medium',
        ];
    }
}
