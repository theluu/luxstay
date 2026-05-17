<?php

namespace App\Services\Payments;

use App\Models\Booking;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use RuntimeException;

class VnpayService
{
    public function createPaymentUrl(Model $payable, string $txnRef, float $amount, string $orderInfo, Request $request): string
    {
        $this->ensureConfigured();

        $params = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => (string) config('services.vnpay.tmn_code'),
            'vnp_Amount' => $this->toGatewayAmount($amount),
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $txnRef,
            'vnp_OrderInfo' => $this->normalizeOrderInfo($orderInfo),
            'vnp_OrderType' => $payable instanceof Booking ? 'hotel' : 'other',
            'vnp_Locale' => (string) config('services.vnpay.locale', 'vn'),
            'vnp_ReturnUrl' => $this->returnUrl(),
            'vnp_IpAddr' => $request->ip() ?: '127.0.0.1',
            'vnp_CreateDate' => Carbon::now('Asia/Ho_Chi_Minh')->format('YmdHis'),
            'vnp_ExpireDate' => Carbon::now('Asia/Ho_Chi_Minh')->addMinutes(15)->format('YmdHis'),
        ];

        ksort($params);

        return rtrim((string) config('services.vnpay.url'), '?')
            . '?'
            . $this->buildQuery($params)
            . '&vnp_SecureHash='
            . $this->sign($params);
    }

    public function assertConfigured(): void
    {
        $this->ensureConfigured();
    }

    public function verify(array $payload): bool
    {
        $secureHash = $payload['vnp_SecureHash'] ?? '';
        if ($secureHash === '') {
            return false;
        }

        unset($payload['vnp_SecureHash'], $payload['vnp_SecureHashType']);
        ksort($payload);

        return hash_equals($secureHash, $this->sign($payload));
    }

    public function expectedGatewayAmount(Model $payable): int
    {
        $amount = $payable instanceof Order
            ? (float) $payable->total
            : (float) $payable->total_price;

        return $this->toGatewayAmount($amount);
    }

    public function successResponse(array $payload): bool
    {
        return ($payload['vnp_ResponseCode'] ?? null) === '00'
            && ($payload['vnp_TransactionStatus'] ?? null) === '00';
    }

    private function ensureConfigured(): void
    {
        if (empty(config('services.vnpay.tmn_code')) || empty(config('services.vnpay.hash_secret'))) {
            throw new RuntimeException('VNPAY chưa được cấu hình. Vui lòng thêm VNPAY_TMN_CODE và VNPAY_HASH_SECRET vào .env.');
        }
    }

    private function returnUrl(): string
    {
        return config('services.vnpay.return_url') ?: route('payment.vnpay.return');
    }

    private function toGatewayAmount(float $amount): int
    {
        $vndAmount = (int) round($amount * (int) config('services.vnpay.usd_to_vnd', 25000));

        return $vndAmount * 100;
    }

    private function normalizeOrderInfo(string $value): string
    {
        $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
        $value = preg_replace('/[^A-Za-z0-9 .:_-]/', '', $value) ?: $value;

        return mb_substr($value, 0, 255);
    }

    private function sign(array $params): string
    {
        return hash_hmac('sha512', $this->buildQuery($params), (string) config('services.vnpay.hash_secret'));
    }

    private function buildQuery(array $params): string
    {
        $parts = [];

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $parts[] = urlencode((string) $key) . '=' . urlencode((string) $value);
        }

        return implode('&', $parts);
    }
}
