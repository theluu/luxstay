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
                              <li><a href="{{ route('account.index') }}">Bảng điều khiển</a></li>
                              <li class="is-active"><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
                              <li><a href="{{ route('account.downloads') }}">Tải xuống</a></li>
                              <li><a href="{{ route('account.address') }}">Địa chỉ</a></li>
                              <li><a href="{{ route('account.edit') }}">Thông tin tài khoản</a></li>
                              <li>
                                 <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0">Đăng xuất</button>
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
                              <i class="fa-solid fa-arrow-left me-2"></i>Quay lại đơn hàng
                           </a>
                        </div>
                        <div class="order-detail-meta mb-4">
                           <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                           <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
                           <p><strong>Thanh toán:</strong> {{ ucfirst($order->payment_status) }}</p>
                        </div>
                        <div class="cart-form-table table-responsive">
                           <table class="shop_table cart_table table">
                              <thead>
                                 <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>SL</th>
                                    <th>Tạm tính</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($order->items as $item)
                                 <tr>
                                    <td>{{ $item->product ? $item->product->name : 'Sản phẩm đã bị xóa' }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                 </tr>
                                 @endforeach
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <th colspan="3">Tổng cộng</th>
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
