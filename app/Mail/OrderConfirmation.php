<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderConfirmation extends Mailable
{
    public function __construct(
        public readonly Order  $order,
        public readonly string $customerName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Xác nhận đơn hàng #' . $this->order->id . ' — LuxeStay');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-confirmation');
    }
}
