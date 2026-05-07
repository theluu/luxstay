<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
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

        return view('pages.shop.checkout', compact('items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('checkout.index')->with('info', 'Payment coming soon.');
    }
}
