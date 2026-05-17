<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\JsonResponse;

class PostCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => PostCategory::orderBy('name')->get(['id', 'name'])]);
    }
}
