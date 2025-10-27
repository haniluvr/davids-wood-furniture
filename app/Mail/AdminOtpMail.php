<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;

    public $otpCode;

    public function __construct(Admin $admin, string $otpCode)
    {
        $this->admin = $admin;
        $this->otpCode = $otpCode;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Login Verification Code - David\'s Wood Furniture',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.admin-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
