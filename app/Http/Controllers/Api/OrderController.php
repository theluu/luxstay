<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection(Order::with(['user', 'transactions' => fn ($q) => $q->latest()])->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Use the web checkout flow.'], 422);
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order->load(['user', 'items.product', 'transactions' => fn ($q) => $q->latest()]));
    }

    public function update(Request $request, Order $order): OrderResource
    {
        $data = $request->validate([
            'status'         => 'sometimes|in:pending,processing,completed,cancelled',
            'payment_status' => 'sometimes|in:unpaid,paid,refunded',
        ]);
        $order->update($data);
        return new OrderResource($order->load(['user', 'items.product', 'transactions' => fn ($q) => $q->latest()]));
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
