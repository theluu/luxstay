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
        return response()->json(['data' => SiteSetting::allKeyed()]);
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
            'nav_items',
            'services_video_url',
        ];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                $value = $key === 'nav_items'
                    ? json_encode($request->input($key))
                    : $request->input($key);
                SiteSetting::set($key, $value);
            }
        }

        return response()->json(['data' => SiteSetting::allKeyed()]);
    }
}
