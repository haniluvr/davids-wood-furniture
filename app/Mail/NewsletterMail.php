<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\User;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $featuredProducts;
    public $promotions;

    /**
     * Create a new message instance.
     */
    public function __construct($subscriber = null, $featuredProducts = null, $promotions = null)
    {
        $this->subscriber = $subscriber;
        $this->featuredProducts = $featuredProducts ?? $this->getFeaturedProducts();
        $this->promotions = $promotions ?? $this->getActivePromotions();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Latest News & Featured Products - David\'s Wood Furniture',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.marketing.newsletter',
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
     * Get featured products for the newsletter
     */
    private function getFeaturedProducts()
    {
        return Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }

    /**
     * Get active promotions
     */
    private function getActivePromotions()
    {
        // This would typically come from a promotions table
        // For now, return empty array
        return collect([]);
    }
}

