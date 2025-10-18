<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ProductReview;

class NewReviewNotification extends Notification
{
    use Queueable;

    protected $review;

    public function __construct(ProductReview $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Product Review - ' . $this->review->product->name)
            ->greeting('Hello!')
            ->line('A new review has been submitted for one of your products.')
            ->line('Review Details:')
            ->line('Product: ' . $this->review->product->name)
            ->line('Customer: ' . ($this->review->user ? $this->review->user->name : 'Anonymous'))
            ->line('Rating: ' . $this->review->rating . '/5 stars')
            ->line('Review: ' . $this->review->review)
            ->line('Status: ' . ($this->review->is_approved ? 'Approved' : 'Pending Approval'))
            ->line('Submitted: ' . $this->review->created_at->format('M d, Y H:i'))
            ->action('View Review', url('/admin/reviews/' . $this->review->id))
            ->line('You can approve, reject, or respond to this review in the admin panel.');
    }

    public function toArray($notifiable)
    {
        return [
            'review_id' => $this->review->id,
            'product_id' => $this->review->product_id,
            'product_name' => $this->review->product->name,
            'user_name' => $this->review->user ? $this->review->user->name : 'Anonymous',
            'rating' => $this->review->rating,
            'is_approved' => $this->review->is_approved,
        ];
    }
}
