@extends('layouts.app')
@section('title', $activity->title . ' – LuxeStay')
@section('content')
      {{-- spa-wellness.html body content with dynamic replacements applied --}}
      <!-- Banner Section Start -->
      <div class="sisf-banner position-relative">
         <div class="banner-img">
            <figure>
               <img src="{{ $activity->hero_image ? asset('storage/' . $activity->hero_image) : asset('images/title-banner.png') }}" alt="Luxestay">
            </figure>
         </div>
         <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
            <div class="sisf-m-inner">
               <div class="sisf-m-content sisf-content-grid ">
                  <h1 class="sisf-m-title text-center entry-title">{{ $activity->title }}</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Main Content Section Start -->
      <div class="serenity-wellness-center-section activities-page-bg section">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-lg-5 col-md-6">
                  <div class="activities-page-left wow zoomInUp">
                     <div class="sisf-e-media">
                        <figure>
                           <img src="{{ asset('images/your-trusted.png') }}" class="w-100" alt="LuxeStay">
                        </figure>
                     </div>
                  </div>
               </div>
               <div class="col-lg-7 col-md-6">
                  <div class="activities-page-right wow bounceInRight">
                     {!! $activity->content !!}
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Main Content Section End -->
      <!-- Massage & Wellness Section Start -->
      <div class="massage-and-wellness-section bg-effect">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title text-center section-title mb-0 wow wow-bounce">
                     <h2 class="sisf-m-title text-white mb-0 sisf-m-title--scroll">Ultimate Relaxation<br> Massage & Wellness</h2>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
         </div>
      </div>
      <!-- Massage & Wellness Section End -->
      <!-- Therapies And Massage Section Start -->
      <div class="therapies-and-massage-section section">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-5">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll mb-0">Therapies And<br> Massage</h2>
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
                                          <img src="{{ asset('images/spa-wellness-img1.png') }}" class="w-100" alt="LuxeStay">
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
                                         Facial Treatments
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
                                          <img src="{{ asset('images/spa-wellness-img2.png') }}" class="w-100" alt="LuxeStay">
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
                                          Yoga and Meditation
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
                                          <img src="{{ asset('images/spa-wellness-img3.png') }}" class="w-100" alt="LuxeStay">
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
                                          Massage Therapy
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
                                          <img src="{{ asset('images/spa-wellness-img1.png') }}" class="w-100" alt="LuxeStay">
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
                                         Facial Treatments
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
                                          <img src="{{ asset('images/spa-wellness-img2.png') }}" class="w-100" alt="LuxeStay">
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
                                          Yoga and Meditation
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
                                          <img src="{{ asset('images/spa-wellness-img3.png') }}" class="w-100" alt="LuxeStay">
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
                                          Massage Therapy
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
      <!-- Therapies And Massage Section End -->
      <!-- Book Now Section Start -->
      <div class="spa-and-wellness-bg book-now-section section">
         <div class="container">
            <div class="row">
               <div class="col-md-7"></div>
               <div class="col-md-5">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title mb-0 wow wow-bounce">
                     <h2 class="sisf-m-title sisf-m-title--scroll">Book now and<br> get 30% off</h2>
                     <div class="sisf-m-text">
                        <p>Our Comfort Is Our Priority" expresses a commitment to providing the highest level of comfort and satisfaction for our customers. Whether you're staying with us, using our services, or purchasing our products.</p>
                     </div>
                     <div class="sisf-m-button pt-4">
                        <a href="{{ route('contact') }}" class="btn-default">BOOK NOW</a>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
         </div>
      </div>
      <!-- Book Now Section End -->
@endsection
