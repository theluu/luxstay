<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,image/gif,image/svg+xml,video/mp4,video/webm,video/ogg',
                'max:51200',
            ],
        ]);

        $path = $request->file('file')->store('uploads', 'public');
        $publicPath = 'storage/' . $path;

        return response()->json([
            'path' => $publicPath,
            'url'  => $publicPath,
            'type' => str_starts_with((string) $request->file('file')->getMimeType(), 'video/') ? 'video' : 'image',
        ]);
    }
}
