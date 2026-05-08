@extends('layouts.app')

@section('title', 'Order #{{ $order->id }} – LuxeStay')

@section('content')
      <!-- Banner Section Start -->
      <div class="sisf-banner position-relative">
         <div class="banner-img">
            <figure>
               <img src="{{ asset('images/title-banner.png') }}" alt="Luxestay">
            </figure>
         </div>
         <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
            <div class="sisf-m-inner">
               <div class="sisf-m-content sisf-content-grid">
                  <h1 class="sisf-m-title text-center entry-title">Order #{{ $order->id }}</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Page Section Start -->
      <div class="sisf-page-section section">
         <div class="sisf-grid sisf-layout--template">
            <div class="sisf-grid-inner container">
               <div class="row">
                  <div class="col-lg-3 col-md-4">
                     <div class="sisf-page-sidebar">
                        <nav class="account-navigation">
                           <ul class="list-unstyled">
                              <li><a href="{{ route('account.index') }}">Dashboard</a></li>
                              <li class="is-active"><a href="{{ route('orders.index') }}">Orders</a></li>
                              <li><a href="{{ route('account.downloads') }}">Downloads</a></li>
                              <li><a href="{{ route('account.address') }}">Addresses</a></li>
                              <li><a href="{{ route('account.edit') }}">Account details</a></li>
                              <li>
                                 <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0">Logout</button>
                                 </form>
                              </li>
                           </ul>
                        </nav>
                     </div>
                  </div>
                  <div class="col-lg-9 col-md-8">
                     <div class="account-content">
                        <div class="mb-4">
                           <a href="{{ route('orders.index') }}" class="sisf-button sisf-layout--outlined">
                              <i class="fa-solid fa-arrow-left me-2"></i>Back to Orders
                           </a>
                        </div>
                        <div class="order-detail-meta mb-4">
                           <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                           <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                           <p><strong>Payment:</strong> {{ ucfirst($order->payment_status) }}</p>
                        </div>
                        <div class="cart-form-table table-responsive">
                           <table class="shop_table cart_table table">
                              <thead>
                                 <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($order->items as $item)
                                 <tr>
                                    <td>{{ $item->product ? $item->product->name : 'Product removed' }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                 </tr>
                                 @endforeach
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <th colspan="3">Total</th>
                                    <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                                 </tr>
                              </tfoot>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Page Section End -->
@endsection
