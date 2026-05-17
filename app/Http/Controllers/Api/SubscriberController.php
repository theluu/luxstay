<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{
    public function index(): JsonResponse
    {
        $subscribers = Subscriber::latest()->get();
        return response()->json(['data' => $subscribers]);
    }

    public function destroy(Subscriber $subscriber): JsonResponse
    {
        $subscriber->delete();
        return response()->json(null, 204);
    }
}
