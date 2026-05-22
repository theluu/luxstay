<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Mail\OrderConfirmation;
use App\Services\Payments\VnpayService;
use App\Services\RecaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private readonly VnpayService $vnpay)
    {
    }

    public function index(): View|RedirectResponse
    {
        $cart     = session()->get('cart', []);
        $items    = [];
        $subtotal = 0;

        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $items[]   = ['product' => $product, 'quantity' => $qty];
                $subtotal += $product->price * $qty;
            }
        }

        if (empty($items)) {
            return redirect()->route('cart.index');
        }

        $shippingFee = $subtotal >= 200 ? 0 : 15;
        $grandTotal  = $subtotal + $shippingFee;
        $user        = Auth::user();

        return view('pages.shop.checkout', compact('items', 'subtotal', 'shippingFee', 'grandTotal', 'user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'billing_first_name' => 'required|string|max:100',
            'billing_last_name'  => 'required|string|max:100',
            'billing_address_1'  => 'required|string|max:255',
            'billing_city'       => 'required|string|max:100',
            'billing_phone'      => 'required|string|max:30',
            'billing_email'      => 'required|email|max:255',
            'payment_method'     => 'required|in:cod,vnpay',
        ]);

        if (!RecaptchaService::verify($request->input('recaptcha_token', ''), 'checkout')) {
            return back()->withInput()->with('error', 'Xác minh bảo mật thất bại. Vui lòng thử lại.');
        }

        if ($request->payment_method === 'vnpay') {
            try {
                $this->vnpay->assertConfigured();
            } catch (\RuntimeException $e) {
                return back()->withInput()->with('error', $e->getMessage());
            }
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $items    = [];
        $subtotal = 0;

        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product && $product->is_active) {
                $items[]  = ['product' => $product, 'quantity' => (int) $qty];
                $subtotal += $product->price * $qty;
            }
        }

        $shippingFee = $subtotal >= 200 ? 0 : 15;
        $total       = $subtotal + $shippingFee;

        $order = DB::transaction(function () use ($request, $items, $subtotal, $shippingFee, $total) {
            $order = Order::create([
                'user_id'          => Auth::id(), // null for guests
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'total'            => $total,
                'shipping_address' => json_encode([
                    'first_name' => $request->billing_first_name,
                    'last_name'  => $request->billing_last_name,
                    'company'    => $request->billing_company,
                    'address_1'  => $request->billing_address_1,
                    'address_2'  => $request->billing_address_2,
                    'city'       => $request->billing_city,
                    'postcode'   => $request->billing_postcode,
                    'phone'      => $request->billing_phone,
                    'email'      => $request->billing_email,
                ]),
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['product']->price,
                ]);
                $item['product']->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        session()->forget('cart');

        // Send order confirmation for COD orders immediately
        if ($request->payment_method !== 'vnpay') {
            $customerEmail = $request->billing_email;
            $customerName  = trim($request->billing_first_name . ' ' . $request->billing_last_name);
            try {
                $order->load('items.product');
                Mail::to($customerEmail)->send(new OrderConfirmation($order, $customerName));
            } catch (\Exception) {
                // Mail failure must not break checkout
            }
        }

        if ($request->payment_method === 'vnpay') {
            $txnRef = 'ORD-' . $order->id . '-' . now()->format('YmdHis');
            $order->update(['vnpay_txn_ref' => $txnRef]);

            PaymentTransaction::create([
                'payable_type' => Order::class,
                'payable_id' => $order->id,
                'amount' => $order->total,
                'gateway' => 'vnpay',
                'status' => 'pending',
                'gateway_ref' => $txnRef,
            ]);

            $url = $this->vnpay->createPaymentUrl(
                $order,
                $txnRef,
                (float) $order->total,
                'Thanh toan don hang ' . $order->id,
                $request
            );

            return redirect()->away($url);
        }

        if (!Auth::check()) {
            session(['guest_order_id' => $order->id]);
            return redirect()->route('checkout.confirmation')
                ->with('success', 'Order #' . $order->id . ' placed successfully! Thank you.');
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order #' . $order->id . ' placed successfully! Thank you.');
    }

    public function guestConfirmation(): View|RedirectResponse
    {
        $orderId = session('guest_order_id');
        if (!$orderId) {
            return redirect()->route('shop.index');
        }

        $order   = Order::with('items.product')->find($orderId);
        if (!$order) {
            return redirect()->route('shop.index');
        }

        $address = $order->shipping_address ?? [];

        return view('pages.shop.confirmation', compact('order', 'address'));
    }
}
