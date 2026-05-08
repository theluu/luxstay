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
                  <h1 class="sisf-m-title text-center entry-title">My Account</h1>
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
                                 <a href="{{ route('account.index') }}">Dashboard</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-orders">
                                 <a href="{{ route('orders.index') }}">Orders</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-downloads">
                                 <a href="{{ route('account.downloads') }}">Downloads</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-edit-address">
                                 <a href="{{ route('account.address') }}">Addresses</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-edit-account">
                                 <a href="{{ route('account.edit') }}">Account details</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-customer-logout">
                                 <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;">Log out</button>
                                 </form>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <div class="col-md-9">
                        <div class="myaccount-content">
                           <p>Hello <strong>{{ $user->name }}</strong> (not <strong>{{ $user->name }}</strong>?
                              <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                 @csrf
                                 <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;text-decoration:underline;">Log out</button>
                              </form>)
                           </p>
                           <p>From your account dashboard you can view your <a href="{{ route('orders.index') }}">recent orders</a>, manage your <a href="{{ route('account.address') }}">shipping and billing addresses</a>, and <a href="{{ route('account.edit') }}">edit your password and account details</a>.</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Page Section End -->
@endsection
