@extends('layouts.app')
@section('title', 'About Us – LuxeStay')
@section('content')
      {{-- about-us.html body content between header and footer --}}
      <!-- Banner Section Start -->
      <div class="sisf-banner position-relative">
         <div class="banner-img">
            <figure>
               <img src="{{ asset('images/about_hero_img.png') }}" alt="Luxestay">
            </figure>
         </div>
         <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
            <div class="sisf-m-inner">
               <div class="sisf-m-content sisf-content-grid ">
                  <h1 class="sisf-m-title text-center entry-title">About Us</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Quality Services Section Start -->
      <div class="quality-service-section section">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow slideInLeft">
                     <h2 class="sisf-m-title sisf-m-title--scroll">Quality Services &<br> Activities Near you</h2>
                     <div class="sisf-m-text">
                        <p>Our Comfort Is Our Priority" expresses a commitment to providing the highest level of comfort and satisfaction for our customers. Whether you're staying with us, using our services, or purchasing our products, we prioritize your needs and ensure a relaxing and enjoyable experience.</p>
                        <p>From cozy accommodations and personalized services to high-quality products designed with your comfort in mind, everything we do is centered around making you feel at ease and valued. Your comfort isn't just our goal—it's our top priority.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
               <div class="col-md-3">
                  <div class="sisf-about-us-content wow zoomIn">
                     <div class="sisf-e-text text-center">
                        <h3 class="sisf-service-title mb-2">
                           <a class="sisf-e-title-link" href="{{ route('about') }}">About Us</a>
                        </h3>
                        <p class="sisf-service-description mb-2">It refers to an establishment that provides lodging, typically on a short-term basis.</p>
                        <div class="sisf-m-button sisf-sis-clear">
                           <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('about') }}">
                           <span class="sisf-m-text">Explore More</span>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="sisf-image-box wow zoomIn">
                     <figure>
                        <img src="{{ asset('images/about_us_img-1.png') }}" class="w-100" alt="Luxestay">
                     </figure>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="sisf-image-box pt-5 wow zoomIn">
                     <figure>
                        <img src="{{ asset('images/about_us_img-2.png') }}" class="w-100" alt="Luxestay">
                     </figure>
                  </div>
                  <div class="sisf-luxury-room-content wow zoomIn">
                     <div class="sisf-e-text text-center">
                        <h3 class="sisf-service-title mb-2">
                           <a class="sisf-e-title-link" href="{{ route('rooms.index') }}">Luxury Rooms</a>
                        </h3>
                        <p class="sisf-service-description mb-2">It refers to an establishment that provides lodging, typically on a short-term basis.</p>
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
      </div>
      <!-- Quality Services Section End -->
      <!-- Quality Services Section Start -->
      <div class="quality-service-section position-relative">
         <div class="quality-service-background comman--background">
            <div class="container">
               <div class="row">
                  <div class="col-12">
                     <!-- Section Title Start -->
                     <div class="sisf-sis-section-title text-center section-title mb-0 wow wow-bounce">
                        <h2 class="sisf-m-title text-white mb-0 sisf-m-title--scroll">Quality Services<br> & Activities Near you</h2>
                     </div>
                     <!-- Section Title End -->
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Quality Services Section End -->
      <!-- Your Comfert Section Start -->
      <div class="your-comfert-section">
         <div class="container-fluid p-0">
            <div class="row align-items-center">
               <div class="col-md-2">
                  <div class="sisf-sis--image wow bounceInLeft">
                     <figure>
                        <img src="{{ asset('images/your_comfort_img-1.png') }}" class="w-100" alt="Luxestay">
                     </figure>
                  </div>
               </div>
               <div class="col-md-4">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll">YOUR COMFORT <br>IS OUR HIGH PRIORITY </h2>
                  </div>
                  <!-- Section Title End -->
                  <div class="row">
                     <div class="col-md-6 p-0">
                        <!-- Counter Item Start -->
                        <div class="counter-item text-center sis--counter ms-3 wow rotateInDownLeft">
                           <!-- Counter Title Start -->
                           <div class="counter-title">
                              <h2 class="d-flex align-items-center justify-content-center">
                                 <span class="counter">45</span>
                                 <span class="sisf-digit-label">+</span>
                              </h2>
                           </div>
                           <!-- Counter Title End -->
                           <!-- Counter Content Start -->
                           <div class="counter-content">
                              <span class="text-uppercase">Rooms</span>
                           </div>
                           <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                     </div>
                     <div class="col-md-6 ps-0">
                        <!-- Counter Item Start -->
                        <div class="counter-item text-center sis--counter sis-e-counter wow rotateInDownLeft">
                           <!-- Counter Title Start -->
                           <div class="counter-title">
                              <h2 class="d-flex align-items-center justify-content-center">
                                 <span class="counter">12</span>
                                 <span class="sisf-digit-label">K</span>
                              </h2>
                           </div>
                           <!-- Counter Title End -->
                           <!-- Counter Content Start -->
                           <div class="counter-content">
                              <span class="text-uppercase">REVIEWS</span>
                           </div>
                           <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                     </div>
                     <div class="col-md-6 p-0">
                        <!-- Counter Item Start -->
                        <div class="counter-item text-center sis--counter sis-e-counter ms-3 wow rotateInDownRight">
                           <!-- Counter Title Start -->
                           <div class="counter-title">
                              <h2 class="d-flex align-items-center justify-content-center">
                                 <span class="counter">250</span>
                                 <span class="sisf-digit-label">+</span>
                              </h2>
                           </div>
                           <!-- Counter Title End -->
                           <!-- Counter Content Start -->
                           <div class="counter-content">
                              <span class="text-uppercase">STAFFS</span>
                           </div>
                           <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                     </div>
                     <div class="col-md-6 ps-0">
                        <!-- Counter Item Start -->
                        <div class="counter-item text-center sis--counter wow rotateInDownRight">
                           <!-- Counter Title Start -->
                           <div class="counter-title">
                              <h2 class="d-flex align-items-center justify-content-center">
                                 <span class="counter">15</span>
                                 <span class="sisf-digit-label">+</span>
                              </h2>
                           </div>
                           <!-- Counter Title End -->
                           <!-- Counter Content Start -->
                           <div class="counter-content">
                              <span class="text-uppercase">YEARS JOURNEY</span>
                           </div>
                           <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="sisf-sis--image wow bounceInRight">
                     <figure>
                        <img src="{{ asset('images/your_comfort_img-2.png') }}" class="w-100" alt="Luxestay">
                     </figure>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Your Comfert Section End -->
      <!-- Our Trusted Partners Section Start -->
      <div class="our-trusted-partners section">
         <div class="container-fluid">
            <div class="row">
               <div class="col-12">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title text-center section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll">OUR TRUSTED <br>PARTNERS </h2>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="comman--swiper-slider wow fadeInUp">
                     <div class="swiper">
                        <div class="swiper-wrapper">
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo1.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo2.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo3.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo4.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo5.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo1.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo2.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo3.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo4.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-image-holder">
                                    <div class="sisf-e-media">
                                       <figure>
                                          <img src="{{ asset('images/client_logo5.png') }}" class="w-100" alt="Luxestay">
                                       </figure>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Our Trusted Partners Section End -->
      <!-- Check In Check Out Section Start -->
      <div class="check-in-out-section section">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <div class="check-in-out-form form-section wow bounceInRight">
                     <form>
                        <div class="booking-form-col position-relative">
                           <label class="form-label" for="checkin">Nhận phòng</label>
                           <input type="text" id="checkin" class="form-control mb-0 ps-0" placeholder="Chọn ngày">
                           <i class="fa-regular fa-calendar"></i>
                        </div>
                        <div class="booking-form-col position-relative">
                           <label class="form-label" for="checkout">Trả phòng</label>
                           <input type="text" id="checkout" class="form-control mb-0 ps-0" placeholder="Chọn ngày">
                           <i class="fa-regular fa-calendar"></i>
                        </div>
                        <div class="select-wrapper booking-form-col position-relative">
                           <label class="form-label" for="rooms">Số phòng</label>
                           <select class="form-select form-control mb-0 ps-0" id="rooms">
                              <option value="1" selected>1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="18">18</option>
                              <option value="19">19</option>
                              <option value="20">20</option>
                           </select>
                           <i class="fa-solid fa-chevron-down custom-select-icon"></i>
                        </div>
                        <div class="select-wrapper booking-form-col position-relative custom-guests-dropdown">
                           <label class="form-label" for="guests">Khách</label>
                           <div class="dropdown">
                              <button class="form-select form-control mb-0 ps-0 dropdown-toggle" type="button"
                                 id="guestsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                              1 Người lớn
                              </button>
                              <ul class="dropdown-menu p-3" aria-labelledby="guestsDropdown">
                                 <li class="mb-2">
                                    <label class="form-label d-block">Người lớn</label>
                                    <select id="guests" class="form-select">
                                       <option value="0">0</option>
                                       <option value="1" selected>1</option>
                                       <option value="2">2</option>
                                       <option value="3">3</option>
                                       <option value="4+">4+</option>
                                    </select>
                                 </li>
                                 <li class="mb-2">
                                    <label class="form-label d-block">Trẻ em <small>(2–12 tuổi)</small></label>
                                    <select class="form-select">
                                       <option value="0" selected>0</option>
                                       <option value="1">1</option>
                                       <option value="2">2</option>
                                       <option value="3">3</option>
                                       <option value="4+">4+</option>
                                    </select>
                                 </li>
                                 <li>
                                    <label class="form-label d-block">Sơ sinh <small>(0–2 tuổi)</small></label>
                                    <select class="form-select">
                                       <option value="0" selected>0</option>
                                       <option value="1">1</option>
                                       <option value="2">2</option>
                                    </select>
                                 </li>
                              </ul>
                           </div>
                           <i class="fa-solid fa-chevron-down custom-select-icon"></i>
                        </div>
                        <div class="sisf-m-button text-center">
                           <button type="submit" class="check-btn">Kiểm tra phòng trống</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Check In Check Out Section End -->
      <!-- Blog Section Start -->
      <div class="letest-blog--news bg-texture-light section">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-5">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll mb-0">BLOG AND NEWS<br> INSIGHT</h2>
                  </div>
                  <!-- Section Title End -->
               </div>
               <div class="col-md-2">
                  <div class="event-image wow fadeInUp float">
                     <figure>
                        <img src="{{ asset('images/your_comfort_graphic.png') }}" alt="LuxeStay">
                     </figure>
                  </div>
               </div>
               <div class="col-md-5">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow bounceInRight">
                     <div class="sisf-m-text">
                        <p class="mt-0">In the dynamic landscape of hospitality, StayEase emerges as a trailblazer, setting new standards for comfort, convenience, and customer-centric service.</p>
                     </div>
                     <div class="sisf-m-button sisf-sis-clear pt-4">
                        <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('blog.index') }}">
                        <span class="sisf-m-text">VIEW MORE</span>
                        </a>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="letest-blog--item letest--blog--item wow bounceInLeft">
                     <div class="sisf-e-inner d-flex align-items-center">
                        <div class="sisf-e-media-holder">
                           <div class="sisf-e-media">
                              <div class="sisf-e--media-image">
                                 <a href="{{ route('blog.index') }}">
                                 <img src="{{ asset('images/home-blog-img1.png') }}" class="w-100" alt="Luxestay">
                                 </a>
                              </div>
                           </div>
                        </div>
                        <div class="sisf-e-content bg-white p-4">
                           <div class="sisf-e-info sisf-info--top">
                              <div class="sisf-e-info-item sisf-e-info-date entry-date published updated mb-2">
                                 <a href="{{ route('blog.index') }}">
                                 <span>19 Jul, 2024</span>
                                 </a>
                              </div>
                           </div>
                           <div class="sisf-e-text">
                              <div class="sisf-e-title-wraper">
                                 <h3 class="sisf-e-title entry-title blog-title mb-2">
                                    <a class="sisf-e-title-link blog--title-link" href="{{ route('blog.index') }}">
                                    Unique Stays You'll Love
                                    </a>
                                 </h3>
                                 <p class="sisf-e-excerpt mb-2">Hotel prices can vary significantly based on the time of yea</p>
                              </div>
                           </div>
                           <div class="sisf-m-button sisf-sis-clear">
                              <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('blog.index') }}">
                              <span class="sisf-m-text text-uppercase">Explore More</span>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="letest-blog--item letest--blog--item wow bounceInRight">
                     <div class="sisf-e-inner d-flex align-items-center">
                        <div class="sisf-e-media-holder">
                           <div class="sisf-e-media">
                              <div class="sisf-e--media-image media-image">
                                 <a href="{{ route('blog.index') }}">
                                 <img src="{{ asset('images/home-blog-img2.png') }}" class="w-100" alt="Luxestay">
                                 </a>
                              </div>
                           </div>
                        </div>
                        <div class="sisf-e-content bg-white p-4">
                           <div class="sisf-e-info sisf-info--top">
                              <div class="sisf-e-info-item sisf-e-info-date entry-date published updated mb-2">
                                 <a href="{{ route('blog.index') }}">
                                 <span>19 Jul, 2024</span>
                                 </a>
                              </div>
                           </div>
                           <div class="sisf-e-text">
                              <div class="sisf-e-title-wraper">
                                 <h3 class="sisf-e-title entry-title blog-title mb-2">
                                    <a class="sisf-e-title-link blog--title-link" href="{{ route('blog.index') }}">
                                    Dive into Luxury
                                    </a>
                                 </h3>
                                 <p class="sisf-e-excerpt mb-2">Hotel prices can vary significantly based on the time of yea</p>
                              </div>
                           </div>
                           <div class="sisf-m-button sisf-sis-clear">
                              <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('blog.index') }}">
                              <span class="sisf-m-text text-uppercase">Explore More</span>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Blog Section End -->
      <!-- Testimonial Section Start -->
      <div class="testimonial-section testimonial--section">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <div class="sisf-single-slider sisf-testimonial-slider wow bounceInLeft">
                     <div class="swiper">
                        <div class="swiper-wrapper">
                           <div class="swiper-slide testimonial-slide testimonial_slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-content w-100">
                                    <div class="sisf-e-quote mb-4">
                                       <span><i class="fa-solid fa-quote-left"></i></span>
                                    </div>
                                    <h3 class="sisf-e-text mb-4">Climbing natural rock formations with ropes and harnesses. Scaling frozen waterfalls or ice-covered rock faces using specialized equipment. Descending snow-covered slopes on skis or a snowboard.</h3>
                                    <div class="sisf-e-bottom-info justify-content-start">
                                       <div class="sisf-e-media-image me-4">
                                          <span>
                                          <img src="{{ asset('images/proile_pic.png') }}" alt="Luxestay">
                                          </span>
                                       </div>
                                       <div class="sisf-e-author pb-0 mb-0 border-0">
                                          <h4 class="sisf-e-author-name">John Wilson</h4>
                                          <span class="sisf-e-author-job text-black">CEO, Blogger</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide testimonial-slide testimonial_slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-content w-100">
                                    <div class="sisf-e-quote mb-4">
                                       <span><i class="fa-solid fa-quote-left"></i></span>
                                    </div>
                                    <h3 class="sisf-e-text mb-4">Climbing natural rock formations with ropes and harnesses. Scaling frozen waterfalls or ice-covered rock faces using specialized equipment. Descending snow-covered slopes on skis or a snowboard.</h3>
                                    <div class="sisf-e-bottom-info justify-content-start">
                                       <div class="sisf-e-media-image me-4">
                                          <span>
                                          <img src="{{ asset('images/proile_pic.png') }}" alt="Luxestay">
                                          </span>
                                       </div>
                                       <div class="sisf-e-author pb-0 mb-0 border-0">
                                          <h4 class="sisf-e-author-name">John Wilson</h4>
                                          <span class="sisf-e-author-job text-black">CEO, Blogger</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide testimonial-slide testimonial_slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-content w-100">
                                    <div class="sisf-e-quote mb-4">
                                       <span><i class="fa-solid fa-quote-left"></i></span>
                                    </div>
                                    <h3 class="sisf-e-text mb-4">Climbing natural rock formations with ropes and harnesses. Scaling frozen waterfalls or ice-covered rock faces using specialized equipment. Descending snow-covered slopes on skis or a snowboard.</h3>
                                    <div class="sisf-e-bottom-info justify-content-start">
                                       <div class="sisf-e-media-image me-4">
                                          <span>
                                          <img src="{{ asset('images/proile_pic.png') }}" alt="Luxestay">
                                          </span>
                                       </div>
                                       <div class="sisf-e-author pb-0 mb-0 border-0">
                                          <h4 class="sisf-e-author-name">John Wilson</h4>
                                          <span class="sisf-e-author-job text-black">CEO, Blogger</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Swiper Buttons -->
                        <div class="swiper-buttons swiper--buttons">
                           <span class="custom-icon custom-icon-left-1"><i class="fa-solid fa-arrow-left-long"></i></span>
                           <span class="custom-icon custom-icon-right-1"><i class="fa-solid fa-arrow-right-long"></i></span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Testimonial Section End -->
@endsection
