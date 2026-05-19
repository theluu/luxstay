<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json(['data' => $this->settings()]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'vnpay_enabled' => 'required|boolean',
            'vnpay_environment' => 'required|in:sandbox,production',
            'vnpay_payment_url' => 'required|url|max:500',
            'vnpay_tmn_code' => 'nullable|string|max:100',
            'vnpay_hash_secret' => 'nullable|string|max:255',
            'vnpay_return_url' => 'required|url|max:500',
            'vnpay_ipn_url' => 'required|url|max:500',
            'vnpay_locale' => 'required|in:vn,en',
            'vnpay_usd_to_vnd' => 'required|integer|min:1|max:1000000',
        ]);

        foreach ($data as $key => $value) {
            if ($key === 'vnpay_hash_secret' && trim((string) $value) === '') {
                continue;
            }

            SiteSetting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value);
        }

        return response()->json(['data' => $this->settings()]);
    }

    private function settings(): array
    {
        $hashSecret = SiteSetting::get('vnpay_hash_secret') ?: config('services.vnpay.hash_secret');

        return [
            'vnpay_enabled' => filter_var(SiteSetting::get('vnpay_enabled', false), FILTER_VALIDATE_BOOLEAN),
            'vnpay_environment' => SiteSetting::get('vnpay_environment', 'sandbox'),
            'vnpay_payment_url' => SiteSetting::get('vnpay_payment_url', config('services.vnpay.url')),
            'vnpay_tmn_code' => SiteSetting::get('vnpay_tmn_code', config('services.vnpay.tmn_code')),
            'vnpay_hash_secret' => '',
            'vnpay_has_hash_secret' => !empty($hashSecret),
            'vnpay_return_url' => SiteSetting::get('vnpay_return_url', config('services.vnpay.return_url') ?: route('payment.vnpay.return')),
            'vnpay_ipn_url' => SiteSetting::get('vnpay_ipn_url', config('services.vnpay.ipn_url') ?: route('payment.vnpay.ipn')),
            'vnpay_locale' => SiteSetting::get('vnpay_locale', config('services.vnpay.locale', 'vn')),
            'vnpay_usd_to_vnd' => (int) SiteSetting::get('vnpay_usd_to_vnd', config('services.vnpay.usd_to_vnd', 25000)),
        ];
    }
}
