<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart  = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $items[] = ['product' => $product, 'quantity' => $qty];
                $total   += $product->price * $qty;
            }
        }

        return view('pages.shop.cart', compact('items', 'total'));
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);
        $cart                       = session()->get('cart', []);
        $cart[$request->product_id] = ($cart[$request->product_id] ?? 0) + ($request->quantity ?? 1);
        session()->put('cart', $cart);
        return back()->with('success', 'Added to cart.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required']);
        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);
        return back()->with('success', 'Removed from cart.');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer|min:1']);
        $cart                       = session()->get('cart', []);
        $cart[$request->product_id] = $request->quantity;
        session()->put('cart', $cart);
        return back()->with('success', 'Cart updated.');
    }
}
