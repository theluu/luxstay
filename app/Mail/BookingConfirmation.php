<?php

namespace App\Mail;

use App\Models\Booking;
use App\Services\EmailTemplateRenderer;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingConfirmation extends Mailable
{
    public function __construct(
        public readonly Booking $booking,
        public readonly string  $guestName,
    ) {}

    private function resolved(): ?array
    {
        return EmailTemplateRenderer::resolve('booking_confirmation', [
            'guest_name'  => $this->guestName,
            'booking_id'  => $this->booking->id,
            'room_name'   => $this->booking->room->name ?? 'N/A',
            'check_in'    => \Carbon\Carbon::parse($this->booking->check_in)->format('d/m/Y'),
            'check_out'   => \Carbon\Carbon::parse($this->booking->check_out)->format('d/m/Y'),
            'guests'      => $this->booking->guests,
            'total_price' => number_format((float)$this->booking->total_price, 0, ',', '.'),
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->resolved()['subject'] ?? ('Xác nhận đặt phòng #' . $this->booking->id . ' — LuxeStay'));
    }

    public function content(): Content
    {
        $r = $this->resolved();
        if ($r) return new Content(view: 'emails.dynamic', with: ['body' => $r['body']]);
        return new Content(view: 'emails.booking-confirmation');
    }
}
