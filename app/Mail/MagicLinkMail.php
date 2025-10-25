<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MagicLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $token;

    public $type;

    public $expiresAt;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token, $type = '2fa', $expiresAt = null)
    {
        $this->user = $user;
        $this->token = $token;
        $this->type = $type;
        $this->expiresAt = $expiresAt ?? now()->addHour();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'password_reset'
            ? 'Reset Your Password - David\'s Wood Furniture'
            : 'Complete Your Login - David\'s Wood Furniture';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->type === 'password_reset'
            ? 'emails.auth.password-reset'
            : 'emails.auth.magic-link';

        return new Content(
            view: $view,
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
}
