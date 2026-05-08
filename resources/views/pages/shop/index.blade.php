@extends('layouts.app')
@section('title', 'Shop – LuxeStay')
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
                  <h1 class="sisf-m-title text-center entry-title">Shop</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Page-Section Start -->
      <div class="sisf-page-section section">
         <div class="sisf-grid sisf-layout--template">
            <div class="sisf-grid-inner container">
               <div class="row">
                  <div class="col-lg-3 col-md-4">
                     <div class="sisf-page-sidebar">
                        <div class="sidebar-widget widget_search">
                           <div class="sisf-search-form">
                              <div class="sisf-search-form-inner border-0">
                                 <input type="search" class="sisf-search-form-field " name="s" value="" placeholder="Search…" required="">
                                 <button type="submit" class="sisf-search-form-button text-black">
                                 <i class="fa-solid fa-magnifying-glass"></i>
                                 </button>
                              </div>
                           </div>
                        </div>
                        <div class="sidebar-separator">
                           <hr class="separator sidebar-line">
                        </div>
                        <!-- Product Categories Start -->
                        <div class="sidebar-widget widget_categories">
                           <h3 class="sidebar-title">Categories</h3>
                           <div class="product-categories">
                              <ul class="product-categories-list">
                                 @foreach($categories as $category)
                                 <li class="product-categories-list-item">
                                    <a href="{{ route('shop.index') }}?category={{ $category->id }}">
                                    <span>{{ $category->name }}</span>
                                    </a>
                                 </li>
                                 @endforeach
                              </ul>
                           </div>
                        </div>
                        <!-- Product Categories End -->
                        <div class="sidebar-separator">
                           <hr class="separator sidebar-line">
                        </div>
                        <!-- Popular Products Start -->
                        <div class="sidebar-widget widget_popular_products">
                           <h3 class="sidebar-title">Feature Products</h3>
                           <div class="sidebar_content-list">
                              <ul class="content_list_widget">
                                 <li>
                                    <div class="sisf-image">
                                       <a href="{{ route('shop.index') }}"><img src="{{ asset('images/product8.png') }}" class="image-fluid" alt="Luxestay"></a>
                                    </div>
                                    <div class="sisf-product-content">
                                       <h5 class="sisf-product-title mb-1"><a href="{{ route('shop.index') }}">Linen Soap</a></h5>
                                       <div class="sisf-ratings">
                                          <div class="sisf-m-star sisf--initial">
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                          </div>
                                       </div>
                                       <div class="sisf-product-price price m-0">
                                          <span class="product-price-amount">$ 11.00</span>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="sisf-image">
                                       <a href="{{ route('shop.index') }}"><img src="{{ asset('images/product7.png') }}" class="image-fluid" alt="Luxestay"></a>
                                    </div>
                                    <div class="sisf-product-content">
                                       <h5 class="sisf-product-title mb-1"><a href="{{ route('shop.index') }}">Keratin Conditioner</a></h5>
                                       <div class="sisf-ratings">
                                          <div class="sisf-m-star sisf--initial">
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                          </div>
                                       </div>
                                       <div class="sisf-product-price price m-0">
                                          <span class="product-price-amount">$ 180.00</span>
                                       </div>
                                    </div>
                                 </li>
                                 <li class="mb-4">
                                    <div class="sisf-image">
                                       <a href="{{ route('shop.index') }}"><img src="{{ asset('images/product3.png') }}" class="image-fluid" alt="Luxestay"></a>
                                    </div>
                                    <div class="sisf-product-content">
                                       <h5 class="sisf-product-title mb-1"><a href="{{ route('shop.index') }}">Daily Cleanser</a></h5>
                                       <div class="sisf-ratings">
                                          <div class="sisf-m-star sisf--initial">
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                             <span class="star sisf-e-colored">★</span>
                                          </div>
                                       </div>
                                       <div class="sisf-product-price price m-0">
                                          <span class="product-price-amount">$ 499.00</span>
                                       </div>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                        <!-- Popular Products End -->
                     </div>
                  </div>
                  <div class="col-lg-9 col-md-8">
                     <div class="sisf-shop-results">
                        <p class="product-result-count">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results</p>
                        <form class="product-ordering" >
                           <select name="orderby" class="orderby" data-minimum-results-for-search="Infinity" aria-label="Shop order" tabindex="-1" aria-hidden="true">
                              <option value="menu_order" selected="selected">Default sorting</option>
                              <option value="popularity">Sort by popularity</option>
                              <option value="rating">Sort by average rating</option>
                              <option value="date">Sort by latest</option>
                              <option value="price">Sort by price: low to high</option>
                              <option value="price-desc">Sort by price: high to low</option>
                           </select>
                           <i class="fas fa-chevron-down custom-toggle-icon"></i>
                        </form>
                     </div>
                     <!-- Product List Start -->
                     <div class="sisf-product-list">
                        <div class="row">
                           @forelse($products as $product)
                           <!-- Product-Item Start -->
                           <div class="col-lg-4 col-md-6">
                              <div class="product wow fadeIn">
                                 <div class="sisf-product-inner sisf-e-inner">
                                    <!-- Product Image Start -->
                                    <div class="sisf-product-image">
                                       <a href="{{ route('shop.show', $product->slug) }}">
                                          <figure>
                                             <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/product1.png') }}" class="image-fluid" alt="{{ $product->name }}">
                                          </figure>
                                       </a>
                                       <div class="sisf-m-button sisf--m-button text-center">
                                          <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                             @csrf
                                             <input type="hidden" name="product_id" value="{{ $product->id }}">
                                             <input type="hidden" name="quantity" value="1">
                                             <button type="submit" class="btn-default w-100"><span> Add to cart</span></button>
                                          </form>
                                       </div>
                                    </div>
                                    <!-- Product Image End -->
                                    <!-- Product Content Start -->
                                    <div class="sisf-e-product-content">
                                       <h5 class="sisf-e-product-title sisf-e-title entry-title">
                                          <a class="sisf-e-product-title-link shop-product" href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
                                       </h5>
                                       <div class="sisf-m-star sisf--initial my-2">
                                          <span class="star sisf-e-colored">★</span>
                                          <span class="star sisf-e-colored">★</span>
                                          <span class="star sisf-e-colored">★</span>
                                          <span class="star sisf-e-colored">★</span>
                                          <span class="star sisf-e-colored">★</span>
                                       </div>
                                       <div class="sisf-product-price price m-0">
                                          <span class="product-price-amount"><sup class="product-price-currencysymbol">$</sup>{{ number_format($product->price, 2) }}</span>
                                       </div>
                                    </div>
                                    <!-- Product Content End -->
                                 </div>
                              </div>
                           </div>
                           <!-- Product-Item End -->
                           @empty
                           <p class="col-12 text-center py-5">No products available.</p>
                           @endforelse
                        </div>
                     </div>
                     <!-- Product List End -->
                  </div>
               </div>
               <div class="page-pagination wow wow-bounce">
                  {{ $products->links() }}
               </div>
            </div>
         </div>
      </div>
      <!-- Page-Section End -->
@endsection
