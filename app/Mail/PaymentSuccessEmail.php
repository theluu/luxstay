<?php

namespace App\Mail;

use App\Services\EmailTemplateRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PaymentSuccessEmail extends Mailable
{
    public function __construct(
        public readonly Model  $payable,
        public readonly string $customerName,
        public readonly string $payableType,
    ) {}

    private function resolved(): ?array
    {
        $total = $this->payableType === 'booking'
            ? number_format((float)($this->payable->total_price ?? 0), 0, ',', '.')
            : number_format((float)($this->payable->total ?? 0), 0, ',', '.');

        return EmailTemplateRenderer::resolve('payment_success', [
            'customer_name' => $this->customerName,
            'payable_id'    => $this->payable->id,
            'payable_type'  => $this->payableType === 'booking' ? 'Đặt phòng' : 'Đơn hàng',
            'total'         => $total,
        ]);
    }

    public function envelope(): Envelope
    {
        $label = $this->payableType === 'booking' ? 'đặt phòng' : 'đơn hàng';
        return new Envelope(subject: $this->resolved()['subject'] ?? ('Thanh toán VNPAY thành công — ' . ucfirst($label) . ' #' . $this->payable->id));
    }

    public function content(): Content
    {
        $r = $this->resolved();
        if ($r) return new Content(view: 'emails.dynamic', with: ['body' => $r['body']]);
        return new Content(view: 'emails.payment-success');
    }
}
