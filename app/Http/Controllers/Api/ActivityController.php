<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityController extends Controller
{
    use SavesTranslations;

    public function index(): AnonymousResourceCollection
    {
        return ActivityResource::collection(Activity::orderBy('sort_order')->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'        => 'required|in:spa,golf,hiking,skiing,water_sports,fitness,nature,restaurant,event',
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|unique:activities,slug',
            'content'     => 'nullable|string',
            'thumbnail'   => 'nullable|string',
            'hero_image'  => 'nullable|string',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
        ]);
        $activity = Activity::create($data);
        if ($request->has('translations')) {
            $this->applyTranslations($activity, $request->input('translations', []));
            $activity->save();
        }
        return (new ActivityResource($activity))->response()->setStatusCode(201);
    }

    public function show(Activity $activity): JsonResponse
    {
        return response()->json([
            'data' => array_merge((new ActivityResource($activity))->resolve(), [
                'all_translations' => $this->allTranslations($activity),
            ]),
        ]);
    }

    public function update(Request $request, Activity $activity): JsonResponse
    {
        $data = $request->validate([
            'type'        => 'sometimes|in:spa,golf,hiking,skiing,water_sports,fitness,nature,restaurant,event',
            'title'       => 'sometimes|string|max:255',
            'slug'        => 'sometimes|string|unique:activities,slug,' . $activity->id,
            'content'     => 'nullable|string',
            'thumbnail'   => 'nullable|string',
            'hero_image'  => 'nullable|string',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
        ]);
        $activity->update($data);
        if ($request->has('translations')) {
            $this->applyTranslations($activity, $request->input('translations', []));
            $activity->save();
        }
        return response()->json([
            'data' => array_merge((new ActivityResource($activity))->resolve(), [
                'all_translations' => $this->allTranslations($activity),
            ]),
        ]);
    }

    public function destroy(Activity $activity): JsonResponse
    {
        $activity->delete();
        return response()->json(null, 204);
    }
}
