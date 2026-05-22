<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    use SavesTranslations;

    public function index(): JsonResponse
    {
        return response()->json(['data' => PostCategory::orderBy('name')->get(['id', 'name'])]);
    }

    public function show(PostCategory $postCategory): JsonResponse
    {
        return response()->json([
            'data' => array_merge($postCategory->toArray(), [
                'all_translations' => $this->allTranslations($postCategory),
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:post_categories,slug',
        ]);
        $category = PostCategory::create($data);
        if ($request->has('translations')) {
            $this->applyTranslations($category, $request->input('translations', []));
            $category->save();
        }
        return response()->json(['data' => $category], 201);
    }

    public function update(Request $request, PostCategory $postCategory): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:post_categories,slug,' . $postCategory->id,
        ]);
        $postCategory->update($data);
        if ($request->has('translations')) {
            $this->applyTranslations($postCategory, $request->input('translations', []));
            $postCategory->save();
        }
        return response()->json([
            'data' => array_merge($postCategory->toArray(), [
                'all_translations' => $this->allTranslations($postCategory),
            ]),
        ]);
    }

    public function destroy(PostCategory $postCategory): JsonResponse
    {
        $postCategory->delete();
        return response()->json(null, 204);
    }
}
