@extends('layouts.app')
@section('title', 'Edit Account – LuxeStay')
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
                              <li class="myaccount-navigation-link myaccount-navigation-link-edit-address">
                                 <a href="{{ route('account.address') }}">Địa chỉ</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-edit-account is-active">
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
                           <form class="billing-form" method="POST" action="{{ route('account.update') }}">
                              @csrf
                              @method('PUT')
                              <div class="sisf-billing-fields mb-3">
                                 <div class="billing-field-wrapper">
                                    <div class="row">
                                       <div class="col-lg-6">
                                          <div class="form-row">
                                             <label>Họ<span class="required">*</span></label>
                                             <input type="text" name="first_name" placeholder="" class="input-text" value="{{ old('first_name', $user->name) }}">
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="form-row">
                                             <label>Tên<span class="required">*</span></label>
                                             <input type="text" name="last_name" placeholder="" class="input-text" value="{{ old('last_name') }}">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="form-row">
                                             <label>Tên hiển thị<span class="required">*</span></label>
                                             <input type="text" name="display_name" placeholder="" class="input-text" value="{{ old('display_name', $user->name) }}">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="form-row">
                                             <label>Địa chỉ email<span class="required">*</span></label>
                                             <input type="email" name="email" placeholder="" class="input-text" value="{{ old('email', $user->email) }}">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="form-row">
                                             <label>Mật khẩu hiện tại (để trống nếu không thay đổi)</label>
                                             <input type="password" name="current_password" placeholder="" class="input-text">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="form-row">
                                             <label>Mật khẩu mới (để trống nếu không thay đổi)</label>
                                             <input type="password" name="password" placeholder="" class="input-text">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="form-row">
                                             <label>Xác nhận mật khẩu mới</label>
                                             <input type="password" name="password_confirmation" placeholder="" class="input-text">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="form-row">
                                             <button type="submit" class="button sisf-button sisf-layout--outlined btn-big text-uppercase">Lưu thay đổi</button>
                                          </div>
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
