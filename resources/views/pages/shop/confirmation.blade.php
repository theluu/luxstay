@extends('layouts.app')
@section('title', 'Order Confirmed – LuxeStay')
@section('content')

<div class="sisf-banner position-relative">
   <div class="banner-img">
      <figure><img src="{{ asset('images/title-banner.png') }}" alt="LuxeStay"></figure>
   </div>
   <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
      <div class="sisf-m-inner">
         <div class="sisf-m-content sisf-content-grid">
            <h1 class="sisf-m-title text-center entry-title">Đặt hàng thành công</h1>
         </div>
      </div>
   </div>
</div>

<div class="section">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-6 text-center">
            <div style="font-size:64px;line-height:1;margin-bottom:24px">✓</div>
            <h2 class="mb-3">Cảm ơn bạn đã đặt hàng!</h2>
            <p class="text-muted mb-2">Đơn hàng <strong>#{{ $order->id }}</strong> đã được đặt thành công.</p>
            <p class="text-muted mb-5">Xác nhận sẽ được gửi đến <strong>{{ $address['email'] ?? '' }}</strong>.</p>

            <div class="card border-0 shadow-sm rounded-3 p-4 mb-5 text-start">
               <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>
               <table class="table table-borderless mb-0">
                  <tbody>
                     @foreach($order->items as $item)
                     <tr>
                        <td class="ps-0 text-muted">{{ $item->product?->name ?? 'Product' }} × {{ $item->quantity }}</td>
                        <td class="text-end">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                     </tr>
                     @endforeach
                     <tr class="border-top">
                        <td class="ps-0 text-muted pt-3">Tạm tính</td>
                        <td class="text-end pt-3">${{ number_format($order->subtotal, 2) }}</td>
                     </tr>
                     <tr>
                        <td class="ps-0 text-muted">Vận chuyển</td>
                        <td class="text-end">{{ $order->shipping_fee > 0 ? '$'.number_format($order->shipping_fee,2) : 'Miễn phí' }}</td>
                     </tr>
                     <tr class="border-top">
                        <td class="ps-0 fw-bold pt-2">Tổng cộng</td>
                        <td class="fw-bold text-end pt-2">${{ number_format($order->total, 2) }}</td>
                     </tr>
                  </tbody>
               </table>
            </div>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
               <a href="{{ route('shop.index') }}" class="btn-default">Tiếp tục mua sắm</a>
               @auth
               <a href="{{ route('orders.index') }}" class="sisf-shortcode sisf-text-underline sisf-underline--left">
                  <span class="sisf-m-text">Đơn hàng của tôi</span>
               </a>
               @else
               <a href="{{ route('login') }}" class="sisf-shortcode sisf-text-underline sisf-underline--left">
                  <span class="sisf-m-text">Đăng nhập để theo dõi đơn hàng</span>
               </a>
               @endauth
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
