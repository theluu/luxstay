<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SubscriberWelcome extends Mailable
{
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Chào mừng bạn đến với LuxeStay Newsletter!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.subscriber-welcome');
    }
}
