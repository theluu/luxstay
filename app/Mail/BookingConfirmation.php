<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingConfirmation extends Mailable
{
    public function __construct(
        public readonly Booking $booking,
        public readonly string  $guestName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Xác nhận đặt phòng #' . $this->booking->id . ' — LuxeStay');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-confirmation');
    }
}
