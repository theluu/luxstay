<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailTemplateRenderer;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeEmail extends Mailable
{
    public function __construct(
        public readonly User $user,
    ) {}

    private function resolved(): ?array
    {
        return EmailTemplateRenderer::resolve('welcome', [
            'name'  => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone ?? '',
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->resolved()['subject'] ?? ('Chào mừng bạn đến với LuxeStay, ' . $this->user->name . '!'));
    }

    public function content(): Content
    {
        $r = $this->resolved();
        if ($r) return new Content(view: 'emails.dynamic', with: ['body' => $r['body']]);
        return new Content(view: 'emails.welcome');
    }
}
