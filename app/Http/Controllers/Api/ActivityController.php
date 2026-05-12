<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityController extends Controller
{
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
        return (new ActivityResource($activity))->response()->setStatusCode(201);
    }

    public function show(Activity $activity): ActivityResource
    {
        return new ActivityResource($activity);
    }

    public function update(Request $request, Activity $activity): ActivityResource
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
        return new ActivityResource($activity);
    }

    public function destroy(Activity $activity): JsonResponse
    {
        $activity->delete();
        return response()->json(null, 204);
    }
}
