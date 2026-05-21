<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index(): JsonResponse
    {
        $data = SiteSetting::allKeyed();

        // Never expose secret key — replace with a boolean indicator
        $data['recaptcha_has_secret_key'] = !empty($data['recaptcha_secret_key']);
        unset($data['recaptcha_secret_key']);

        return response()->json(['data' => $data]);
    }

    public function update(Request $request): JsonResponse
    {
        $allowed = [
            'site_name',
            'logo',
            'favicon',
            'phone',
            'email',
            'facebook_url',
            'instagram_url',
            'linkedin_url',
            'twitter_url',
            'services_video_url',
            'recaptcha_enabled',
            'recaptcha_site_key',
        ];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }

        // Secret key: only save if a non-empty value is sent
        if ($request->has('recaptcha_secret_key') && trim((string) $request->input('recaptcha_secret_key')) !== '') {
            SiteSetting::set('recaptcha_secret_key', trim($request->input('recaptcha_secret_key')));
        }

        return $this->index();
    }
}
