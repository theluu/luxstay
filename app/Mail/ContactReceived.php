<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactReceived extends Mailable
{
    public function __construct(
        public readonly string $senderName,
        public readonly string $senderEmail,
        public readonly string $messageText,
        public readonly string $source = 'contact_page',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Tin nhắn liên hệ mới từ ' . $this->senderName);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-received');
    }
}
