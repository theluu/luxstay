@extends('layouts.app')
@section('title', 'Orders – LuxeStay')
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
               <div class="sisf-m-content sisf-content-grid ">
                  <h1 class="sisf-m-title text-center entry-title">Tài khoản của tôi</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Page Section Start -->
      <div class="sisf-page-section section">
         <div class="sisf-grid sisf-layout--template">
            <div class="sisf-grid-inner container">
               <div class="sisf-myaccount-sec">
                  <div class="row">
                     <div class="col-md-3">
                        <div class="myaccount-navigation">
                           <ul class="list-unstyled">
                              <li class="myaccount-navigation-link myaccount-navigation-link-dashboard">
                                 <a href="{{ route('account.index') }}">Bảng điều khiển</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-orders is-active">
                                 <a href="{{ route('orders.index') }}">Đơn hàng</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-downloads">
                                 <a href="{{ route('account.downloads') }}">Tải xuống</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-edit-address">
                                 <a href="{{ route('account.address') }}">Địa chỉ</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-edit-account">
                                 <a href="{{ route('account.edit') }}">Thông tin tài khoản</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-customer-logout">
                                 <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;">Đăng xuất</button>
                                 </form>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <div class="col-md-9">
                        <div class="myaccount-content cart-form-table">
                           <div class="cart-scroll">
                              <table class="sisforders-table shop_table">
                                 <thead>
                                    <tr>
                                       <th scope="col"><span class="nobr">Đơn hàng</span></th>
                                       <th scope="col"><span class="nobr">Ngày</span></th>
                                       <th scope="col"><span class="nobr">Trạng thái</span></th>
                                       <th scope="col"><span class="nobr">Tổng cộng</span></th>
                                       <th scope="col"><span class="nobr">Thao tác</span></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                       <th>
                                          <a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a>
                                       </th>
                                       <td>
                                          <span>{{ $order->created_at->format('M d, Y') }}</span>
                                       </td>
                                       <td>
                                          {{ ucfirst($order->status) }}
                                       </td>
                                       <td>
                                          <span class="price-amount"><span class="price-currencysymbol">$</span>{{ number_format($order->total, 2) }}</span> for {{ count($order->items) }} item{{ count($order->items) !== 1 ? 's' : '' }}
                                       </td>
                                       <td>
                                          <a href="{{ route('orders.show', $order) }}" class="button view sisf-button sisf-layout--outlined btn-big text-uppercase text-center">Xem</a>
                                       </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center">Chưa có đơn hàng nào.</td></tr>
                                    @endforelse
                                 </tbody>
                              </table>
                           </div>
                           {{ $orders->links() }}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Page Section End -->
@endsection
