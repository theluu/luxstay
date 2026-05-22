<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use SavesTranslations;

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
        $slider = Slider::create($data);
        if ($request->has('translations')) {
            $this->applyTranslations($slider, $request->input('translations', []));
            $slider->save();
        }
        return response()->json(['data' => $slider], 201);
    }

    public function show(Slider $slider): JsonResponse
    {
        return response()->json([
            'data' => array_merge($slider->toArray(), [
                'all_translations' => $this->allTranslations($slider),
            ]),
        ]);
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
        if ($request->has('translations')) {
            $this->applyTranslations($slider, $request->input('translations', []));
            $slider->save();
        }
        return response()->json([
            'data' => array_merge($slider->toArray(), [
                'all_translations' => $this->allTranslations($slider),
            ]),
        ]);
    }

    public function destroy(Slider $slider): JsonResponse
    {
        $slider->delete();
        return response()->json(null, 204);
    }
}
