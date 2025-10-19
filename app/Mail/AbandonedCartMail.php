<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\CartItem;

class AbandonedCartMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $cartItems;
    public $cartTotal;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $cartItems = null, $cartTotal = 0)
    {
        $this->user = $user;
        $this->cartItems = $cartItems ?? $this->getUserCartItems($user);
        $this->cartTotal = $cartTotal ?: $this->calculateCartTotal();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Don\'t Forget Your Items - Complete Your Order!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.marketing.abandoned-cart',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get user's cart items
     */
    private function getUserCartItems(User $user)
    {
        return CartItem::where('user_id', $user->id)
            ->with(['product.images', 'product.category'])
            ->get();
    }

    /**
     * Calculate cart total
     */
    private function calculateCartTotal()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }
}

