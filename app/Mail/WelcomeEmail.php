<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeEmail extends Mailable
{
    public function __construct(
        public readonly User $user,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Chào mừng bạn đến với LuxeStay, ' . $this->user->name . '!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.welcome');
    }
}
