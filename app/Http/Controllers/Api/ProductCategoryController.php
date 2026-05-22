<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    use SavesTranslations;

    public function index(): JsonResponse
    {
        return response()->json(['data' => ProductCategory::orderBy('name')->get(['id', 'name'])]);
    }

    public function show(ProductCategory $productCategory): JsonResponse
    {
        return response()->json([
            'data' => array_merge($productCategory->toArray(), [
                'all_translations' => $this->allTranslations($productCategory),
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:product_categories,slug',
        ]);
        $category = ProductCategory::create($data);
        if ($request->has('translations')) {
            $this->applyTranslations($category, $request->input('translations', []));
            $category->save();
        }
        return response()->json(['data' => $category], 201);
    }

    public function update(Request $request, ProductCategory $productCategory): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:product_categories,slug,' . $productCategory->id,
        ]);
        $productCategory->update($data);
        if ($request->has('translations')) {
            $this->applyTranslations($productCategory, $request->input('translations', []));
            $productCategory->save();
        }
        return response()->json([
            'data' => array_merge($productCategory->toArray(), [
                'all_translations' => $this->allTranslations($productCategory),
            ]),
        ]);
    }

    public function destroy(ProductCategory $productCategory): JsonResponse
    {
        $productCategory->delete();
        return response()->json(null, 204);
    }
}
