<?php

namespace App\Mail;

use App\Services\EmailTemplateRenderer;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordEmail extends Mailable
{
    public function __construct(
        public readonly string $resetUrl,
        public readonly string $userName,
    ) {}

    private function resolved(): ?array
    {
        return EmailTemplateRenderer::resolve('reset_password', [
            'name'      => $this->userName,
            'reset_url' => $this->resetUrl,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->resolved()['subject'] ?? 'Đặt lại mật khẩu LuxeStay của bạn');
    }

    public function content(): Content
    {
        $r = $this->resolved();
        if ($r) return new Content(view: 'emails.dynamic', with: ['body' => $r['body']]);
        return new Content(view: 'emails.reset-password');
    }
}
