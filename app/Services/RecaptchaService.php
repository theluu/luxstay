<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    private const DEFAULT_THRESHOLD = 0.5;

    public static function isEnabled(): bool
    {
        return filter_var(SiteSetting::get('recaptcha_enabled', '0'), FILTER_VALIDATE_BOOLEAN)
            && !empty(SiteSetting::get('recaptcha_site_key'))
            && !empty(SiteSetting::get('recaptcha_secret_key'));
    }

    public static function siteKey(): string
    {
        return SiteSetting::get('recaptcha_site_key', '');
    }

    /**
     * Verify a reCAPTCHA v3 token.
     * Returns true if disabled (bypass) or if score >= threshold.
     * Returns false if token is invalid or score is too low.
     */
    public static function verify(string $token, string $action = '', float $threshold = self::DEFAULT_THRESHOLD): bool
    {
        if (!static::isEnabled()) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->post(self::VERIFY_URL, [
                'secret'   => SiteSetting::get('recaptcha_secret_key'),
                'response' => $token,
            ]);

            if (!$response->successful()) {
                Log::warning('reCAPTCHA verify HTTP error', ['status' => $response->status()]);
                return false;
            }

            $data = $response->json();

            if (!($data['success'] ?? false)) {
                return false;
            }

            if (!empty($action) && ($data['action'] ?? '') !== $action) {
                return false;
            }

            return ($data['score'] ?? 0) >= $threshold;
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA verify exception: ' . $e->getMessage());
            return false;
        }
    }
}
