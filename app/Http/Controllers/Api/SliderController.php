<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Slider::orderBy('sort_order')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'       => 'required|in:image,video',
            'title'      => 'nullable|string|max:1000',
            'media_url'  => 'required|string|max:1000',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);

        return response()->json(['data' => Slider::create($data)], 201);
    }

    public function show(Slider $slider): JsonResponse
    {
        return response()->json(['data' => $slider]);
    }

    public function update(Request $request, Slider $slider): JsonResponse
    {
        $data = $request->validate([
            'type'       => 'sometimes|in:image,video',
            'title'      => 'nullable|string|max:1000',
            'media_url'  => 'sometimes|string|max:1000',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $slider->update($data);

        return response()->json(['data' => $slider]);
    }

    public function destroy(Slider $slider): JsonResponse
    {
        $slider->delete();
        return response()->json(null, 204);
    }
}
