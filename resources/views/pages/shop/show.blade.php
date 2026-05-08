@extends('layouts.app')
@section('title', '{{ $product->name }} – LuxeStay')
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
      <!-- Page Section Start -->
      <div class="sisf-page-section section">
         <div class="sisf-single">
            <div class="container">
               <div class="sisf-single-top">
                  <div class="row sisf-single-top-row">
                     <div class="col-md-6">
                        <div class="sisf-single-image">
                           <div class="product-gallery">
                              <div class="gallery--section position-relative product-main-gallery">
                                 <div class="gallery-items page-gallery-box">
                                    <div class="product-gallery">
                                       <div class="wow fadeInUp">
                                          <a href="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/product_1.png') }}">
                                             <figure>
                                                <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/product_1.png') }}" class="image-fluid" alt="{{ $product->name }}">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              @if($product->gallery)
                              <div class="gallery--section position-relative">
                                 <div class="row gallery-items page-gallery-box d-flex justify-content-center">
                                    @foreach(array_slice((array)$product->gallery, 0, 3) as $galleryImage)
                                    <div class="col-lg-4 col-md-6">
                                       <div class="premium-gallery small-gallry">
                                          <a href="{{ asset('storage/' . $galleryImage) }}">
                                             <figure class="mb-0">
                                                <img src="{{ asset('storage/' . $galleryImage) }}" class="image-fluid" alt="{{ $product->name }}">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                    @endforeach
                                 </div>
                              </div>
                              @else
                              <div class="gallery--section position-relative">
                                 <div class="row gallery-items page-gallery-box d-flex justify-content-center">
                                    <div class="col-lg-4 col-md-6">
                                       <div class="premium-gallery small-gallry">
                                          <a href="{{ asset('images/shop-img-2.png') }}">
                                             <figure class="mb-0">
                                                <img src="{{ asset('images/shop-img-2.png') }}" class="image-fluid" alt="LuxeStay">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                       <div class="premium-gallery small-gallry">
                                          <a href="{{ asset('images/shop-img-3.png') }}">
                                             <figure class="mb-0">
                                                <img src="{{ asset('images/shop-img-3.png') }}" class="image-fluid" alt="LuxeStay">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                       <div class="premium-gallery small-gallry">
                                          <a href="{{ asset('images/shop-img-4.png') }}">
                                             <figure class="mb-0">
                                                <img src="{{ asset('images/shop-img-4.png') }}" class="image-fluid" alt="LuxeStay">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="single-product-summary">
                           <div class="sisf-product-title-box">
                              <h2 class="sisf-product-title">{{ $product->name }}</h2>
                              <div class="sisf-social-share">
                                 <span class="sisf-social-title">Share</span>
                                 <ul class="sisf-social-list">
                                    <li class="sisf-social-icon">
                                       <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                    <li class="sisf-social-icon">
                                       <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                                    </li>
                                    <li class="sisf-social-icon">
                                       <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                    </li>
                                    <li class="sisf-social-icon">
                                       <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                                    </li>
                                    <li class="sisf-social-icon">
                                       <a href="#"><i class="fa-brands fa-tumblr"></i></a>
                                    </li>
                                    <li class="sisf-social-icon">
                                       <a href="#"><i class="fa-brands fa-vk"></i></a>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                           <div class="sisf-ratings d-flex align-items-center mt-3">
                              <div class="sisf-m-star sisf--initial">
                                 <span class="star sisf-e-colored">★</span>
                                 <span class="star sisf-e-colored">★</span>
                                 <span class="star sisf-e-colored">★</span>
                                 <span class="star sisf-e-colored">★</span>
                                 <span class="star sisf-e-colored">★</span>
                              </div>
                              <a href="#reviews" class="review-link ps-2">
                              Reviews
                              </a>
                           </div>
                           <div class="price mt-2">
                              <span class="price-amount">
                              <span class="price-currencysymbol">$</span>{{ number_format($product->price, 2) }}
                              </span>
                           </div>
                           <div class="product-details-short-description mt-3">
                              <p>{!! nl2br(e($product->description)) !!}</p>
                           </div>
                           <div class="sisf-product_cart d-flex my-5">
                              <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center">
                                 @csrf
                                 <input type="hidden" name="product_id" value="{{ $product->id }}">
                                 <div class="sisf-quantity-buttons quantity me-3">
                                    <span class="sisf-quantity-minus">
                                    <i class="fas fa-chevron-down custom-toggle-icon"></i>
                                    </span>
                                    <input type="text" id="quantity" class="sisf-quantity-input" data-step="1" data-min="1" data-max="" name="quantity" value="1" title="Qty" size="4" placeholder="">
                                    <span class="sisf-quantity-plus">
                                    <i class="fas fa-chevron-down custom-toggle-icon"></i>
                                    </span>
                                 </div>
                                 <div class="sisf-m-button">
                                    <button type="submit" class="sisf-m sisf-button"><span>Add to cart</span></button>
                                 </div>
                              </form>
                           </div>
                           <div class="product_meta">
                              <span class="sku_wrapper">
                              <span class="sisf-meta-label">Stock:</span>
                              <span class="sisf-meta-value">{{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</span>
                              </span>
                              @if($product->category)
                              <span class="posted_in"><span class="sisf-meta-label">Category:</span>
                              <span class="sisf-meta-value"><a href="{{ route('shop.index') }}">{{ $product->category->name }}</a></span>
                              </span>
                              @endif
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="product-tabs wc-tabs-wrapper wow fadeInUp">
                  <!-- product tab Nav start -->
                  <div class="product-tab-nav" data-wow-delay="0.25s">
                     <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                           <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                           <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#additionalinformation" type="button" role="tab" aria-selected="false">Additional information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                           <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-selected="false">Reviews</button>
                        </li>
                     </ul>
                  </div>
                  <!-- product tab Nav End -->
                  <!-- Product Tab Box Start -->
                  <div class="product-tab-box tab-content" id="myTabContent">
                     <div class="our-vision-item tab-pane fade show active" id="description" role="tabpanel">
                        <div class="product-tab-content">
                           <p class="mb-3">{!! nl2br(e($product->description)) !!}</p>
                        </div>
                     </div>
                     <div class="our-vision-item tab-pane fade" id="additionalinformation" role="tabpanel">
                        <div class="product-tab-content">
                           <div class="product-tab-body">
                              <table class="product-information">
                                 <tbody>
                                    <tr>
                                       <th>Stock:</th>
                                       <td>{{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' available)' : 'Out of Stock' }}</td>
                                    </tr>
                                    @if($product->category)
                                    <tr>
                                       <th>Category:</th>
                                       <td>{{ $product->category->name }}</td>
                                    </tr>
                                    @endif
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="our-mission-item tab-pane fade" id="reviews" role="tabpanel">
                        <div class="product-tab-content">
                           <div class="product-tab-body">
                              <div class="product-reviews-col">
                                 <div class="review_form_wrapper review-form-wrapper mt-2">
                                    <div class="review_form">
                                       <h2 class="comment-reply-title">Add a review</h2>
                                       <form id="commentform">
                                          <p class="comment-notes">Your email address will not be published. Required fields are marked
                                             <span class="required">*</span>
                                          </p>
                                          <div class="comment-form-rating">
                                             <label>Your Rating <span class="required">*</span></label>
                                             <div class="star-rating my-4">
                                                <input type="radio" id="star5" name="rating" value="5">
                                                <label for="star5">★</label>
                                                <input type="radio" id="star4" name="rating" value="4">
                                                <label for="star4">★</label>
                                                <input type="radio" id="star3" name="rating" value="3" checked="">
                                                <label for="star3">★</label>
                                                <input type="radio" id="star2" name="rating" value="2">
                                                <label for="star2">★</label>
                                                <input type="radio" id="star1" name="rating" value="1">
                                                <label for="star1">★</label>
                                             </div>
                                          </div>
                                          <div class="review_form_box">
                                             <div class="form_box-grid d-flex justify-content-between gap-4">
                                                <div class="form_box-item">
                                                   <input id="author" name="author" placeholder="Your Name *" type="text" value="" size="30" maxlength="245" required="required">
                                                </div>
                                                <div class="form_box-item">
                                                   <input id="email" name="email" placeholder="Email *" type="text" value="" size="30" maxlength="100" required="required">
                                                </div>
                                             </div>
                                             <div class="comment-form-comment">
                                                <textarea id="comment" name="comment" placeholder="Your Review *" cols="45" rows="8" maxlength="65525" required="required"></textarea>
                                             </div>
                                             <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input" name="wp-comment-cookies-consent" type="checkbox" value="yes">
                                                <label class="form-check-label comment-box ms-2 mt-1">Save my name, and email in this browser for the next time I comment.</label>
                                             </div>
                                             <div class="sisf-m-button mt-4">
                                                <a href="#" class="btn-default rounded-0 btn-secondary"><span>Submit<i class="fa-solid fa-arrow-right"></i></span></a>
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
                  <!-- Product Tab Box End -->
               </div>
               <div class="related_products">
                  <h2>Related products</h2>
                  <div class="sisf-product-list">
                     <div class="row">
                        @foreach($related as $relatedProduct)
                        <!-- Product-Item Start -->
                        <div class="col-lg-3 col-md-6">
                           <div class="product wow fadeIn">
                              <div class="sisf-product-inner sisf-e-inner">
                                 <!-- Product Image Start -->
                                 <div class="sisf-product-image">
                                    <a href="{{ route('shop.show', $relatedProduct->slug) }}">
                                       <figure>
                                          <img src="{{ $relatedProduct->thumbnail ? asset('storage/' . $relatedProduct->thumbnail) : asset('images/product1.png') }}" class="image-fluid" alt="{{ $relatedProduct->name }}">
                                       </figure>
                                    </a>
                                    <div class="sisf-m-button sisf--m-button text-center">
                                       <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                          @csrf
                                          <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                          <input type="hidden" name="quantity" value="1">
                                          <button type="submit" class="btn-default w-100"><span> Add to cart</span></button>
                                       </form>
                                    </div>
                                 </div>
                                 <!-- Product Image End -->
                                 <!-- Product Content Start -->
                                 <div class="sisf-e-product-content">
                                    <h5 class="sisf-e-product-title sisf-e-title entry-title">
                                       <a class="sisf-e-product-title-link shop-product" href="{{ route('shop.show', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                                    </h5>
                                    <div class="sisf-m-star sisf--initial my-2">
                                       <span class="star sisf-e-colored">★</span>
                                       <span class="star sisf-e-colored">★</span>
                                       <span class="star sisf-e-colored">★</span>
                                       <span class="star sisf-e-colored">★</span>
                                       <span class="star sisf-e-colored">★</span>
                                    </div>
                                    <div class="sisf-product-price price m-0">
                                       <span class="product-price-amount"><sup class="product-price-currencysymbol">$</sup>{{ number_format($relatedProduct->price, 2) }}</span>
                                    </div>
                                 </div>
                                 <!-- Product Content End -->
                              </div>
                           </div>
                        </div>
                        <!-- Product-Item End -->
                        @endforeach
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Page Section End -->
@endsection
