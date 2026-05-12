<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): JsonResponse    { return response()->json(['data' => []]); }
    public function store(Request $r): JsonResponse  { return response()->json(['data' => []], 201); }
    public function show(string $id): JsonResponse   { return response()->json(['data' => []]); }
    public function update(Request $r, string $id): JsonResponse { return response()->json(['data' => []]); }
    public function destroy(string $id): JsonResponse { return response()->json(null, 204); }
}
