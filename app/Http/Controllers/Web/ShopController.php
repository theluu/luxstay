<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $products   = Product::where('is_active', true)->with('category')->paginate(12);
        $categories = ProductCategory::withCount('products')->get();
        return view('pages.shop.index', compact('products', 'categories'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $related = Product::where('product_category_id', $product->product_category_id)
                       ->where('id', '!=', $product->id)
                       ->where('is_active', true)
                       ->take(4)->get();
        return view('pages.shop.show', compact('product', 'related'));
    }
}
