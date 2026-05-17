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
        $orderby  = request('orderby', 'menu_order');
        $category = request('category');
        $search   = request('q');

        $builder = Product::where('is_active', true)->with('category');

        if ($category) {
            $builder->where('product_category_id', $category);
        }

        if ($search) {
            $builder->where('name', 'like', "%{$search}%");
        }

        match ($orderby) {
            'price'      => $builder->orderBy('price', 'asc'),
            'price-desc' => $builder->orderBy('price', 'desc'),
            'date'       => $builder->orderBy('created_at', 'desc'),
            default      => $builder->orderBy('id', 'asc'),
        };

        $products   = $builder->paginate(12)->withQueryString();
        $categories = ProductCategory::withCount('products')->get();

        $sortOptions = [
            'menu_order' => 'Mặc định',
            'date'       => 'Mới nhất',
            'price'      => 'Giá: thấp đến cao',
            'price-desc' => 'Giá: cao đến thấp',
        ];
        $sortLabel = $sortOptions[$orderby] ?? 'Mặc định';

        return view('pages.shop.index', compact('products', 'categories', 'orderby', 'category', 'search', 'sortOptions', 'sortLabel'));
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
