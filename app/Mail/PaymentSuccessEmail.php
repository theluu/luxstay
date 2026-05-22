<?php

namespace App\Mail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PaymentSuccessEmail extends Mailable
{
    public function __construct(
        public readonly Model  $payable,
        public readonly string $customerName,
        public readonly string $payableType, // 'booking' or 'order'
    ) {}

    public function envelope(): Envelope
    {
        $label = $this->payableType === 'booking' ? 'đặt phòng' : 'đơn hàng';
        return new Envelope(subject: 'Thanh toán VNPAY thành công — ' . ucfirst($label) . ' #' . $this->payable->id);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.payment-success');
    }
}
