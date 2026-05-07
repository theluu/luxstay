<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->paginate(10);
        return view('pages.account.orders', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product');
        return view('pages.account.order-detail', compact('order'));
    }
}
