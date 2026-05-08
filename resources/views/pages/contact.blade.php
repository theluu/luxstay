@extends('layouts.app')
@section('title', 'Contact – LuxeStay')
@section('content')
      {{-- contact.html body content between header and footer --}}
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
                  <h1 class="sisf-m-title text-center entry-title">Contacts</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Get In Touch Section Start -->
      <div class="get-in-touch-section section">
         <div class="container">
            <div class="row">
               <div class="col-md-8 ms-auto me-auto">
                  <!-- Section-Title Start -->
                  <div class="sisf-sis-section-title section-title text-center wow wow-bounce">
                     <h3 class="sisf-m-subtitle">Get In Touch</h3>
                     <h2 class="sisf-m-title">Quality Services & Activities<br> Near you</h2>
                     <div class="sisf-m-text">
                        <p>Our Comfort Is Our Priority" expresses a commitment to providing the highest level of comfort and satisfaction for our customers. Whether you're staying with us, using our services, or purchasing our products, we prioritize your needs and ensure a relaxing and enjoyable experience.</p>
                     </div>
                  </div>
                  <!-- Section-Title End -->
               </div>
            </div>
            <div class="contact-opning-contents sisf-opning-contents wow zoomInUp">
               <div class="row">
                  <div class="col-md-4">
                     <div class="sisf-icon-with-content d-flex align-items-center">
                        <div class="sisf-icon-holder me-4">
                           <span><i class="fa-solid fa-calendar-days"></i></span>
                        </div>
                        <div class="sisf-m-content border-0">
                           <h5 class="sisf-m-title">
                              <span class="sisf-m-title-text">Opening Date:</span>
                           </h5>
                           <div class="sisf-m-text">Monday - Saturday</div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="sisf-icon-with-content sisf--m-content d-flex align-items-center">
                        <div class="sisf-icon-holder me-4">
                           <span><i class="fa-regular fa-clock"></i></span>
                        </div>
                        <div class="sisf-m-content border-0">
                           <h5 class="sisf-m-title">
                              <span class="sisf-m-title-text">Opening Hours:</span>
                           </h5>
                           <div class="sisf-m-text">06:00 am – 22:00 pm</div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="sisf-icon-with-content sisf--m-content border-0 d-flex align-items-center">
                        <div class="sisf-icon-holder me-4">
                           <span><i class="fa-solid fa-blender-phone"></i></span>
                        </div>
                        <div class="sisf-m-content border-0">
                           <h5 class="sisf-m-title">
                              <span class="sisf-m-title-text">Phone Booking:</span>
                           </h5>
                           <div class="sisf-m-text">+1 850 6396 102</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Get In Touch Section End -->
      <!-- Our Address Section Start -->
      <div class="our-address-section section">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-5 pe-0">
                  <div class="address-image wow slideInLeft">
                     <figure>
                        <img src="{{ asset('images/best_offer_img1.png') }}" class="w-100" alt="LuxeStay">
                     </figure>
                  </div>
               </div>
               <div class="col-md-7 ps-0">
                  <div class="our-address">
                     <div class="row">
                        <div class="col-md-6">
                           <!-- Content-Start -->
                           <div class="sisf-m-content wow rotateInDownLeft">
                              <h5 class="sisf-m-title">
                                 <span class="sisf-m-title-text">ADDRESS</span>
                              </h5>
                              <div class="sisf-m-text mt-4">
                                 <p>Nordstrom NYC Flagship<br> 222 West 56th Street, New<br> York, NY 10019<br> +1(646) 779 6425</p>
                              </div>
                           </div>
                           <!-- Content-End -->
                        </div>
                        <div class="col-md-6">
                           <!-- Content-Start -->
                           <div class="sisf-m-content wow rotateInDownRight">
                              <h5 class="sisf-m-title">
                                 <span class="sisf-m-title-text">ADDRESS</span>
                              </h5>
                              <div class="sisf-m-text mt-4">
                                 <p>Nordstrom NYC Flagship<br> 20 Cooper Square, San<br> Francisco, CA 90017, USA<br> +1(646) 778 7850</p>
                              </div>
                           </div>
                           <!-- Content-End -->
                        </div>
                        <div class="col-md-6">
                           <!-- Content-Start -->
                           <div class="sisf-m-content wow rotateInDownLeft">
                              <h5 class="sisf-m-title">
                                 <span class="sisf-m-title-text">OPENING HOURS</span>
                              </h5>
                              <div class="sisf-m-text mt-4">
                                 <span class="open-close"> Mon – Fri: 11am – 8pm<br>Sat – Sun: 11am – 6pm</span>
                              </div>
                           </div>
                           <!-- Content-End -->
                        </div>
                        <div class="col-md-6">
                           <!-- Content-Start -->
                           <div class="sisf-m-content wow rotateInDownRight">
                              <h5 class="sisf-m-title">
                                 <span class="sisf-m-title-text">OPENING HOURS</span>
                              </h5>
                              <div class="sisf-m-text mt-4">
                                 <span> Mon – Fri: 11am – 8pm<br>Sat – Sun: 11am – 6pm</span>
                              </div>
                           </div>
                           <!-- Content-End -->
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Our Address Section End -->
      <!-- We Are Here Section Start -->
      <div class="we-are-here section">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <!-- Section Title Start -->
                  <div class="sisf-sis-section-title section-title wow slideInLeft">
                     <h2 class="sisf-m-title text-capitalize">We're Here For You</h2>
                     <div class="sisf-m-text">
                        <p>We understand that your needs are important, and we are here to assist you every step of the way. Our dedicated team is committed to providing top-notch support and addressing any questions or concerns you may have.</p>
                     </div>
                  </div>
                  <!-- Section Title End -->
                  <!-- Contact Information Start -->
                  <div class="contact-social-icons wow slideInLeft">
                     <a href="#"><i class="fa-brands fa-facebook"></i></a>
                     <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                     <a href="#"><i class="fa-brands fa-instagram"></i></a>
                     <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                  </div>
                  <!-- Contact Information End -->
                  <!-- Contact Form Start -->
                  <div class="contact-here-form contact-here--form p-0">
                     <form id="contactForm" action="{{ route('contact') }}" method="POST" data-toggle="validator" data-wow-delay="0.5s">
                        @csrf
                        <div class="row align-items-center">
                           <div class="form form-group wow bounceInLeft">
                              <div class="help-block with-errors"></div>
                              <label class="sisf-m-field-label" for="name">NAME</label>
                              <input type="text" class="form-control" name="name" id="name" placeholder="Your Name" required="">
                           </div>
                           <div class="form form-group wow bounceInLeft">
                              <div class="help-block with-errors"></div>
                              <label class="sisf-m-field-label" for="email">EMAIL</label>
                              <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required="">
                           </div>
                           <div class="form form-group wow bounceInLeft">
                              <div class="help-block with-errors"></div>
                              <label class="sisf-m-field-label" for="msg">YOUR MESSAGE</label>
                              <textarea class="form-control" rows="3" name="msg" id="msg" placeholder="Your Message" required=""></textarea>
                           </div>
                           <div class="sisf-m-button wow bounceInLeft">
                              <button type="submit" class="btn-default w-100 text-center">
                              <span class="sisf-m-text">CHECK AVAILABILITY</span>
                              </button>
                              <div id="msgSubmit" class="hidden"></div>
                           </div>
                        </div>
                     </form>
                  </div>
                  <!-- Contact Form End -->
               </div>
               <div class="col-md-6">
                  <div class="contact-map wow wow-bounce">
                     <iframe src="https://maps.google.com/maps?q=744%20Sipes%20Center%2C%20West%20Bobbyshire%2C%20%20MT%2075517%2C%20USA&amp;t=m&amp;z=10&amp;output=embed&amp;iwloc=near" title="744 Sipes Center, West Bobbyshire,  MT 75517, USA" aria-label="744 Sipes Center, West Bobbyshire,  MT 75517, USA"></iframe>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- We Are Here Section End -->
@endsection
