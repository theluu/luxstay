<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return BookingResource::collection(Booking::with('user', 'room')->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Use the web booking flow.'], 422);
    }

    public function show(Booking $booking): BookingResource
    {
        return new BookingResource($booking->load('user', 'room'));
    }

    public function update(Request $request, Booking $booking): BookingResource
    {
        $data = $request->validate([
            'status'         => 'sometimes|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'sometimes|in:unpaid,paid,refunded',
        ]);
        $booking->update($data);
        return new BookingResource($booking->load('user', 'room'));
    }

    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();
        return response()->json(null, 204);
    }
}
