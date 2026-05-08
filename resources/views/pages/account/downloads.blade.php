@extends('layouts.app')
@section('title', 'Downloads – LuxeStay')
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
                              <li class="myaccount-navigation-link myaccount-navigation-link-dashboard">
                                 <a href="{{ route('account.index') }}">Dashboard</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-orders">
                                 <a href="{{ route('orders.index') }}">Orders</a>
                              </li>
                              <li class="myaccount-navigation-link myaccount-navigation-link-downloads is-active">
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
                        <div class="myaccount-content cart-form-table">
                           <div class="myaccount-info">
                              <a class="button sisf-button sisf-layout--outlined text-uppercase" href="{{ route('shop.index') }}">Browse products</a> No downloads available yet.
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Page Section End -->
@endsection
