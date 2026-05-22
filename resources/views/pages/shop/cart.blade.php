@extends('layouts.app')
@section('title', 'Cart – LuxeStay')
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
                  <h1 class="sisf-m-title text-center entry-title">{{ __('shop.cart_title') }}</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Page Section Start -->
      <div class="sisf-page-section section">
         <div class="sisf-grid sisf-layout--template">
            <div class="sisf-grid-inner container">
               <div class="sisf--cart">
                  <div class="cart-form-table table-responsive">
                     <div class="cart-scroll">
                        <table class="shop_table cart_table table">
                           <thead>
                              <tr>
                                 <th class="product-thumbnail">
                                    <span class="screen-reader-text"> </span>
                                 </th>
                                 <th class="product-name">{{ __('shop.product_col') }}</th>
                                 <th class="product-price">Giá</th>
                                 <th class="product-quantity">{{ __('shop.quantity_col') }}</th>
                                 <th class="product-subtotal">Tạm tính</th>
                                 <th class="product-remove"><span class="screen-reader-text"> </span></th>
                              </tr>
                           </thead>
                           <tbody>
                              @forelse($items as $item)
                              <!-- Cart Item Start -->
                              <tr class="cart_item sisf-product-type-product">
                                 <td class="product-thumbnail">
                                    <a href="{{ route('shop.show', $item['product']->slug) }}">
                                       <img src="{{ $item['product']->thumbnail ? asset($item['product']->thumbnail) : asset('images/product_1.png') }}" class="image-fluid" alt="{{ $item['product']->name }}">
                                    </a>
                                 </td>
                                 <td class="product-name" data-title="Sản phẩm">
                                    <a href="{{ route('shop.show', $item['product']->slug) }}">{{ $item['product']->name }}</a>
                                 </td>
                                 <td class="product-price" data-title="Giá">{{ $item['quantity'] }} x <span class="price-amount"><span class="Price-currencySymbol">$</span>{{ number_format($item['product']->price, 2) }}</span>
                                 </td>
                                 <td class="product-quantity" data-title="Số lượng">
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center gap-2">
                                       @csrf
                                       <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                       <div class="sisf-quantity-buttons quantity">
                                          <span class="sisf-quantity-minus">
                                          <i class="fas fa-chevron-down custom-toggle-icon"></i>
                                          </span>
                                          <input type="number" class="sisf-quantity-input" min="1" name="quantity" value="{{ $item['quantity'] }}" title="Qty" size="4">
                                          <span class="sisf-quantity-plus">
                                          <i class="fas fa-chevron-down custom-toggle-icon"></i>
                                          </span>
                                       </div>
                                       <button type="submit" class="border-0 bg-transparent text-muted" title="Update"><i class="fa fa-rotate-right"></i></button>
                                    </form>
                                 </td>
                                 <td class="product-subtotal">
                                    <span class="price-amount"><span class="price-currencysymbol">$</span>{{ number_format($item['product']->price * $item['quantity'], 2) }}</span>
                                 </td>
                                 <td class="product-remove">
                                    <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                       @csrf
                                       <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                       <button type="submit" class="remove border-0 bg-transparent"><i class="fa fa-times"></i></button>
                                    </form>
                                 </td>
                              </tr>
                              <!-- Cart Item End -->
                              @empty
                              <tr><td colspan="6" class="text-center">{{ __('shop.empty_cart_msg') }}</td></tr>
                              @endforelse
                           </tbody>
                           <tfoot>
                              <tr>
                                 <td colspan="6" class="actions">
                                    <div class="d-flex justify-content-end">
                                       <a href="{{ route('shop.index') }}" class="button sisf-button sisf-layout--outlined">{{ __('shop.continue_shopping') }}</a>
                                    </div>
                                 </td>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                     <div class="cart-collaterals">
                        <div class="cart_totals">
                           <h2>{{ __('shop.cart_total') }}</h2>
                           <table class="shop_table">
                              <tbody>
                                 <tr class="cart-subtotal">
                                    <th>Tạm tính</th>
                                    <td data-title="Tạm tính"><span class="price-amount"><span class="price-currencysymbol">$</span>{{ number_format($total, 2) }}</span></td>
                                 </tr>
                                 <tr class="order-total">
                                    <th>Tổng cộng</th>
                                    <td data-title="Tổng cộng"><strong><span class="price-amount"><span class="price-currencysymbol">$</span>{{ number_format($total, 2) }}</span></strong></td>
                                 </tr>
                              </tbody>
                           </table>
                           <div class="proceed-to-checkout text-center mt-3">
                              <a href="{{ route('checkout.index') }}" class="checkout-button button sisf-button sisf-layout--outlined">{{ __('shop.proceed_checkout') }}</a>
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
