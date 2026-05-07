@extends('layouts.app')

@section('title', 'LuxeStay – Luxury Hotel & Resort Booking')

@section('content')
      <!-- Hero Section Start -->
      <div class="hero hero-slider">
         <div class="hero-slider-layout">
            <div class="swiper position-relative">
               <div class="swiper-wrapper">
                  <div class="swiper-slide position-relative">
                     <div class="hero-slide hero-video">
                        <div class="hero-bg-video">
                           <!-- Selfhosted Video Start -->
                           <video class="p-0" autoplay="" muted="" loop="" id="myVideo">
                              <source src="https://luxestay.wpthemeverse.com/wp-content/uploads/2024/08/video-hotel1-1.mp4" type="video/mp4">
                           </video>
                           <!-- Selfhosted Video End -->
                        </div>
                        <!-- Slider Content Start -->
                        <div class="container-fluid">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <!-- Hero Content Start -->
                                 <div class="hero-content">
                                    <!-- Hero Title Start -->
                                    <div class="section-title mb-3 wow fadeInUp">
                                       <h1 class="text-white text-center text-anime-style-2" data-cursor="-opaque">PREMIER MOUNTAIN RETREAT FOR<br> RELAXATION AND RECREATION</h1>
                                    </div>
                                    <!-- Hero Title End -->
                                 </div>
                                 <!-- Hero Content End -->
                              </div>
                           </div>
                        </div>
                        <!-- Slider Content End -->
                     </div>
                  </div>
                  <div class="swiper-slide">
                     <div class="hero-slide">
                        <div class="hero-slider-image">
                           <img src="{{ asset('images/home1-hero_img.png') }}" alt="LuxeStay">
                        </div>
                        <!-- Slider Content Start -->
                        <div class="container-fluid">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <!-- Hero Content Start -->
                                 <div class="hero-content">
                                    <!-- Hero Title Start -->
                                    <div class="section-title mb-3 wow fadeInUp">
                                       <h1 class="text-white text-center text-anime-style-2" data-cursor="-opaque">SOPHISTICATED ALPINE RESORT IN<br> SWITZERLAND'S HEARTLAND</h1>
                                    </div>
                                    <!-- Hero Title End -->
                                 </div>
                                 <!-- Hero Content End -->
                              </div>
                           </div>
                        </div>
                        <!-- Slider Content End -->
                     </div>
                  </div>
                  <div class="swiper-slide">
                     <div class="hero-slide">
                        <div class="hero-slider-image">
                           <img src="{{ asset('images/home1_hero_img2.png') }}" alt="LuxeStay">
                        </div>
                        <!-- Slider Content Start -->
                        <div class="container-fluid">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <!-- Hero Content Start -->
                                 <div class="hero-content">
                                    <!-- Hero Title Start -->
                                    <div class="section-title mb-3 wow fadeInUp">
                                       <h1 class="text-white text-center text-anime-style-2" data-cursor="-opaque">EXCLUSIVE HIGH-ALTITUDE<br> SANCTUARY AND ADVENTURE<br> RESORT</h1>
                                    </div>
                                    <!-- Hero Title End -->
                                 </div>
                                 <!-- Hero Content End -->
                              </div>
                           </div>
                        </div>
                        <!-- Slider Content End -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Hero Section End -->
      <!-- Offer Section Start -->
      <div class="our-offers-section">
         <div class="container">
            <div class="row">
               <div class="col-md-6 p-0">
                  <div class="sisf-box-with-content wow slideInLeft">
                     <div class="box-image me-4">
                        <figure>
                           <img src="{{ asset('images/best.png') }}" class="image-fluid" alt="LuxeStay">
                        </figure>
                     </div>
                     <div class="content-text">
                        <h4 class="heading-title">Best mountain retreat award 2024</h4>
                     </div>
                  </div>
               </div>
               <div class="col-md-6 p-0">
                  <div class="sisf-box-with-content wow slideInRight">
                     <div class="box-image me-4">
                        <figure>
                           <img src="{{ asset('images/offer.png') }}" class="image-fluid" alt="LuxeStay">
                        </figure>
                     </div>
                     <div class="content-text">
                        <h4 class="heading-title">Get 20% off on first booking</h4>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Offer Section End -->
      <!-- Your Comfort Section Start -->
      <div class="your-comfort-section">
         <div class="container-fluid">
            <div class="row align-items-center">
               <div class="col-lg-2 col-md-3 position-relative">
                  <div class="your-comfort-left-image wow zoomInLeft">
                     <figure>
                        <img src="{{ asset('images/your_comfort_img1.png') }}" class="image-fluid w-100" alt="LuxeStay">
                     </figure>
                  </div>
                  <div class="comfort-graphic-image float wow fadeInUp">
                     <figure>
                        <img src="{{ asset('images/your_comfort_graphic.png') }}" class="image-fluid w-100" alt="LuxeStay">
                     </figure>
                  </div>
               </div>
               <div class="col-lg-8 col-md-6">
                  <div class="your-comfort-decoration-center wow zoomInUp">
                     <!-- Section Title Start -->
                     <div class="sisf-sis-section-title section-title text-center mb-0">
                        <h2 class="sisf-m-title sisf-m-title--scroll">YOUR COMFORT IS OUR<br> PRIORITY </h2>
                        <div class="sisf-m-text">
                           <p>Our Comfort Is Our Priority" expresses a commitment to providing the highest level of comfort and satisfaction for our customers. Whether you're staying with us, using our services, or purchasing our products, we prioritize your needs and ensure a relaxing and enjoyable experience.</p>
                           <p>From cozy accommodations and personalized services to high-quality products designed with your comfort in mind, everything we do is centered around making you feel at ease and valued. Your comfort isn't just our goal—it's our top priority.</p>
                        </div>
                        <div class="sisf-m-button sisf-sis-clear pt-4">
                           <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('about') }}">
                           <span class="sisf-m-text">SEE MORE</span>
                           </a>
                        </div>
                     </div>
                     <!-- Section Title End -->
                  </div>
               </div>
               <div class="col-lg-2 col-md-3">
                  <div class="your-comfort-right-image wow zoomInRight">
                     <figure>
                        <img src="{{ asset('images/your_comfort_img2.png') }}" class="image-fluid w-100" alt="LuxeStay">
                     </figure>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Your Comfort Section End -->
      <!-- LuxeStay Offerings Section Start -->
      <div class="luxestay-offering-section comman--bg">
         <div class="container">
            <div class="row align-items-end">
               <div class="col-md-4">
                  <div class="service-box text-end">
                     <div class="image-box-wrapper">
                        <figure>
                           <img src="{{ asset('images/image-1.png') }}" alt="LuxeStay">
                        </figure>
                        <div class="image-box-content mt-3">
                           <h4 class="image-box-title">24h Front Desk</h4>
                           <p class="box-description mt-2">Eventum nobis nunc et leo urgeant eos etiam sint et vel stante at vel itaque iste modestia.</p>
                        </div>
                     </div>
                  </div>
                  <div class="service-box text-end">
                     <div class="image-box-wrapper">
                        <figure>
                           <img src="{{ asset('images/image-2.png') }}" alt="LuxeStay">
                        </figure>
                        <div class="image-box-content mt-3">
                           <h4 class="image-box-title">Restaurants</h4>
                           <p class="box-description mt-2">Eventum nobis nunc et leo urgeant eos etiam sint et vel stante at vel itaque iste modestia.</p>
                        </div>
                     </div>
                  </div>
                  <div class="service-box text-end">
                     <div class="image-box-wrapper">
                        <figure>
                           <img src="{{ asset('images/image-3.png') }}" alt="LuxeStay">
                        </figure>
                        <div class="image-box-content mt-3">
                           <h4 class="image-box-title">Superior Room</h4>
                           <p class="box-description mt-2">Eventum nobis nunc et leo urgeant eos etiam sint et vel stante at vel itaque iste modestia.</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="services-video">
                     <!-- Selfhosted Video Start -->
                     <video autoplay="" muted="" loop="">
                        <source src="https://luxestay.wpthemeverse.com/wp-content/uploads/2024/07/video2.mp4" type="video/mp4">
                     </video>
                     <!-- Selfhosted Video End -->
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="service-box">
                     <div class="image-box-wrapper">
                        <figure>
                           <img src="{{ asset('images/image-4.png') }}" alt="LuxeStay">
                        </figure>
                        <div class="image-box-content mt-3">
                           <h4 class="image-box-title">Airport Transfers</h4>
                           <p class="box-description mt-2">Eventum nobis nunc et leo urgeant eos etiam sint et vel stante at vel itaque iste modestia.</p>
                        </div>
                     </div>
                  </div>
                  <div class="service-box">
                     <div class="image-box-wrapper">
                        <figure>
                           <img src="{{ asset('images/image-5.png') }}" alt="LuxeStay">
                        </figure>
                        <div class="image-box-content mt-3">
                           <h4 class="image-box-title">Outdoor Activities</h4>
                           <p class="box-description mt-2">Eventum nobis nunc et leo urgeant eos etiam sint et vel stante at vel itaque iste modestia.</p>
                        </div>
                     </div>
                  </div>
                  <div class="service-box">
                     <div class="image-box-wrapper">
                        <figure>
                           <img src="{{ asset('images/image-6.png') }}" alt="LuxeStay">
                        </figure>
                        <div class="image-box-content mt-3">
                           <h4 class="image-box-title">Spa & Wellness</h4>
                           <p class="box-description mt-2">Eventum nobis nunc et leo urgeant eos etiam sint et vel stante at vel itaque iste modestia.</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- LuxeStay Offerings Section End -->
      <!-- Your Comfort Section Start -->
      <div class="your-comfort-section comman-bg sisf-extended-grid sisf-extended-grid--right section">
         <div class="container pe-0">
            <div class="row">
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll mb-0">YOUR COMFORT IS <br>OUR PRIORITY </h2>
                  </div>
                  <!-- Section Title End -->
               </div>
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow bounceInRight">
                     <div class="sisf-m-text">
                        <p class="mt-0">Our Comfort Is Our Priority" expresses a commitment to providing the highest level of comfort and satisfaction for our customers. Whether you're staying with us, using our services, or purchasing our products, we prioritize your needs and ensure a relaxing and enjoyable experience.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="sisf--sis-slider wow lightSpeedIn">
                     <div class="swiper">
                        <div class="swiper-wrapper">
                           @forelse($rooms ?? [] as $room)
                           <div class="sisf-e sisf-room-list-item swiper-slide">
                              <div class="sisf-e-inner position-relative">
                                 <div class="sisf-e-media">
                                    <div class="sisf-e-media-image">
                                       <a href="{{ route('rooms.show', $room->slug) }}">
                                       <img src="{{ asset($room->thumbnail) }}" class="w-100" alt="LuxeStay">
                                       </a>
                                    </div>
                                    <span class="sisf-e-price">
                                    <span class="sisf-e-price-label text-uppercase">From</span>
                                    <span class="sisf-e-price-value">${{ $room->price_per_night }}</span>
                                    </span>
                                 </div>
                                 <div class="sisf-e-content">
                                    <div class="sisf-e-content-text">
                                       <h3 class="sisf-e-title entry-title">
                                          <a href="{{ route('rooms.show', $room->slug) }}" class="text-white">{{ $room->name }}</a>
                                       </h3>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @empty
                           <p>No rooms available.</p>
                           @endforelse
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Your Comfort Section End -->
      <!-- Check In Check Out Section Start -->
      <div class="check-in-out-section section">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <div class="check-in-out-form form-section wow bounceInRight">
                     <form>
                        <div class="booking-form-col position-relative">
                           <label class="form-label" for="checkin">Check-in</label>
                           <input type="text" id="checkin" class="form-control mb-0 ps-0" placeholder="Select date">
                           <i class="fa-regular fa-calendar"></i>
                        </div>
                        <div class="booking-form-col position-relative">
                           <label class="form-label" for="checkout">Check-out</label>
                           <input type="text" id="checkout" class="form-control mb-0 ps-0" placeholder="Select date">
                           <i class="fa-regular fa-calendar"></i>
                        </div>
                        <div class="select-wrapper booking-form-col position-relative">
                           <label class="form-label" for="rooms">Rooms</label>
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
                              <label class="form-label" for="guests">Guests</label>
                              <div class="dropdown">
                                 <button class="form-select form-control mb-0 ps-0 dropdown-toggle" type="button"
                                       id="guestsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                 1 Adult
                                 </button>
                                 <ul class="dropdown-menu p-3" aria-labelledby="guestsDropdown">
                                 <li class="mb-2">
                                    <label class="form-label d-block">Adults</label>
                                    <select id="guests" class="form-select">
                                       <option value="0">0</option>
                                       <option value="1" selected>1</option>
                                       <option value="2">2</option>
                                       <option value="3">3</option>
                                       <option value="4+">4+</option>
                                    </select>
                                 </li>
                                 <li class="mb-2">
                                    <label class="form-label d-block">Children <small>(2–12 years old)</small></label>
                                    <select class="form-select">
                                       <option value="0" selected>0</option>
                                       <option value="1">1</option>
                                       <option value="2">2</option>
                                       <option value="3">3</option>
                                       <option value="4+">4+</option>
                                    </select>
                                 </li>
                                 <li>
                                    <label class="form-label d-block">Infants <small>(0–2 years old)</small></label>
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
                           <button type="submit" class="check-btn">Check Availability</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Check In Check Out Section End -->
      <!-- Extra Service Section Start -->
      <div class="extra-service-section sisf-extended-grid sisf-extended-grid--left">
         <div class="container ps-0">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <div class="extra-service-image wow zoomInUp">
                     <figure>
                        <img src="{{ asset('images/extra_service_image.png') }}" class="w-100" alt="LuxeStay">
                     </figure>
                  </div>
               </div>
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll">EXTRA SERVICE COST</h2>
                  </div>
                  <!-- Section Title End -->
                  <!-- Form Start -->
                  <div class="contact-here-form">
                     <form id="contactForm" action="#" method="POST" data-toggle="validator" data-wow-delay="0.5s">
                        @csrf
                        <div class="row">
                           <div class="form form-group wow bounceInRight">
                              <div class="help-block with-errors"></div>
                              <label class="sisf-m-field-label" for="name">NAME</label>
                              <input type="text" class="form-control" name="name" id="name" placeholder="Name" required="">
                           </div>
                           <div class="form form-group wow bounceInRight">
                              <div class="help-block with-errors"></div>
                              <label class="sisf-m-field-label" for="email">EMAIL</label>
                              <input type="email" class="form-control" name="email" id="email" placeholder="Email" required="">
                           </div>
                           <div class="form form-group wow bounceInRight">
                              <div class="help-block with-errors"></div>
                              <label class="sisf-m-field-label" for="msg">YOUR MESSAGE</label>
                              <textarea class="form-control" rows="3" name="msg" id="msg" placeholder="Your message" required=""></textarea>
                           </div>
                           <div class="sisf-m-button wow bounceInRight">
                              <button type="submit" class="btn-default w-100 text-center">
                              <span class="sisf-m-text">CHECK AVAILABILITY</span>
                              </button>
                              <div id="msgSubmit" class="hidden"></div>
                           </div>
                        </div>
                     </form>
                  </div>
                  <!-- Form End -->
               </div>
            </div>
         </div>
      </div>
      <!-- Extra Service Section End -->
      <!-- Your Comfort Section Start -->
      <div class="your-comfort-section section">
         <div class="container pe-0">
            <div class="row">
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll mb-0">YOUR COMFORT IS <br>OUR PRIORITY </h2>
                  </div>
                  <!-- Section Title End -->
               </div>
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow bounceInRight">
                     <div class="sisf-m-text">
                        <p class="mt-0">Our Comfort Is Our Priority" expresses a commitment to providing the highest level of comfort and satisfaction for our customers. Whether you're staying with us, using our services, or purchasing our products, we prioritize your needs and ensure a relaxing and enjoyable experience.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="sisf-shortcode sisf-m sisf-border-box sisf-sis-info-cards wow bounceInLeft">
                     <div class="sisf-e-main-image">
                        <figure>
                           <img src="{{ asset('images/expericence_img1.png') }}" class="w-100" alt="LuxeStay">
                        </figure>
                     </div>
                     <div class="sisf-m-content">
                        <h5 class="sisf-m-subtitle">
                           JULY 27, 2024
                        </h5>
                        <h3 class="sisf-m-title my-2">
                           <a href="{{ route('activities.show', 'restaurant') }}">Restaurants</a>
                        </h3>
                        <p class="sisf-m-text mb-0">In the dynamic landscape of hospitality, StayEase emerge</p>
                        <div class="sisf-m-button sisf-sis-clear pt-3">
                           <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('activities.show', 'restaurant') }}">
                           <span class="sisf-m-text">Explore More</span>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="sisf-shortcode sisf-m sisf-border-box sisf-sis-info-cards second wow bounceInUp border-0">
                     <div class="sisf-m-content text-center">
                        <h5 class="sisf-m-subtitle">
                           JULY 27, 2024
                        </h5>
                        <h3 class="sisf-m-title my-2">
                           <a href="{{ route('offers') }}">The Best Hotels For Family Vacations</a>
                        </h3>
                        <div class="sisf-m-button sisf-sis-clear pt-2">
                           <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('offers') }}">
                           <span class="sisf-m-text">Explore More</span>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="sisf-sis-info-cards sisf--info-cards position-relative wow zoomInUp border-0">
                     <div class="sisf-e-main-image">
                        <figure>
                           <img src="{{ asset('images/spa_wellness.png') }}" class="w-100" alt="LuxeStay">
                        </figure>
                     </div>
                     <div class="sisf-m-content">
                        <h5 class="sisf-m-subtitle text-white">
                           JULY 27, 2024
                        </h5>
                        <h3 class="sisf-m-title my-2">
                           <a href="{{ route('activities.show', 'spa-wellness') }}" class="text-white">
                           Spa & Wellness
                           </a>
                        </h3>
                        <div class="sisf-m-button sisf-sis-clear">
                           <a class="sisf-shortcode text-white sisf-text-underline sisf-underline--left" href="{{ route('activities.show', 'spa-wellness') }}">
                           <span class="sisf-m-text">Explore More</span>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="sisf-sis-info-cards sisf--info-cards position-relative wow zoomInUp border-0 mb-0">
                     <div class="sisf-e-main-image">
                        <figure>
                           <img src="{{ asset('images/night_time_adventure_img.png') }}" class="w-100" alt="LuxeStay">
                        </figure>
                     </div>
                     <div class="sisf-m-content">
                        <h5 class="sisf-m-subtitle text-white">
                           JULY 27, 2024
                        </h5>
                        <h3 class="sisf-m-title my-2">
                           <a href="#" class="text-white">
                           Nighttime Adventures in the Highlands
                           </a>
                        </h3>
                        <div class="sisf-m-button sisf-sis-clear">
                           <a class="sisf-shortcode text-white sisf-text-underline sisf-underline--left" href="#">
                           <span class="sisf-m-text">Explore More</span>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="sisf-shortcode sisf-m sisf-border-box sisf-sis-info-cards second-inner wow bounceIn border-0">
                     <div class="sisf-m-content">
                        <h5 class="sisf-m-subtitle">
                           JULY 27, 2024
                        </h5>
                        <h3 class="sisf-m-title my-2">
                           <a href="{{ route('about') }}">About Us</a>
                        </h3>
                        <p class="sisf-m-text mb-0">In the dynamic landscape of hospitality, StayEase emerge</p>
                        <div class="sisf-m-button sisf-sis-clear pt-2">
                           <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('about') }}">
                           <span class="sisf-m-text">Explore More</span>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="sisf-shortcode sisf-m sisf-border-box sisf-sis-info-cards wow bounceInRight">
                     <div class="sisf-e-main-image">
                        <figure>
                           <img src="{{ asset('images/lets_relax_img.png') }}" class="w-100" alt="LuxeStay">
                        </figure>
                     </div>
                     <div class="sisf-m-content">
                        <h5 class="sisf-m-subtitle">
                           JULY 27, 2024
                        </h5>
                        <h3 class="sisf-m-title my-2">
                           <a href="{{ route('activities.show', 'spa-wellness') }}">Let's Relax with StayEase</a>
                        </h3>
                        <p class="sisf-m-text mb-0">In the dynamic landscape of hospitality, StayEase emerge</p>
                        <div class="sisf-m-button sisf-sis-clear pt-3">
                           <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('activities.show', 'spa-wellness') }}">
                           <span class="sisf-m-text">Explore More</span>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Your Comfort Section End -->
      <!-- The Journey Section Start -->
      <div class="the-journey-section section">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-5">
                  <div class="sisf-journey-image wow slideInLeft">
                     <figure>
                        <img src="{{ asset('images/the_journey_img.png') }}" class="w-100" alt="LuxeStay">
                     </figure>
                  </div>
               </div>
               <div class="col-md-7">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title text-white sisf-m-title--scroll">The Journey of Altitude<br> in Mountains </h2>
                     <div class="sisf-m-text">
                        <p class="text-white">In the dynamic landscape of hospitality, StayEase emerges as a trailblazer, setting new standards for comfort, convenience, and customer-centric service. In the dynamic landscape of hospitality, StayEase emerges as a trailblazer, setting new standards for comfort, convenience, and customer-centric service.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
                  <div class="row">
                     <div class="col-md-4">
                        <!-- Counter Item Start -->
                        <div class="counter-item text-center step-counter">
                           <!-- Counter Title Start -->
                           <div class="counter-title">
                              <h2 class="d-flex align-items-center justify-content-center">
                                 <span class="counter text-white">1400</span>
                                 <span class="sisf-digit-label text-white">M</span>
                              </h2>
                           </div>
                           <!-- Counter Title End -->
                           <!-- Counter Content Start -->
                           <div class="counter-content">
                              <span class="text-white">HIGHEST ALTITUDE</span>
                           </div>
                           <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                     </div>
                     <div class="col-md-4">
                        <!-- Counter Item Start -->
                        <div class="counter-item text-center step-counter">
                           <!-- Counter Title Start -->
                           <div class="counter-title">
                              <h2 class="d-flex align-items-center justify-content-center">
                                 <span class="counter text-white">2610</span>
                                 <span class="sisf-digit-label text-white">M</span>
                              </h2>
                           </div>
                           <!-- Counter Title End -->
                           <!-- Counter Content Start -->
                           <div class="counter-content">
                              <span class="text-white">HIGHEST ALTITUDE</span>
                           </div>
                           <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                     </div>
                     <div class="col-md-4">
                        <!-- Counter Item Start -->
                        <div class="counter-item text-center step-counter">
                           <!-- Counter Title Start -->
                           <div class="counter-title">
                              <h2 class="d-flex align-items-center justify-content-center">
                                 <span class="counter text-white">60</span>
                                 <span class="sisf-digit-label text-white">KM</span>
                              </h2>
                           </div>
                           <!-- Counter Title End -->
                           <!-- Counter Content Start -->
                           <div class="counter-content">
                              <span class="text-white">SLOPES</span>
                           </div>
                           <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- The Journey Section End -->
      <!-- Event Section Start -->
      <div class="event-section section">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-5">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll mb-0">LOCAL ACTIVITIES<br> OF STAYEASE</h2>
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
                        <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('contact') }}">
                        <span class="sisf-m-text">VIEW MORE</span>
                        </a>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
         </div>
         <div class="container-fluid">
            <div class="row">
               <div class="col-12">
                  <div class="sisf-sis-slider sisf-activities-slider">
                     <div class="swiper">
                        <div class="swiper-wrapper">
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-images-holder">
                                    <div class="sisf-e-main-image">
                                       <figure>
                                          <img src="{{ asset('images/event-slide1.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                 </div>
                                 <div class="sisf-e-content-holder">
                                    <div class="sisf-activities-category sisf-item-content">
                                       <h6 class="sisf-e-category">
                                          OUTDOORS
                                       </h6>
                                    </div>
                                    <div class="sisf-activities-title sisf-item-content">
                                       <h3 class="sisf-e-title">
                                          Winter Hiking
                                       </h3>
                                    </div>
                                    <div class="sisf-activities-description sisf-item-content">
                                       <p class="sisf-e-description mb-3">
                                          In the dynamic landscape of hospitality, StayEase emerge
                                       </p>
                                    </div>
                                    <div class="sisf-activities-price sisf-item-content">
                                       <h5 class="sisf-e-price">
                                          $190 / Per Person
                                       </h5>
                                    </div>
                                    <div class="sisf-m-button sisf-sis-clear pt-3">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('contact') }}">
                                       <span class="sisf-m-text">Book Now</span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-images-holder">
                                    <div class="sisf-e-main-image">
                                       <figure>
                                          <img src="{{ asset('images/event-slide2.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                 </div>
                                 <div class="sisf-e-content-holder">
                                    <div class="sisf-activities-category sisf-item-content">
                                       <h6 class="sisf-e-category">
                                          OUTDOORS
                                       </h6>
                                    </div>
                                    <div class="sisf-activities-title sisf-item-content">
                                       <h3 class="sisf-e-title">
                                          Ski and Snowboarding
                                       </h3>
                                    </div>
                                    <div class="sisf-activities-description sisf-item-content">
                                       <p class="sisf-e-description mb-3">
                                          In the dynamic landscape of hospitality, StayEase emerge
                                       </p>
                                    </div>
                                    <div class="sisf-activities-price sisf-item-content">
                                       <h5 class="sisf-e-price">
                                          $190 / Per Person
                                       </h5>
                                    </div>
                                    <div class="sisf-m-button sisf-sis-clear pt-3">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('contact') }}">
                                       <span class="sisf-m-text">Book Now</span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-images-holder">
                                    <div class="sisf-e-main-image">
                                       <figure>
                                          <img src="{{ asset('images/event-slide3.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                 </div>
                                 <div class="sisf-e-content-holder">
                                    <div class="sisf-activities-category sisf-item-content">
                                       <h6 class="sisf-e-category">
                                          OUTDOORS
                                       </h6>
                                    </div>
                                    <div class="sisf-activities-title sisf-item-content">
                                       <h3 class="sisf-e-title">
                                          Golf Courses
                                       </h3>
                                    </div>
                                    <div class="sisf-activities-description sisf-item-content">
                                       <p class="sisf-e-description mb-3">
                                          In the dynamic landscape of hospitality, StayEase emerge
                                       </p>
                                    </div>
                                    <div class="sisf-activities-price sisf-item-content">
                                       <h5 class="sisf-e-price">
                                          $150 / Per Person
                                       </h5>
                                    </div>
                                    <div class="sisf-m-button sisf-sis-clear pt-3">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('contact') }}">
                                       <span class="sisf-m-text">Book Now</span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-images-holder">
                                    <div class="sisf-e-main-image">
                                       <figure>
                                          <img src="{{ asset('images/event-slide1.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                 </div>
                                 <div class="sisf-e-content-holder">
                                    <div class="sisf-activities-category sisf-item-content">
                                       <h6 class="sisf-e-category">
                                          OUTDOORS
                                       </h6>
                                    </div>
                                    <div class="sisf-activities-title sisf-item-content">
                                       <h3 class="sisf-e-title">
                                          Winter Hiking
                                       </h3>
                                    </div>
                                    <div class="sisf-activities-description sisf-item-content">
                                       <p class="sisf-e-description mb-3">
                                          In the dynamic landscape of hospitality, StayEase emerge
                                       </p>
                                    </div>
                                    <div class="sisf-activities-price sisf-item-content">
                                       <h5 class="sisf-e-price">
                                          $190 / Per Person
                                       </h5>
                                    </div>
                                    <div class="sisf-m-button sisf-sis-clear pt-3">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('contact') }}">
                                       <span class="sisf-m-text">Book Now</span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-images-holder">
                                    <div class="sisf-e-main-image">
                                       <figure>
                                          <img src="{{ asset('images/event-slide2.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                 </div>
                                 <div class="sisf-e-content-holder">
                                    <div class="sisf-activities-category sisf-item-content">
                                       <h6 class="sisf-e-category">
                                          OUTDOORS
                                       </h6>
                                    </div>
                                    <div class="sisf-activities-title sisf-item-content">
                                       <h3 class="sisf-e-title">
                                          Ski and Snowboarding
                                       </h3>
                                    </div>
                                    <div class="sisf-activities-description sisf-item-content">
                                       <p class="sisf-e-description mb-3">
                                          In the dynamic landscape of hospitality, StayEase emerge
                                       </p>
                                    </div>
                                    <div class="sisf-activities-price sisf-item-content">
                                       <h5 class="sisf-e-price">
                                          $190 / Per Person
                                       </h5>
                                    </div>
                                    <div class="sisf-m-button sisf-sis-clear pt-3">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('contact') }}">
                                       <span class="sisf-m-text">Book Now</span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-images-holder">
                                    <div class="sisf-e-main-image">
                                       <figure>
                                          <img src="{{ asset('images/event-slide3.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                 </div>
                                 <div class="sisf-e-content-holder">
                                    <div class="sisf-activities-category sisf-item-content">
                                       <h6 class="sisf-e-category">
                                          OUTDOORS
                                       </h6>
                                    </div>
                                    <div class="sisf-activities-title sisf-item-content">
                                       <h3 class="sisf-e-title">
                                          Golf Courses
                                       </h3>
                                    </div>
                                    <div class="sisf-activities-description sisf-item-content">
                                       <p class="sisf-e-description mb-3">
                                          In the dynamic landscape of hospitality, StayEase emerge
                                       </p>
                                    </div>
                                    <div class="sisf-activities-price sisf-item-content">
                                       <h5 class="sisf-e-price">
                                          $150 / Per Person
                                       </h5>
                                    </div>
                                    <div class="sisf-m-button sisf-sis-clear pt-3">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('contact') }}">
                                       <span class="sisf-m-text">Book Now</span>
                                       </a>
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
      <!-- Event Section End -->
      <!-- Testimonial Section Start -->
      <div class="testimonial-section comman--bg">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <div class="sisf-single-slider sisf-testimonial-slider wow bounceInLeft">
                     <div class="swiper position-relative">
                        <div class="swiper-wrapper">
                           <div class="swiper-slide testimonial-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-quote">
                                    <span><i class="fa-solid fa-quote-left"></i></span>
                                 </div>
                                 <div class="sisf-e-bottom-info">
                                    <div class="sisf-e-media-image">
                                       <figure>
                                          <img src="{{ asset('images/testimonial_img.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                    <div class="sisf-e-content">
                                       <h3 class="sisf-e-text text-white">Climbing natural rock formations with ropes and harnesses. Scaling frozen waterfalls or ice-covered rock faces using specialized equipment. Descending snow-covered slopes on skis or a snowboard.</h3>
                                       <div class="sisf-e-author d-flex align-items-center">
                                          <h4 class="sisf-e-author-name me-3 position-relative text-white">John Wilson</h4>
                                          <span class="sisf-e-author-job ms-3">CEO, Blogger</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide testimonial-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-quote">
                                    <span><i class="fa-solid fa-quote-left"></i></span>
                                 </div>
                                 <div class="sisf-e-bottom-info">
                                    <div class="sisf-e-media-image">
                                       <figure>
                                          <img src="{{ asset('images/testimonial_img1.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                    <div class="sisf-e-content">
                                       <h3 class="sisf-e-text text-white">Climbing natural rock formations with ropes and harnesses. Scaling frozen waterfalls or ice-covered rock faces using specialized equipment. Descending snow-covered slopes on skis or a snowboard.</h3>
                                       <div class="sisf-e-author d-flex align-items-center">
                                          <h4 class="sisf-e-author-name me-3 position-relative text-white">Jonis Lopez</h4>
                                          <span class="sisf-e-author-job ms-3">CEO, Blogger</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="swiper-slide testimonial-slide">
                              <div class="sisf-e-inner">
                                 <div class="sisf-e-quote">
                                    <span><i class="fa-solid fa-quote-left"></i></span>
                                 </div>
                                 <div class="sisf-e-bottom-info">
                                    <div class="sisf-e-media-image">
                                       <figure>
                                          <img src="{{ asset('images/testimonial_img2.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </div>
                                    <div class="sisf-e-content">
                                       <h3 class="sisf-e-text text-white">Climbing natural rock formations with ropes and harnesses. Scaling frozen waterfalls or ice-covered rock faces using specialized equipment. Descending snow-covered slopes on skis or a snowboard.</h3>
                                       <div class="sisf-e-author d-flex align-items-center">
                                          <h4 class="sisf-e-author-name me-3 position-relative text-white">Robert Garcia</h4>
                                          <span class="sisf-e-author-job ms-3">CEO, Blogger</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Swiper Buttons -->
                        <div class="swiper-buttons wow fadeInUp">
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
