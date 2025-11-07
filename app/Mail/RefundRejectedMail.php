<?php

namespace App\Mail;

use App\Models\ReturnRepair;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $returnRepair;

    public $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(ReturnRepair $returnRepair, string $rejectionReason)
    {
        $this->returnRepair = $returnRepair;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Refund Request Update - '.$this->returnRepair->rma_number.' - David\'s Wood Furniture',
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
            view: 'emails.refunds.rejected',
            with: [
                'returnRepair' => $this->returnRepair,
                'rejectionReason' => $this->rejectionReason,
            ],
        );
    }
}
