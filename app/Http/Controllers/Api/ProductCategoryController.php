<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;

class ProductCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => ProductCategory::orderBy('name')->get(['id', 'name'])]);
    }
}
