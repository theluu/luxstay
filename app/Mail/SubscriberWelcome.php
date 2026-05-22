<?php

namespace App\Mail;

use App\Services\EmailTemplateRenderer;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SubscriberWelcome extends Mailable
{
    private function resolved(): ?array
    {
        return EmailTemplateRenderer::resolve('subscriber_welcome', []);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->resolved()['subject'] ?? 'Chào mừng bạn đến với LuxeStay Newsletter!');
    }

    public function content(): Content
    {
        $r = $this->resolved();
        if ($r) return new Content(view: 'emails.dynamic', with: ['body' => $r['body']]);
        return new Content(view: 'emails.subscriber-welcome');
    }
}
