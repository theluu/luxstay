<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomController extends Controller
{
    use SavesTranslations;

    public function index(): AnonymousResourceCollection
    {
        return RoomResource::collection(Room::with('roomType', 'amenities')->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'room_type_id'    => 'required|exists:room_types,id',
            'name'            => 'required|string|max:255',
            'slug'            => 'required|string|unique:rooms,slug',
            'description'     => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests'      => 'required|integer|min:1',
            'size_sqm'        => 'nullable|integer',
            'thumbnail'       => 'nullable|string',
            'is_available'    => 'boolean',
        ]);
        $room = Room::create($data);
        if ($request->has('translations')) {
            $this->applyTranslations($room, $request->input('translations', []));
            $room->save();
        }
        return (new RoomResource($room->load('roomType', 'amenities')))->response()->setStatusCode(201);
    }

    public function show(Room $room): JsonResponse
    {
        $room->load('roomType', 'amenities');
        return response()->json([
            'data' => array_merge((new RoomResource($room))->resolve(), [
                'all_translations' => $this->allTranslations($room),
            ]),
        ]);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $data = $request->validate([
            'room_type_id'    => 'sometimes|exists:room_types,id',
            'name'            => 'sometimes|string|max:255',
            'slug'            => 'sometimes|string|unique:rooms,slug,' . $room->id,
            'description'     => 'nullable|string',
            'price_per_night' => 'sometimes|numeric|min:0',
            'max_guests'      => 'sometimes|integer|min:1',
            'size_sqm'        => 'nullable|integer',
            'thumbnail'       => 'nullable|string',
            'is_available'    => 'boolean',
        ]);
        $room->update($data);
        if ($request->has('translations')) {
            $this->applyTranslations($room, $request->input('translations', []));
            $room->save();
        }
        $room->load('roomType', 'amenities');
        return response()->json([
            'data' => array_merge((new RoomResource($room))->resolve(), [
                'all_translations' => $this->allTranslations($room),
            ]),
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();
        return response()->json(null, 204);
    }
}
