<?php

namespace App\Mail;

use App\Models\ReturnRepair;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $returnRepair;

    /**
     * Create a new message instance.
     */
    public function __construct(ReturnRepair $returnRepair)
    {
        $this->returnRepair = $returnRepair;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Refund Request Approved - '.$this->returnRepair->rma_number.' - David\'s Wood Furniture',
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
            view: 'emails.refunds.approved',
            with: [
                'returnRepair' => $this->returnRepair,
            ],
        );
    }
}
