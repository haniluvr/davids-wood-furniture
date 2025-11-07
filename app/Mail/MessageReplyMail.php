<?php

namespace App\Mail;

use App\Models\Admin;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public $replyMessage;

    public $contactMessage;

    public $admin;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $replyMessage, ContactMessage $contactMessage, Admin $admin)
    {
        $this->subject = $subject;
        $this->replyMessage = $replyMessage;
        $this->contactMessage = $contactMessage;
        $this->admin = $admin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
            from: config('mail.from.address', 'noreply@davidswood.shop'),
            replyTo: 'hello@davidswood.shop',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.messages.reply',
            with: [
                'subject' => $this->subject,
                'replyMessage' => $this->replyMessage,
                'contactMessage' => $this->contactMessage,
                'admin' => $this->admin,
            ],
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
