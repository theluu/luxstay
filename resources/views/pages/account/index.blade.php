@extends('layouts.app')
@section('title', 'My Account – LuxeStay')
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
                              <li class="myaccount-navigation-link myaccount-navigation-link-dashboard is-active">
                                 <a href="{{ route('account.index') }}">Bảng điều khiển</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-orders">
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
                        <div class="myaccount-content">
                           <p>Xin chào <strong>{{ $user->name }}</strong> (không phải bạn?
                              <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                 @csrf
                                 <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;text-decoration:underline;">Đăng xuất</button>
                              </form>)
                           </p>
                           <p>Từ bảng điều khiển tài khoản, bạn có thể xem <a href="{{ route('orders.index') }}">đơn hàng gần đây</a>, quản lý <a href="{{ route('account.address') }}">địa chỉ giao hàng và thanh toán</a>, và <a href="{{ route('account.edit') }}">chỉnh sửa mật khẩu và thông tin tài khoản</a>.</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Page Section End -->
@endsection
