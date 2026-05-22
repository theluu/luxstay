<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailSettingController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json(['data' => $this->settings()]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'mail_mailer'       => 'required|in:smtp,log,sendmail',
            'mail_host'         => 'nullable|string|max:255',
            'mail_port'         => 'nullable|integer|min:1|max:65535',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_encryption'   => 'nullable|in:tls,ssl,starttls,',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name'    => 'nullable|string|max:255',
        ]);

        foreach ($data as $key => $value) {
            if ($key === 'mail_password' && trim((string) $value) === '') {
                continue;
            }
            SiteSetting::set($key, $value ?? '');
        }

        return response()->json(['data' => $this->settings()]);
    }

    public function testEmail(Request $request): JsonResponse
    {
        $request->validate(['to' => 'required|email']);

        try {
            $mailer = SiteSetting::get('mail_mailer', 'log');
            if ($mailer && $mailer !== 'log') {
                config([
                    'mail.default'                 => $mailer,
                    'mail.mailers.smtp.host'       => SiteSetting::get('mail_host'),
                    'mail.mailers.smtp.port'       => (int) SiteSetting::get('mail_port', 587),
                    'mail.mailers.smtp.username'   => SiteSetting::get('mail_username'),
                    'mail.mailers.smtp.password'   => SiteSetting::get('mail_password'),
                    'mail.mailers.smtp.encryption' => SiteSetting::get('mail_encryption', 'tls') ?: null,
                    'mail.from.address'            => SiteSetting::get('mail_from_address', config('mail.from.address')),
                    'mail.from.name'               => SiteSetting::get('mail_from_name', config('mail.from.name')),
                ]);
            }

            Mail::raw('Đây là email test từ LuxeStay Admin. Cấu hình SMTP hoạt động tốt!', function ($msg) use ($request) {
                $msg->to($request->to)->subject('🏨 LuxeStay — Test Email');
            });

            return response()->json(['message' => 'Email test đã được gửi tới ' . $request->to]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gửi thất bại: ' . $e->getMessage()], 422);
        }
    }

    private function settings(): array
    {
        return [
            'mail_mailer'       => SiteSetting::get('mail_mailer', 'log'),
            'mail_host'         => SiteSetting::get('mail_host', ''),
            'mail_port'         => (int) SiteSetting::get('mail_port', 587),
            'mail_username'     => SiteSetting::get('mail_username', ''),
            'mail_password'     => '',
            'mail_has_password' => !empty(SiteSetting::get('mail_password')),
            'mail_encryption'   => SiteSetting::get('mail_encryption', 'tls'),
            'mail_from_address' => SiteSetting::get('mail_from_address', config('mail.from.address', '')),
            'mail_from_name'    => SiteSetting::get('mail_from_name', config('mail.from.name', '')),
        ];
    }
}
