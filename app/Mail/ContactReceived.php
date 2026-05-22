<?php

namespace App\Mail;

use App\Services\EmailTemplateRenderer;
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

    private function resolved(): ?array
    {
        return EmailTemplateRenderer::resolve('contact_received', [
            'sender_name'  => $this->senderName,
            'sender_email' => $this->senderEmail,
            'message'      => $this->messageText,
            'source'       => $this->source,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->resolved()['subject'] ?? ('Tin nhắn liên hệ mới từ ' . $this->senderName));
    }

    public function content(): Content
    {
        $r = $this->resolved();
        if ($r) return new Content(view: 'emails.dynamic', with: ['body' => $r['body']]);
        return new Content(view: 'emails.contact-received');
    }
}
