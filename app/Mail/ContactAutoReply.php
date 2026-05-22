<?php

namespace App\Mail;

use App\Services\EmailTemplateRenderer;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactAutoReply extends Mailable
{
    public function __construct(
        public readonly string $senderName,
        public readonly string $messageText,
    ) {}

    private function resolved(): ?array
    {
        return EmailTemplateRenderer::resolve('contact_auto_reply', [
            'sender_name' => $this->senderName,
            'message'     => $this->messageText,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->resolved()['subject'] ?? 'Cảm ơn bạn đã liên hệ với LuxeStay');
    }

    public function content(): Content
    {
        $r = $this->resolved();
        if ($r) return new Content(view: 'emails.dynamic', with: ['body' => $r['body']]);
        return new Content(view: 'emails.contact-auto-reply');
    }
}
