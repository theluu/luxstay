<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentTransactionResource;
use App\Models\PaymentTransaction;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentTransactionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return PaymentTransactionResource::collection(
            PaymentTransaction::latest()->paginate(50)
        );
    }

    public function show(PaymentTransaction $paymentTransaction): PaymentTransactionResource
    {
        return new PaymentTransactionResource($paymentTransaction);
    }
}
