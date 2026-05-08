@extends('layouts.app')
@section('title', 'Offers & Promotions – LuxeStay')
@section('content')
      {{-- offers-promotions.html body content between header and footer --}}
      <!-- Banner Section Start -->
      <div class="sisf-banner position-relative">
         <div class="banner-img">
            <figure>
               <img src="{{ asset('images/offer-hero_img.png') }}" alt="Luxestay">
            </figure>
         </div>
         <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
            <div class="sisf-m-inner">
               <div class="sisf-m-content sisf-content-grid ">
                  <h1 class="sisf-m-title text-center entry-title">Offers & Promotions</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Best Offers And Promotion Section Start -->
      <div class="offers-and-promotion-section section">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <div class="best-offer--image wow slideInLeft">
                     <figure>
                        <img src="{{ asset('images/best_offer_img1.png') }}" class="w-100" alt="Luxestay">
                     </figure>
                  </div>
               </div>
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll">BEST OFFERS &<br> PROMOTIONS </h2>
                     <div class="sisf-m-text">
                        <p>Our Comfort Is Our Priority" expresses a commitment to providing the highest level of comfort and satisfaction for our customers. Whether you're staying with us, using our services, or purchasing our products, we prioritize your needs and ensure a relaxing and enjoyable experience.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
                  <div class="sisf-contact-desk d-flex align-items-center wow slideInRight">
                     <div class="phone-image me-3">
                        <figure>
                           <img src="{{ asset('images/phone.png') }}" alt="Luxestay">
                        </figure>
                     </div>
                     <div class="sisf-m-content mt-1">
                        <h5 class="sisf-m-title">
                           <span class="sisf-m-title-text">Contact Desk:</span>
                        </h5>
                        <div class="sisf-m-text">+1 850 6396 102</div>
                     </div>
                  </div>
                  <div class="best-offer-image wow slideInRight">
                     <figure>
                        <img src="{{ asset('images/best_offer_img2.png') }}" class="w-100" alt="Luxestay">
                     </figure>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Best Offers And Promotion Section End -->
      <!-- Pricing Table Section Start -->
      <div class="pricing-table bg-texture-light section">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title text-center section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll">Premier Corporate<br> Pricing for Luxury Suites</h2>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
            <div class="row align-items-center">
               <div class="col-lg-4 col-md-6">
                  <div class="sisf-sis-pricing-table sisf-sis-pricing-table-inner mt-0 wow rotateInDownLeft">
                     <div class="sisf-m-inner">
                        <div class="sisf-m-top">
                           <div class="sisf-m-price d-flex">
                              <div class="sisf-m-price-wrapper">
                                 <span class="sisf-m-price-currency">$</span>
                                 <span class="sisf-m-price-value">29</span>
                                 <span class="sisf-m-price-period">Month</span>
                              </div>
                           </div>
                           <div class="sisf-m-top-inner">
                              <h4 class="sisf-m-title">
                                 Standard Plan
                              </h4>
                           </div>
                        </div>
                        <ul class="sisf-m-content list-unstyled mb-0">
                           <li class="sisf-e-item pt-0">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">Transportation Service</span>
                           </li>
                           <li class="sisf-e-item">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">Spa & Wellness Treatment</span>
                           </li>
                           <li class="sisf-e-item">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">Food & Drinks</span>
                           </li>
                           <li class="sisf-e-item">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">24/7 Customer Support</span>
                           </li>
                        </ul>
                        <div class="sisf-m-button text-center">
                           <a href="#" class="btn-default">Buy Now</a>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4 col-md-6">
                  <div class="sisf-sis-pricing-table highlighted mt-0 wow zoomIn">
                     <div class="sisf-m-inner">
                        <div class="sisf-m-top">
                           <div class="sisf-m-price d-flex">
                              <div class="sisf-m-price-wrapper">
                                 <span class="sisf-m-price-currency sisf-e-colored">$</span>
                                 <span class="sisf-m-price-value sisf-e-colored">49</span>
                                 <span class="sisf-m-price-period text-white">Month</span>
                              </div>
                           </div>
                           <div class="sisf-m-top-inner">
                              <h4 class="sisf-m-title text-white">
                                 Pro Plan
                              </h4>
                           </div>
                        </div>
                        <ul class="sisf-m-content list-unstyled mb-0">
                           <li class="sisf-e-item text-white pt-0">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check"></i>
                              </span>
                              <span class="sisf-e-text">Transportation Service</span>
                           </li>
                           <li class="sisf-e-item text-white">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text"> Spa & Wellness Treatment</span>
                           </li>
                           <li class="sisf-e-item text-white">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">Food & Drinks</span>
                           </li>
                           <li class="sisf-e-item text-white">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">24/7 Customer Support</span>
                           </li>
                        </ul>
                        <div class="sisf-m-button text-center">
                           <a href="#" class="btn-default">Buy Now</a>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4 col-md-6">
                  <div class="sisf-sis-pricing-table sisf-sis-pricing-table-inner mt-0 wow rotateInDownRight">
                     <div class="sisf-m-inner">
                        <div class="sisf-m-top">
                           <div class="sisf-m-price d-flex">
                              <div class="sisf-m-price-wrapper">
                                 <span class="sisf-m-price-currency">$</span>
                                 <span class="sisf-m-price-value">99</span>
                                 <span class="sisf-m-price-period">Month</span>
                              </div>
                           </div>
                           <div class="sisf-m-top-inner">
                              <h4 class="sisf-m-title">
                                 Premium Plan
                              </h4>
                           </div>
                        </div>
                        <ul class="sisf-m-content list-unstyled mb-0">
                           <li class="sisf-e-item pt-0">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text"> Transportation Service</span>
                           </li>
                           <li class="sisf-e-item">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">Spa & Wellness Treatment</span>
                           </li>
                           <li class="sisf-e-item">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">Food & Drinks</span>
                           </li>
                           <li class="sisf-e-item">
                              <span class="sisf-e-icon">
                              <i class="fa-solid fa-check me-2"></i>
                              </span>
                              <span class="sisf-e-text">24/7 Customer Support</span>
                           </li>
                        </ul>
                        <div class="sisf-m-button text-center">
                           <a href="#" class="btn-default">Buy Now</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Pricing Table Section End -->
      <!-- Rooms And Suits Section Start -->
      <div class="rooms-and-suits-section section pb-0">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll">Hot Deal of the<br> Week!</h2>
                     <div class="sisf-m-text">
                        <p>Don't miss out on our exclusive deal! This week only, enjoy hot deal offer, "30% off on following room & suits.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
                  <div class="sisf-room-list-item sisf-room-list--item sisf-item--full wow bounceInLeft">
                     <div class="sisf-e-inner">
                        <div class="sisf-e-media position-relative">
                           <div class="sisf-single-slider sisf-e-media-slider">
                              <div class="swiper">
                                 <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image11.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image12.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image2.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- Swiper Buttons -->
                                 <div class="swiper-buttons">
                                    <span class="custom--icon custom-icon-left-1"><i class="fa-solid fa-play fa-flip-horizontal"></i></span>
                                    <span class="custom--icon custom-icon-right-1"><i class="fa-solid fa-play"></i></span>
                                 </div>
                              </div>
                           </div>
                           <span class="sisf-e-price">
                           <span class="sisf-e-price-label">From</span>
                           <span class="sisf-e-price-value">$899</span>
                           </span>
                        </div>
                        <div class="sisf-e-content">
                           <div class="sisf-e-content-info">
                              <ul class="sisf-e-basic-info d-flex align-items-center ps-0 mb-3">
                                 <li class="sisf-e-item sisf-e-room-size text-black ms-0">
                                    <span><img src="{{ asset('images/small-img1.png') }}" alt="LuxeStay"></span>
                                    <span>65m2</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-capacity text-black">
                                    <span><img src="{{ asset('images/small-img2.png') }}" alt="LuxeStay"></span>
                                    <span>4 Guests</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-beds text-black">
                                    <span><img src="{{ asset('images/small-img3.png') }}" alt="LuxeStay"></span>
                                    <span>2 Beds</span>
                                 </li>
                              </ul>
                           </div>
                           <div class="sisf-e-content-text">
                              <h4 class="sisf-e-title entry-title mb-2">
                                 <a href="{{ route('rooms.index') }}">
                                 Presidential Suite
                                 </a>
                              </h4>
                              <p class="sisf-e-excerpt mb-2">The Presidential Suite is the pinnacle of luxury a</p>
                              <div class="sisf-m-button sisf-sis-clear">
                                 <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.index') }}">
                                 <span class="sisf-m-text">Explore More</span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="sisf-room-list-item sisf-room-list--item sisf-item--full wow bounceInLeft">
                     <div class="sisf-e-inner">
                        <div class="sisf-e-media position-relative">
                           <div class="sisf-single-slider sisf-e-media-slider">
                              <div class="swiper">
                                 <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image5.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image13.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image2.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- Swiper Buttons -->
                                 <div class="swiper-buttons">
                                    <span class="custom--icon custom-icon-left-2"><i class="fa-solid fa-play fa-flip-horizontal"></i></span>
                                    <span class="custom--icon custom-icon-right-2"><i class="fa-solid fa-play"></i></span>
                                 </div>
                              </div>
                           </div>
                           <span class="sisf-e-price">
                           <span class="sisf-e-price-label">From</span>
                           <span class="sisf-e-price-value">$799</span>
                           </span>
                        </div>
                        <div class="sisf-e-content">
                           <div class="sisf-e-content-info">
                              <ul class="sisf-e-basic-info d-flex align-items-center ps-0 mb-3">
                                 <li class="sisf-e-item sisf-e-room-size text-black ms-0">
                                    <span><img src="{{ asset('images/small-img1.png') }}" alt="LuxeStay"></span>
                                    <span>60m2</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-capacity text-black">
                                    <span><img src="{{ asset('images/small-img2.png') }}" alt="LuxeStay"></span>
                                    <span>4 Guests</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-beds text-black">
                                    <span><img src="{{ asset('images/small-img3.png') }}" alt="LuxeStay"></span>
                                    <span>2 Beds</span>
                                 </li>
                              </ul>
                           </div>
                           <div class="sisf-e-content-text">
                              <h4 class="sisf-e-title entry-title mb-2">
                                 <a href="{{ route('rooms.index') }}">
                                 Ocean View Room
                                 </a>
                              </h4>
                              <p class="sisf-e-excerpt mb-2">Experience the ultimate in coastal luxury with our</p>
                              <div class="sisf-m-button sisf-sis-clear">
                                 <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.index') }}">
                                 <span class="sisf-m-text">Explore More</span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="sisf-room-list-item sisf-room-list--item sisf-item--full wow zoomInUp">
                     <div class="sisf-e-inner">
                        <div class="sisf-e-media position-relative">
                           <div class="sisf-single-slider sisf-e-media-slider">
                              <div class="swiper">
                                 <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image4.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image1.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image13.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- Swiper Buttons -->
                                 <div class="swiper-buttons">
                                    <span class="custom--icon custom-icon-left-3"><i class="fa-solid fa-play fa-flip-horizontal"></i></span>
                                    <span class="custom--icon custom-icon-right-3"><i class="fa-solid fa-play"></i></span>
                                 </div>
                              </div>
                           </div>
                           <span class="sisf-e-price">
                           <span class="sisf-e-price-label">From</span>
                           <span class="sisf-e-price-value">$399</span>
                           </span>
                        </div>
                        <div class="sisf-e-content">
                           <div class="sisf-e-content-info">
                              <ul class="sisf-e-basic-info d-flex align-items-center ps-0 mb-3">
                                 <li class="sisf-e-item sisf-e-room-size text-black ms-0">
                                    <span><img src="{{ asset('images/small-img1.png') }}" alt="LuxeStay"></span>
                                    <span>60m2</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-capacity text-black">
                                    <span><img src="{{ asset('images/small-img2.png') }}" alt="LuxeStay"></span>
                                    <span>8 Guests</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-beds text-black">
                                    <span><img src="{{ asset('images/small-img3.png') }}" alt="LuxeStay"></span>
                                    <span>1 Bed</span>
                                 </li>
                              </ul>
                           </div>
                           <div class="sisf-e-content-text">
                              <h4 class="sisf-e-title entry-title mb-2">
                                 <a href="{{ route('rooms.index') }}">
                                 Poolside Room
                                 </a>
                              </h4>
                              <p class="sisf-e-excerpt mb-2">Relax in our stylish Poolside Room, designed to of</p>
                              <div class="sisf-m-button sisf-sis-clear">
                                 <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.index') }}">
                                 <span class="sisf-m-text">Explore More</span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="sisf-room-list-item sisf-room-list--item sisf-item--full wow bounceInRight">
                     <div class="sisf-e-inner">
                        <div class="sisf-e-media position-relative">
                           <div class="sisf-single-slider sisf-e-media-slider">
                              <div class="swiper">
                                 <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image6.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image7.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                    <div class="swiper-slide">
                                       <div class="sisf-image-holder">
                                          <figure>
                                             <img src="{{ asset('images/room-slider-image8.png') }}" class="w-100" alt="Luxestay">
                                          </figure>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- Swiper Buttons -->
                                 <div class="swiper-buttons">
                                    <span class="custom--icon custom-icon-left-4"><i class="fa-solid fa-play fa-flip-horizontal"></i></span>
                                    <span class="custom--icon custom-icon-right-4"><i class="fa-solid fa-play"></i></span>
                                 </div>
                              </div>
                           </div>
                           <span class="sisf-e-price">
                           <span class="sisf-e-price-label">From</span>
                           <span class="sisf-e-price-value">$399</span>
                           </span>
                        </div>
                        <div class="sisf-e-content">
                           <div class="sisf-e-content-info">
                              <ul class="sisf-e-basic-info d-flex align-items-center ps-0 mb-3">
                                 <li class="sisf-e-item sisf-e-room-size text-black ms-0">
                                    <span><img src="{{ asset('images/small-img1.png') }}" alt="LuxeStay"></span>
                                    <span>65m2</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-capacity text-black">
                                    <span><img src="{{ asset('images/small-img2.png') }}" alt="LuxeStay"></span>
                                    <span>6 Guests</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-beds text-black">
                                    <span><img src="{{ asset('images/small-img3.png') }}" alt="LuxeStay"></span>
                                    <span>2 Beds</span>
                                 </li>
                              </ul>
                           </div>
                           <div class="sisf-e-content-text">
                              <h4 class="sisf-e-title entry-title mb-2">
                                 <a href="{{ route('rooms.index') }}">
                                 Mountain View Room
                                 </a>
                              </h4>
                              <p class="sisf-e-excerpt mb-2">Experience ultimate tranquility in our Mountain Vi</p>
                              <div class="sisf-m-button sisf-sis-clear">
                                 <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.index') }}">
                                 <span class="sisf-m-text">Explore More</span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="sisf-m-button wow bounceInRight">
                     <a href="{{ route('rooms.index') }}" class="btn-default">View More</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Rooms And Suits Section End -->
@endsection
