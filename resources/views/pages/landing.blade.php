@extends('layouts.app')
@section('title', 'LuxeStay – Luxury Hotel & Resort')
@section('content')
      {{-- landing.html body content between header and footer --}}
      <!-- Landing Page Banner Start -->
      <div class="landing-banner comman--bg">
         <div class="row">
            <div class="col-12">
               <!-- Section Title Start -->
               <div class="sisf-sis-section-title text-center section-title wow zoomIn">
                  <h2 class="sisf-m-title text-white sisf-m-title--scroll">Đặt phòng khách sạn cao cấp<br> HTML Template</h2>
               </div>
               <!-- Section Title End -->
            </div>
         </div>
         <!-- Gallery Slider Start -->
         <div class="row">
            <div class="col-12">
               <div class="sisf-sis-slider">
                  <div class="swiper">
                     <div class="swiper-wrapper">
                        <div class="swiper-slide">
                           <div class="wow zoomInUp">
                              <a href="{{ route('home') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/landing_hero_img1.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="wow zoomInUp">
                              <a href="{{ route('home') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/landing_hero_img2.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="wow zoomInUp">
                              <a href="{{ route('home') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/landing_hero_img3.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="wow zoomInUp">
                              <a href="{{ route('home') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/landing_hero_img1.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="wow zoomInUp">
                              <a href="{{ route('home') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/landing_hero_img2.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="wow zoomInUp">
                              <a href="{{ route('home') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/landing_hero_img3.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Landing Page Banner End -->
      <!-- Home Pages Section Start -->
      <div class="stu-homepages section pb-0">
         <div class="container-fluid">
            <div class="row">
               <div class="col-12">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title text-center section-title wow wow-bounce">
                     <h1 class="home-page-count sisf-e-colored">3</h1>
                     <h2 class="sisf-m-title text-white sisf-m-title--scroll">Trang chủ ấn tượng</h2>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
            <div class="stu-homepages-row">
               <div class="row">
                  <div class="col-lg-4 col-md-6">
                     <div class="sisf-images--box wow bounceInLeft">
                        <a href="{{ route('home') }}">
                           <figure>
                              <img src="{{ asset('images/home_image-1.png') }}" class="w-100 animate-hover" alt="LuxeStay">
                           </figure>
                        </a>
                        <a href="{{ route('home') }}">
                           <h5 class="mt-4">Trang chủ Núi</h5>
                        </a>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="sisf-images--box wow bounceInUp">
                        <a href="{{ route('home') }}">
                           <figure>
                              <img src="{{ asset('images/home_image-2.png') }}" class="w-100 animate-hover" alt="LuxeStay">
                           </figure>
                        </a>
                        <a href="{{ route('home') }}">
                           <h5 class="mt-4">Trang chủ Biển</h5>
                        </a>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="sisf-images--box wow bounceInRight">
                        <a href="{{ route('home') }}">
                           <figure>
                              <img src="{{ asset('images/home_image-3.png') }}" class="w-100 animate-hover" alt="LuxeStay">
                           </figure>
                        </a>
                        <a href="{{ route('home') }}">
                           <h5 class="mt-4">Trang chủ Sang trọng</h5>
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Home Pages Section End -->
      <!-- Inner-Pages Section Start -->
      <div class="inner-pages section">
         <div class="container-fluid">
            <div class="row">
               <div class="col-12">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title text-center wow wow-bounce">
                     <h2 class="sisf-m-title text-white sisf-m-title--scroll">Trang nội bộ thiết kế sẵn</h2>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
            <div class="row">
               <div class="sisf-sis-slider">
                  <div class="swiper">
                     <div class="swiper-wrapper">
                        <div class="swiper-slide">
                           <div class="slider-image wow fadeInUp">
                              <a href="#">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/inner_page1.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="slider-image wow fadeInUp">
                              <a href="{{ route('about') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/inner_page2.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="slider-image wow fadeInUp">
                              <a href="{{ route('offers') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/inner_page3.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="slider-image wow fadeInUp">
                              <a href="{{ route('rooms.suites') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/inner_page4.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="slider-image wow fadeInUp">
                              <a href="{{ route('rooms.index') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/inner_page5.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="slider-image wow fadeInUp">
                              <a href="{{ route('shop.index') }}">
                                 <figure class="image-anime">
                                    <img src="{{ asset('images/inner_page6.png') }}" class="w-100" alt="LuxeStay">
                                 </figure>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="container">
            <div class="row">
               <div class="col-md-4">
                  <div class="sisf-landing-icon-with-text mb-3 wow bounceInLeft">
                     <span class="me-3 check"><i class="fa-solid fa-check"></i></span>
                     <span class="text-white">Bố cục đẹp</span>
                  </div>
                  <div class="sisf-landing-icon-with-text wow bounceInLeft">
                     <span class="me-3 check"><i class="fa-solid fa-check"></i></span>
                     <span class="text-white">Lịch kiểm tra phòng trống</span>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="sisf-landing-icon-with-text mb-3 wow zoomInUp">
                     <span class="me-3 check"><i class="fa-solid fa-check"></i></span>
                     <span class="text-white">Tùy chỉnh linh hoạt</span>
                  </div>
                  <div class="sisf-landing-icon-with-text wow zoomInUp">
                     <span class="me-3 check"><i class="fa-solid fa-check"></i></span>
                     <span class="text-white">Hoàn toàn tương thích</span>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="sisf-landing-icon-with-text mb-3 wow bounceInRight">
                     <span class="me-3 check"><i class="fa-solid fa-check"></i></span>
                     <span class="text-white">Hỗ trợ chuyên nghiệp</span>
                  </div>
                  <div class="sisf-landing-icon-with-text wow bounceInRight">
                     <span class="me-3 check"><i class="fa-solid fa-check"></i></span>
                     <span class="text-white">Cửa hàng HTML Template</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Inner-Pages Section End -->
      <!-- Room & Suite Detail Layouts Section Start -->
      <div class="room-and-suit-layout sisf-extended-grid sisf-extended-grid--right">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-5">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow slideInLeft">
                     <h2 class="sisf-m-title sisf-m-title--scroll">Thiết kế chi tiết<br> Phòng & Suite</h2>
                     <div class="sisf-m-text">
                        <p>The Beautiful Room & Suite Detail Layouts design is a sophisticated and elegant approach to interior design, aiming to create spaces that are both aesthetically pleasing and functionally efficient. This design style is characterized by meticulous attention to detail, seamless integration of various elements, and a focus on enhancing the user experience.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
               <div class="col-md-7 p-0">
                  <div class="landing-layout-image wow slideInRight">
                     <figure class="image-anime">
                        <img src="{{ asset('images/beautiful_room_img.png') }}" class="w-100" alt="LuxeStay">
                     </figure>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Room & Suite Detail Layouts Section End -->
      <!-- Activity Pages Section Start -->
      <div class="activities-pages comman--bg posetion-relative pb-5">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow slideInLeft">
                     <h2 class="sisf-m-title text-white sisf-m-title--scroll">Khám phá<br> trang hoạt động thú vị</h2>
                  </div>
                  <!-- Section Title End -->
               </div>
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow slideInRight">
                     <div class="sisf-m-text">
                        <p class="text-white mt-0">Welcome to our enticing world of flavors and carefully curated products! Step into a realm where culinary delights meet artisanal craftsmanship. Our menu and shop pages are meticulously designed to offer you an unforgettable experience, whether you're browsing for delectable dishes to satisfy your cravings or seeking unique items to elevate your home.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
            </div>
         </div>
         <div class="container-fluid">
            <div class="row">
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'event-wedding') }}">
                        <img src="{{ asset('images/activity_page1.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'fitness-and-wellness') }}">
                        <img src="{{ asset('images/activity_page2.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'golf-courses') }}">
                        <img src="{{ asset('images/activity_page3.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'hiking-and-trekking') }}">
                        <img src="{{ asset('images/activity_page4.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'leisure-and-entertainment') }}">
                        <img src="{{ asset('images/activity_page5.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'nature-and-exploration') }}">
                        <img src="{{ asset('images/activity_page6.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'ski-snowboarding') }}">
                        <img src="{{ asset('images/activity_page7.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'spa-wellness') }}">
                        <img src="{{ asset('images/activity_page8.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'unique-experiences') }}">
                        <img src="{{ asset('images/activity_page9.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'water-sports') }}">
                        <img src="{{ asset('images/activity_page10.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="sisf-e sisf-activity-page-image-wrapper sisf-grid-item wow fadeInUp">
                     <div class="sisf-e-inner position-relative">
                        <a href="{{ route('activity.show', 'winter-hiking') }}">
                        <img src="{{ asset('images/activity_page11.png') }}" class="w-100" alt="LuxeStay">
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Activity Pages Section End -->
      <!-- Startup Plateform Section Start -->
      <div class="startup-plateform position-relative sisf-extended-grid sisf-extended-grid--right">
         <div class="container pe-0">
            <div class="row align-items-center">
               <div class="col-lg-5 col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title mb-0 wow bounceInLeft">
                     <h2 class="sisf-m-title text-white sisf-m-title--scroll">Xây dựng<br> nền tảng<br> của bạn ngay hôm nay</h2>
                     <div class="sisf-m-text pb-4">
                        <p class="text-white">we are passionate about helping individuals discover their next thrilling on-water adventure. Whether you're a seasoned water enthusiast or a novice looking to explore the wonders of the water, our service/product is designed to provide you with unforgettable experiences and create lasting memories.</p>
                     </div>
                     <div class="sisf-m-button mt-2">
                        <a href="{{ route('contact') }}" class="btn-default rounded-0"><span>LIÊN HỆ</span>
                        </a>
                     </div>
                  </div>
                  <!-- Section Title End -->
               </div>
               <div class="col-lg-7 col-md-6">
                  <div class="landing-bottom-image">
                     <figure class="image-anime wow bounceInRight">
                        <img src="{{ asset('images/landing_footer_img.png') }}" class="w-100" alt="LuxeStay">
                     </figure>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Startup Plateform Section End -->
@endsection
