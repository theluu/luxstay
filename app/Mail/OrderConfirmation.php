<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\EmailTemplateRenderer;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderConfirmation extends Mailable
{
    public function __construct(
        public readonly Order  $order,
        public readonly string $customerName,
    ) {}

    private function resolved(): ?array
    {
        return EmailTemplateRenderer::resolve('order_confirmation', [
            'customer_name'  => $this->customerName,
            'order_id'       => $this->order->id,
            'total'          => number_format((float)$this->order->total, 0, ',', '.'),
            'payment_method' => $this->order->payment_status === 'paid' ? 'Đã thanh toán (VNPAY)' : 'COD (thanh toán khi nhận hàng)',
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->resolved()['subject'] ?? ('Xác nhận đơn hàng #' . $this->order->id . ' — LuxeStay'));
    }

    public function content(): Content
    {
        $r = $this->resolved();
        if ($r) return new Content(view: 'emails.dynamic', with: ['body' => $r['body']]);
        return new Content(view: 'emails.order-confirmation');
    }
}
