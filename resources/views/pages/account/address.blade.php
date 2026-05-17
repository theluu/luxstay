@extends('layouts.app')
@section('title', 'Edit Address – LuxeStay')
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
                              <li class="myaccount-navigation-link myaccount-navigation-link-orders">
                                 <a href="{{ route('orders.index') }}">Đơn hàng</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-downloads">
                                 <a href="{{ route('account.downloads') }}">Tải xuống</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-edit-address is-active">
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
                           <form method="POST" action="{{ route('account.address.update') }}">
                              @csrf
                              @method('PUT')
                              <p>Các địa chỉ sau sẽ được dùng mặc định trên trang thanh toán.</p>
                              <div class="sisf-myaccount-addresses">
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="sisf-address-box">
                                          <div class="sisf-address-title">
                                             <h3>Địa chỉ thanh toán</h3>
                                             <a href="#" class="edit">Sửa</a>
                                          </div>
                                          <address>
                                             1532 Park Serrena Street,<br>Selgoes Park,<br> Los Angeles<br>90001, US
                                          </address>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="sisf-address-box">
                                          <div class="sisf-address-title">
                                             <h3>Địa chỉ giao hàng</h3>
                                             <a href="#" class="edit">Thêm</a>
                                          </div>
                                          <address>
                                             1532 Park Serrena Street,<br>Selgoes Park,<br> Los Angeles<br>90001, US
                                          </address>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Page Section End -->
@endsection
