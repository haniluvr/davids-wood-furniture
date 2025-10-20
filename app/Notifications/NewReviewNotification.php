<?php

namespace App\Notifications;

use App\Models\ProductReview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
            ->subject('New Product Review - '.$this->review->product->name)
            ->view('emails.reviews.new-review', [
                'review' => $this->review,
            ]);
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
