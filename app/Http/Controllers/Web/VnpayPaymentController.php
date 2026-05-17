<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\Payments\VnpayService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VnpayPaymentController extends Controller
{
    public function __construct(private readonly VnpayService $vnpay)
    {
    }

    public function return(Request $request): RedirectResponse
    {
        $payload = $request->query();
        $transaction = $this->findTransaction($payload['vnp_TxnRef'] ?? null);

        if (!$this->vnpay->verify($payload) || !$transaction) {
            return redirect()->route('home')->with('error', 'Không thể xác thực giao dịch VNPAY.');
        }

        $this->applyGatewayResult($transaction, $payload);

        return $this->redirectToPayable($transaction->payable)
            ->with($this->vnpay->successResponse($payload) ? 'success' : 'error', $this->messageFor($payload));
    }

    public function ipn(Request $request): JsonResponse
    {
        $payload = $request->query();

        if (!$this->vnpay->verify($payload)) {
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
        }

        $transaction = $this->findTransaction($payload['vnp_TxnRef'] ?? null);
        if (!$transaction) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        if ((int) ($payload['vnp_Amount'] ?? 0) !== $this->vnpay->expectedGatewayAmount($transaction->payable)) {
            return response()->json(['RspCode' => '04', 'Message' => 'Invalid amount']);
        }

        if ($transaction->status === 'success') {
            return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
        }

        $this->applyGatewayResult($transaction, $payload);

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm success']);
    }

    private function findTransaction(?string $txnRef): ?PaymentTransaction
    {
        if (!$txnRef) {
            return null;
        }

        return PaymentTransaction::with('payable')
            ->where('gateway', 'vnpay')
            ->where('gateway_ref', $txnRef)
            ->latest()
            ->first();
    }

    private function applyGatewayResult(PaymentTransaction $transaction, array $payload): void
    {
        DB::transaction(function () use ($transaction, $payload) {
            $transaction->refresh();
            $payable = $transaction->payable;

            if (!$payable) {
                return;
            }

            $success = $this->vnpay->successResponse($payload);

            $transaction->update([
                'status' => $success ? 'success' : 'failed',
                'gateway_response' => $payload,
            ]);

            if ($success) {
                $updates = ['payment_status' => 'paid'];

                if ($payable instanceof Booking) {
                    $updates['status'] = 'confirmed';
                }

                if ($payable instanceof Order && $payable->status === 'pending') {
                    $updates['status'] = 'processing';
                }

                $payable->update($updates);
            }
        });
    }

    private function redirectToPayable(Model $payable): RedirectResponse
    {
        if ($payable instanceof Booking) {
            if ($payable->user_id === null) {
                session(['guest_booking_id' => $payable->id]);
            }

            return redirect()->route('bookings.confirmation', $payable);
        }

        if ($payable instanceof Order) {
            if ($payable->user_id === null) {
                session(['guest_order_id' => $payable->id]);
                return redirect()->route('checkout.confirmation');
            }

            return redirect()->route('orders.show', $payable);
        }

        return redirect()->route('home');
    }

    private function messageFor(array $payload): string
    {
        return $this->vnpay->successResponse($payload)
            ? 'Thanh toán VNPAY thành công.'
            : 'Thanh toán VNPAY chưa thành công hoặc đã bị hủy.';
    }
}
