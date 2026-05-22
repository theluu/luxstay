<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactAutoReply extends Mailable
{
    public function __construct(
        public readonly string $senderName,
        public readonly string $messageText,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Cảm ơn bạn đã liên hệ với LuxeStay');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-auto-reply');
    }
}
