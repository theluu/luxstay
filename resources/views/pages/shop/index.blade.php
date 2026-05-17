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
                  <h1 class="sisf-m-title text-center entry-title">Cửa hàng</h1>
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
                        <!-- Product Categories Start -->
                        <div class="sidebar-widget widget_categories">
                           <h3 class="sidebar-title">Danh mục</h3>
                           <div class="product-categories">
                              <ul class="product-categories-list">
                                 <li class="product-categories-list-item">
                                    <a href="{{ route('shop.index', array_filter(['orderby' => $orderby])) }}">
                                       <span>Tất cả</span>
                                    </a>
                                 </li>
                                 @foreach($categories as $cat)
                                 <li class="product-categories-list-item {{ ($category ?? null) == $cat->id ? 'current-cat' : '' }}">
                                    <a href="{{ route('shop.index', array_filter(['category' => $cat->id, 'orderby' => $orderby])) }}">
                                    <span>{{ $cat->name }}</span>
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
                           <h3 class="sidebar-title">Sản phẩm nổi bật</h3>
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
                        <p class="product-result-count">Hiển thị {{ $products->firstItem() }}–{{ $products->lastItem() }} / {{ $products->total() }} sản phẩm</p>
                        <div class="product-ordering shop-sort" id="shopSort">
                           <button type="button" class="sort-trigger" id="sortTrigger" aria-haspopup="listbox" aria-expanded="false">
                              <span>{{ $sortLabel }}</span>
                              <i class="fas fa-chevron-down sort-chevron"></i>
                           </button>
                           <ul class="sort-menu" id="sortMenu" role="listbox">
                              @foreach($sortOptions as $val => $label)
                              <li role="option">
                                 <a href="{{ route('shop.index', array_filter(['category' => $category, 'orderby' => $val !== 'menu_order' ? $val : null])) }}"
                                    class="{{ $orderby === $val ? 'active' : '' }}">{{ $label }}</a>
                              </li>
                              @endforeach
                           </ul>
                        </div>
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
                                             <img src="{{ $product->thumbnail ? asset($product->thumbnail) : asset('images/product1.png') }}" class="image-fluid" alt="{{ $product->name }}">
                                          </figure>
                                       </a>
                                       <div class="sisf-m-button sisf--m-button text-center">
                                          <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                             @csrf
                                             <input type="hidden" name="product_id" value="{{ $product->id }}">
                                             <input type="hidden" name="quantity" value="1">
                                             <button type="submit" class="btn-default w-100"><span> Thêm vào giỏ</span></button>
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
                           <p class="col-12 text-center py-5">Không có sản phẩm nào.</p>
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

@push('styles')
<style>
.shop-sort { position: relative; }
.sort-trigger {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 9px 16px;
    background: #fff;
    border: 1px solid #ddd;
    cursor: pointer;
    font-size: 14px;
    font-family: inherit;
    color: #333;
    min-width: 210px;
    justify-content: space-between;
    transition: border-color .2s;
}
.sort-trigger:hover { border-color: #999; }
.sort-chevron { font-size: 12px; transition: transform .25s ease; }
.sort-trigger.is-open .sort-chevron { transform: rotate(180deg); }
.sort-menu {
    position: absolute;
    top: calc(100% + 2px);
    right: 0;
    list-style: none;
    margin: 0;
    padding: 6px 0;
    background: #111;
    min-width: 100%;
    border-radius: 6px;
    z-index: 200;
    opacity: 0;
    transform: translateY(-8px);
    pointer-events: none;
    transition: opacity .2s ease, transform .2s ease;
    box-shadow: 0 8px 24px rgba(0,0,0,.25);
}
.sort-menu.is-open { opacity: 1; transform: translateY(0); pointer-events: all; }
.sort-menu li a {
    display: block;
    padding: 11px 20px;
    color: #fff;
    font-size: 14px;
    text-decoration: none;
    transition: background .15s;
}
.sort-menu li a:hover, .sort-menu li a.active { background: #c9a96e; color: #fff; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var trigger = document.getElementById('sortTrigger');
    var menu    = document.getElementById('sortMenu');
    if (!trigger || !menu) return;

    // Restore scroll position after filter navigation
    var saved = sessionStorage.getItem('shopScrollY');
    if (saved) {
        window.scrollTo({ top: parseInt(saved), behavior: 'instant' });
        sessionStorage.removeItem('shopScrollY');
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = menu.classList.toggle('is-open');
        trigger.classList.toggle('is-open', open);
        trigger.setAttribute('aria-expanded', open);
    });

    document.addEventListener('click', function () {
        menu.classList.remove('is-open');
        trigger.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
    });

    menu.addEventListener('click', function (e) { e.stopPropagation(); });

    // Save scroll before navigating
    menu.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () {
            sessionStorage.setItem('shopScrollY', window.scrollY);
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            menu.classList.remove('is-open');
            trigger.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        }
    });
})();
</script>
@endpush
