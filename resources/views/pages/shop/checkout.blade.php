@extends('layouts.app')

@section('title', 'Checkout – LuxeStay')

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
                  <h1 class="sisf-m-title text-center entry-title">{{ __('shop.checkout_title') }}</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Page Section Start -->
      <div class="sisf-page-section section">
         <div class="sisf-grid sisf-layout--template">
            <div class="sisf-grid-inner container">
               <div class="sisf-checkout">
                  <div class="row">
                     <div class="col-md-8">
                        <div class="sisf-checkout-left">
                           <div class="sisf-checkout-left-inner">
                              <form class="checkout-form" action="{{ route('checkout.store') }}" method="POST" data-recaptcha data-recaptcha-action="checkout">
                                 @csrf
                                 <div class="sisf-billing-fields mb-3">
                                    <h3 class="checkout-title">{{ __('shop.billing_info') }}</h3>
                                    <div class="billing-field-wrapper">
                                       <div class="row">
                                          <div class="col-lg-6">
                                             <div class="form-row">
                                                @php $nameParts = $user ? explode(' ', $user->name, 2) : ['','']; @endphp
                                                <input type="text" class="input-text" name="billing_first_name" id="billing_first_name" placeholder="{{ __('shop.first_name') }}" value="{{ old('billing_first_name', $nameParts[0] ?? '') }}" required autocomplete="given-name">
                                             </div>
                                          </div>
                                          <div class="col-lg-6">
                                             <div class="form-row">
                                                <input type="text" class="input-text" name="billing_last_name" id="billing_last_name" placeholder="{{ __('shop.last_name') }}" value="{{ old('billing_last_name', $nameParts[1] ?? '') }}" required autocomplete="family-name">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-lg-12">
                                             <div class="form-row">
                                                <input type="text" class="input-text " name="billing_company" id="billing_company" placeholder="{{ __('shop.company') }}" value="" autocomplete="organization">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-lg-12">
                                             <div class="form-row">
                                                <input type="text" class="input-text" name="billing_address_1" id="billing_address_1" placeholder="{{ __('shop.address_1') }}" value="{{ old('billing_address_1') }}" required autocomplete="address-line1">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-lg-12">
                                             <div class="form-row">
                                                <input type="text" class="input-text " name="billing_address_2" id="billing_address_2" placeholder="{{ __('shop.address_2') }}" value="" autocomplete="address-line2">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-lg-12">
                                             <div class="form-row">
                                                <input type="text" class="input-text" name="billing_city" id="billing_city" placeholder="{{ __('shop.city') }}" value="{{ old('billing_city') }}" required autocomplete="address-level2">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-lg-12">
                                             <div class="form-row">
                                                <input type="text" class="input-text " name="billing_postcode" id="billing_postcode" placeholder="{{ __('shop.postcode') }}" value="" aria-required="true" autocomplete="postal-code">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-lg-12">
                                             <div class="form-row">
                                                <input type="tel" class="input-text" name="billing_phone" id="billing_phone" placeholder="{{ __('shop.phone_label') }}" value="{{ old('billing_phone') }}" required autocomplete="tel">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-lg-12">
                                             <div class="form-row">
                                                <input type="email" class="input-text mb-0" name="billing_email" id="billing_email" placeholder="{{ __('shop.email_label') }}" value="{{ old('billing_email', $user?->email ?? '') }}" required>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="sisf-additional-fields">
                                    <h3 class="checkout-title">{{ __('shop.additional_info') }}</h3>
                                    <div class="additional-field-wrapper"></div>
                                 </div>
                                 <div class="sisf-checkout-payment">
                                    <h3 class="checkout-title">{{ __('shop.payment_method') }}</h3>
                                    <div class="sisf-payment_methods">
                                       <ul class="payment_methods">
                                          <li class="payment_method_cod">
                                             <input id="payment_method_cod" type="radio" class="input-radio" name="payment_method" value="cod" @checked(old('payment_method', 'cod') === 'cod')>
                                             <label for="payment_method_cod">{{ __('shop.cod') }}</label>
                                             <p>{{ __('shop.cod_desc') }}</p>
                                          </li>
                                          <li class="payment_method_vnpay">
                                             <input id="payment_method_vnpay" type="radio" class="input-radio" name="payment_method" value="vnpay" @checked(old('payment_method') === 'vnpay')>
                                             <label for="payment_method_vnpay">{{ __('shop.vnpay') }}</label>
                                             <p>{{ __('shop.vnpay_desc') }}</p>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                                 <div class="form-row place-order">
                                    <a class="button back-to-cart" href="{{ route('cart.index') }}"><i class="fa-solid fa-arrow-left me-3"></i>{{ __('shop.back_to_cart') }}</a>
                                    <button type="submit" class="button sisf-button sisf-layout--outlined btn-big text-uppercase">{{ __('shop.place_order') }}</button>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="sisf-checkout-right">
                           <div class="sisf-checkout-cart-items">
                              <h3 class="checkout-title">{{ __('shop.your_cart') }}</h3>
                              <ul class="sisf-checkout-cart-items-content list-unstyled">
                                 @foreach($items as $item)
                                 <li class="cart_item sisf-product-type-product">
                                    <div class="cart_item-inner d-flex align-items-center">
                                       <div class="sisf-e-image">
                                          <a href="#"><img src="{{ $item['product']->thumbnail ? asset($item['product']->thumbnail) : asset('images/product_1.png') }}" class="image-fluid" alt="{{ $item['product']->name }}"></a>
                                       </div>
                                       <div class="product-name">
                                          <h5>{{ $item['product']->name }}</h5>
                                          <p class="sisf-e-quantity-price mb-0"><strong class="product-quantity">{{ $item['quantity'] }}<i class="fa fa-times px-2"></i></strong>${{ number_format($item['product']->price, 2) }}</p>
                                       </div>
                                    </div>
                                 </li>
                                 @endforeach
                              </ul>
                           </div>
                           <div class="sisf-checkout-cart-totals cart-form-table mt-4 pt-3">
                              <h3 class="checkout-title">{{ __('shop.cart_total') }}</h3>
                              <table class="shop_table">
                                 <tbody>
                                    <tr class="cart-subtotal">
                                       <th>{{ __('shop.subtotal') }}</th>
                                       <td><span class="price-amount"><span class="price-currencysymbol">$</span>{{ number_format($subtotal, 2) }}</span></td>
                                    </tr>
                                    <tr class="cart-shipping-fee">
                                       <th>{{ __('shop.shipping') }}</th>
                                       <td>
                                          @if($shippingFee == 0)
                                             <span class="text-success">{{ __('shop.free_shipping') }}</span>
                                          @else
                                             <span class="price-amount"><span class="price-currencysymbol">$</span>{{ number_format($shippingFee, 2) }}</span>
                                             <small class="d-block text-muted">{{ __('shop.free_over') }}</small>
                                          @endif
                                       </td>
                                    </tr>
                                    <tr class="order-total">
                                       <th>{{ __('shop.total') }}</th>
                                       <td><strong><span class="price-amount"><span class="price-currencysymbol">$</span>{{ number_format($grandTotal, 2) }}</span></strong></td>
                                    </tr>
                                 </tbody>
                              </table>
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
