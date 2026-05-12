<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
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
        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('category'));
    }

    public function update(Request $request, Product $product): ProductResource
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
        return new ProductResource($product->load('category'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
