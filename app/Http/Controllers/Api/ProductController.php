<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    use SavesTranslations;

    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::with('category')->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'slug'                => 'required|string|unique:products,slug',
            'description'         => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'thumbnail'           => 'nullable|string',
            'is_active'           => 'boolean',
            'product_category_id' => 'nullable|exists:product_categories,id',
        ]);
        $product = Product::create($data);
        if ($request->has('translations')) {
            $this->applyTranslations($product, $request->input('translations', []));
            $product->save();
        }
        return (new ProductResource($product->load('category')))->response()->setStatusCode(201);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('category');
        return response()->json([
            'data' => array_merge((new ProductResource($product))->resolve(), [
                'all_translations' => $this->allTranslations($product),
            ]),
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'name'                => 'sometimes|string|max:255',
            'slug'                => 'sometimes|string|unique:products,slug,' . $product->id,
            'description'         => 'nullable|string',
            'price'               => 'sometimes|numeric|min:0',
            'stock'               => 'sometimes|integer|min:0',
            'thumbnail'           => 'nullable|string',
            'is_active'           => 'boolean',
            'product_category_id' => 'nullable|exists:product_categories,id',
        ]);
        $product->update($data);
        if ($request->has('translations')) {
            $this->applyTranslations($product, $request->input('translations', []));
            $product->save();
        }
        $product->load('category');
        return response()->json([
            'data' => array_merge((new ProductResource($product))->resolve(), [
                'all_translations' => $this->allTranslations($product),
            ]),
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
