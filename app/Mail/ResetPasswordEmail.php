<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordEmail extends Mailable
{
    public function __construct(
        public readonly string $resetUrl,
        public readonly string $userName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Đặt lại mật khẩu LuxeStay của bạn');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.reset-password');
    }
}
