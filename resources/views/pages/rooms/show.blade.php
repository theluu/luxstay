@extends('layouts.app')
@section('title', $room->name . ' – LuxeStay')
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
                  <h1 class="sisf-m-title text-center entry-title">{{ $room->name }}</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Room Detail Section Start -->
      <div class="room-detail-section section">
         <div class="container">
            <div class="sisf-room sisf-room-single sisf-room-list-item sisf-room-list--item sisf-item-layout--standard">
               <div class="row">
                  <div class="col-md-8">
                     <div class="sisf-e-top-holder">
                        <div class="sisf-e-media position-relative">
                           <div class="sisf-image-holder reveal">
                              <figure>
                                 <img src="{{ $room->thumbnail ? asset($room->thumbnail) : asset('images/room-single_img.png') }}" class="w-100" alt="Luxestay">
                              </figure>
                           </div>
                           <span class="sisf-e-price">
                           <span class="sisf-e-price-label">{{ __('rooms.price_from') }}</span>
                           <span class="sisf-e-price-value">${{ number_format($room->price_per_night, 0) }}</span>
                           </span>
                        </div>
                        <div class="sisf-e-content pb-0">
                           <div class="sisf-e-content-info my-2 wow fadeInUp">
                              <ul class="sisf-e-basic-info d-flex align-items-center ps-0 mb-3">
                                 <li class="sisf-e-item sisf-e-room-size text-black ms-0">
                                    <span><img src="{{ asset('images/small-img1.png') }}" alt="LuxeStay"></span>
                                    <span>{{ $room->size_sqm }}m2</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-capacity text-black">
                                    <span><img src="{{ asset('images/small-img2.png') }}" alt="LuxeStay"></span>
                                    <span>{{ $room->max_guests }} {{ __('common.guests') }}</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-beds text-black">
                                    <span><img src="{{ asset('images/small-img3.png') }}" alt="LuxeStay"></span>
                                    <span>{{ __('common.bed') }}</span>
                                 </li>
                              </ul>
                           </div>
                           <div class="sisf-e-content-text wow fadeInUp">
                              <h4 class="sisf-e-title entry-title mb-2">
                                 <a href="{{ route('rooms.show', $room->slug) }}">
                                 {{ $room->name }}
                                 </a>
                              </h4>
                              <p class="sisf-e-excerpt mb-0">{!! nl2br(e($room->description)) !!}</p>
                           </div>
                        </div>
                     </div>
                     <div class="sisf-e-middle-holder">
                        <div class="sisf-e-amenities sisf-room-detail wow fadeInUp">
                           <h4 class="sisf-e-title">
                              {{ __('rooms.amenities') }}
                           </h4>
                           <div class="row">
                              <div class="col-md-12">
                                 <ul class="sisf-e-amenities-holder ps-0 mb-0 list-unstyled">
                                    @foreach($room->amenities as $amenity)
                                    <li class="sisf-e sisf-e-info-item">
                                       <span class="sisf-e-icon">
                                          <i class="fas {{ $amenity->icon }}" style="font-size:18px;width:20px;text-align:center"></i>
                                       </span>
                                       <span class="sisf-e--content">{{ $amenity->name }}</span>
                                    </li>
                                    @endforeach
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="sisf-e-rules sisf-room-detail wow fadeInUp">
                           <h4 class="sisf-e-title">
                              Nội quy
                           </h4>
                           <div class="row">
                              <div class="col-12">
                                 <ul class="sisf-e-amenities-holder ps-0 mb-0 list-unstyled">
                                    <li class="sisf-e sisf-e-info-item">
                                       <span class="sisf-e-icon">
                                       <img src="{{ asset('images/rules-img1.png') }}" alt="LuxeStay">
                                       </span>
                                       <span class="sisf-e--content">
                                       {{ __('rooms.check_in_time') }}
                                       </span>
                                    </li>
                                    <li class="sisf-e sisf-e-info-item">
                                       <span class="sisf-e-icon">
                                       <img src="{{ asset('images/rules-img2.png') }}" alt="LuxeStay">
                                       </span>
                                       <span class="sisf-e--content">
                                       {{ __('rooms.check_out_time') }}
                                       </span>
                                    </li>
                                    <li class="sisf-e sisf-e-info-item">
                                       <span class="sisf-e-icon">
                                       <img src="{{ asset('images/rules-img3.png') }}" alt="LuxeStay">
                                       </span>
                                       <span class="sisf-e--content">
                                       Không hút thuốc
                                       </span>
                                    </li>
                                    <li class="sisf-e sisf-e-info-item mb-0">
                                       <span class="sisf-e-icon">
                                       <img src="{{ asset('images/rules-img4.png') }}" alt="LuxeStay">
                                       </span>
                                       <span class="sisf-e--content">
                                       Giữ gìn tài sản cẩn thận
                                       </span>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="sisf-e-availability sisf-room-detail wow fadeInUp">
                           <h4 class="sisf-e-title">
                              Lịch trống
                           </h4>
                           <div class="row">
                              <div class="col-12">
                                 <div class="sisf-room-calendar">
                                    <div id="static-calendar"></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="sisf-e--location sisf-room-detail wow fadeInUp">
                           <h4 class="sisf-e-title">
                              Vị trí
                           </h4>
                           <p class="sisf-e-location-address mb-3">348 Hauser Blvd, Los Angeles, CA 90036, USA</p>
                           <div class="sisf-e-address-additional-info">
                              <h5 class="sisf-e-address-additional-info-title mb-3">
                                 Lân cận
                              </h5>
                              <div class="sisf-e-address-additional-info-content mb-4">
                                 <div class="sisf-e-info-item mb-3">
                                    <span class="sisf-e-label">Trạm Metro: </span>
                                    <span class="sisf-e-value">1.0 Km</span>
                                 </div>
                                 <div class="sisf-e-info-item">
                                    <span class="sisf-e-label">Siêu thị: </span>
                                    <span class="sisf-e-value">1.5km</span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-12">
                                 <div class="map">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2612.159888425788!2d-118.35319442544817!3d34.06935401682862!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2b926f53b8895%3A0x8a66ea260f821437!2s348%20Hauser%20Blvd%2C%20Los%20Angeles%2C%20CA%2090036!5e1!3m2!1sen!2sus!4v1753275999792!5m2!1sen!2sus" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="sisf-e-bottom-holder">
                        <div class="row gallery-items sisf-layout--sis-masonry sisf-sis-image-gallery-masonry sisf-ripple-effect">
                           <div class="col-md-6">
                              <!-- Gallery Item Start -->
                              <div class="page-gallery--item page-gallery wow fadeInUp">
                                 <div class="gallery-item">
                                    <a href="{{ asset('images/room_gallery1.png') }}" class="sisf-e-item">
                                       <figure>
                                          <img src="{{ asset('images/room_gallery1.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </a>
                                 </div>
                              </div>
                              <!-- Gallery Item End -->
                              <div class="row">
                                 <div class="col-md-6">
                                    <!-- Gallery Item Start -->
                                    <div class="page-gallery--item wow fadeInUp">
                                       <div class="gallery-item">
                                          <a href="{{ asset('images/room-slider-image1.png') }}" class="sisf-e-item">
                                             <figure>
                                                <img src="{{ asset('images/room-slider-image1.png') }}" class="w-100" alt="LuxeStay">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                    <!-- Gallery Item End -->
                                 </div>
                                 <div class="col-md-6">
                                    <!-- Gallery Item Start -->
                                    <div class="page-gallery--item wow fadeInUp">
                                       <div class="gallery-item">
                                          <a href="{{ asset('images/room-slider-image9.png') }}" class="sisf-e-item">
                                             <figure>
                                                <img src="{{ asset('images/room-slider-image9.png') }}" class="w-100" alt="LuxeStay">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                    <!-- Gallery Item End -->
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <!-- Gallery Item Start -->
                              <div class="page-gallery--item page-gallery wow fadeInUp">
                                 <div class="gallery-item">
                                    <a href="{{ asset('images/room_gallery2.png') }}" class="sisf-e-item">
                                       <figure>
                                          <img src="{{ asset('images/room_gallery2.png') }}" class="w-100" alt="LuxeStay">
                                       </figure>
                                    </a>
                                 </div>
                              </div>
                              <!-- Gallery Item End -->
                              <div class="row">
                                 <div class="col-md-6">
                                    <!-- Gallery Item Start -->
                                    <div class="page-gallery--item wow fadeInUp">
                                       <div class="gallery-item">
                                          <a href="{{ asset('images/room-slider-image8.png') }}" class="sisf-e-item">
                                             <figure>
                                                <img src="{{ asset('images/room-slider-image8.png') }}" class="w-100" alt="LuxeStay">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                    <!-- Gallery Item End -->
                                 </div>
                                 <div class="col-md-6">
                                    <!-- Gallery Item Start -->
                                    <div class="page-gallery--item wow fadeInUp">
                                       <div class="gallery-item">
                                          <a href="{{ asset('images/room-slider-image7.png') }}" class="sisf-e-item">
                                             <figure>
                                                <img src="{{ asset('images/room-slider-image7.png') }}" class="w-100" alt="LuxeStay">
                                             </figure>
                                          </a>
                                       </div>
                                    </div>
                                    <!-- Gallery Item End -->
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="sisf-room-reservation">
                        <div class="sisf-title mb-4">
                           <h3 class="sisf-room-reservation-title text-center">{{ __('rooms.reservation_title') }}</h3>
                        </div>
                        <div class="check-in-out-form room-reservation-form form-section">
                           <form action="{{ route('bookings.store') }}" method="POST" data-recaptcha data-recaptcha-action="booking">
                              @csrf
                              <input type="hidden" name="room_id" value="{{ $room->id }}">
                              @guest
                              <div class="booking-form-col position-relative">
                                 <label class="form-label" for="guest_name">Họ và tên</label>
                                 <input type="text" id="guest_name" name="guest_name" class="form-control ps-0" placeholder="Họ và tên" value="{{ old('guest_name') }}" required>
                              </div>
                              <div class="booking-form-col position-relative">
                                 <label class="form-label" for="guest_email">Email</label>
                                 <input type="email" id="guest_email" name="guest_email" class="form-control ps-0" placeholder="Email của bạn" value="{{ old('guest_email') }}" required>
                              </div>
                              <div class="booking-form-col position-relative">
                                 <label class="form-label" for="guest_phone">Điện thoại</label>
                                 <input type="tel" id="guest_phone" name="guest_phone" class="form-control ps-0" placeholder="{{ __('rooms.guest_phone') }}" value="{{ old('guest_phone') }}">
                              </div>
                              @endguest
                              <div class="booking-form-col position-relative">
                                 <label class="form-label" for="checkin">{{ __('rooms.check_in_label') }}</label>
                                 <input type="text" id="checkin" name="check_in" class="form-control ps-0 flatpickr-input" placeholder="{{ __('rooms.select_date') }}" value="{{ old('check_in') }}" required>
                                 <i class="fa-regular fa-calendar"></i>
                              </div>
                              <div class="booking-form-col position-relative">
                                 <label class="form-label" for="checkout">{{ __('rooms.check_out_label') }}</label>
                                 <input type="text" id="checkout" name="check_out" class="form-control ps-0 flatpickr-input" placeholder="{{ __('rooms.select_date') }}" value="{{ old('check_out') }}" required>
                                 <i class="fa-regular fa-calendar"></i>
                              </div>
                              <div class="select-wrapper booking-form-col position-relative">
                                 <label class="form-label" for="rooms">Số phòng</label>
                                 <select class="form-select form-control ps-0" id="rooms">
                                    <option value="1" selected="">1</option>
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
                                 <select id="guests" name="guests" class="form-select form-control ps-0">
                                    @for($i = 1; $i <= $room->max_guests; $i++)
                                       <option value="{{ $i }}" @selected((int) old('guests', 1) === $i)>{{ $i }} Guest{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                 </select>
                                 {{-- hidden dropdown kept for UI compat --}}
                                 <div class="dropdown d-none">
                                    <button class="form-select form-control ps-0 dropdown-toggle" type="button" id="guestsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    1 Người lớn
                                    </button>
                                    <ul class="dropdown-menu p-3" aria-labelledby="guestsDropdown">
                                       <li class="mb-2">
                                          <label class="form-label d-block">Người lớn</label>
                                          <select class="form-select">
                                             <option value="0">0</option>
                                             <option value="1" selected="">1</option>
                                             <option value="2">2</option>
                                             <option value="3">3</option>
                                             <option value="4+">4+</option>
                                          </select>
                                       </li>
                                       <li class="mb-2">
                                          <label class="form-label d-block">Trẻ em <small>(2–12 tuổi)</small></label>
                                          <select class="form-select">
                                             <option value="0" selected="">0</option>
                                             <option value="1">1</option>
                                             <option value="2">2</option>
                                             <option value="3">3</option>
                                             <option value="4+">4+</option>
                                          </select>
                                       </li>
                                       <li>
                                          <label class="form-label d-block">Sơ sinh <small>(0–2 tuổi)</small></label>
                                          <select class="form-select">
                                             <option value="0" selected="">0</option>
                                             <option value="1">1</option>
                                             <option value="2">2</option>
                                          </select>
                                       </li>
                                    </ul>
                                 </div>
                                 <i class="fa-solid fa-chevron-down custom-select-icon"></i>
                              </div>
                              <!-- Extra Services part -->
                              <div class="sisf--service-box">
                                 <div class="sisf-title mb-4">
                                    <h3 class="sisf-room-reservation-title text-start">Dịch vụ thêm</h3>
                                 </div>
                                 <div class="service-item">
                                    <label><input type="checkbox" class="service" data-price="15" checked> Dọn phòng</label>
                                    <span>$15</span>
                                 </div>
                                 <div class="service-item">
                                    <label><input type="checkbox" class="service" data-price="10"> Thuê xe đạp</label>
                                    <span>$10 / người</span>
                                 </div>
                                 <div class="service-item">
                                    <label><input type="checkbox" class="service" data-price="15"> Đưa đón sân bay</label>
                                    <span>$15 / người</span>
                                 </div>
                                 <div class="service-item">
                                    <label><input type="checkbox" class="service" data-price="100"> Massage</label>
                                    <span>$100 / người</span>
                                 </div>
                                 <div class="service-item">
                                    <label><input type="checkbox" class="service" data-price="0"> Bãi đỗ xe</label>
                                    <span>Miễn phí</span>
                                 </div>
                                 <div class="price-box">
                                    <p class="mb-2 text-black">Giá của bạn</p>
                                    <span>$ <span id="total-price">414</span></span>
                                 </div>
                              </div>
                              <div class="mb-3">
                                 <label class="form-label">Yêu cầu đặc biệt</label>
                                 <textarea name="special_requests" class="form-control" rows="2" placeholder="Yêu cầu đặc biệt?">{{ old('special_requests') }}</textarea>
                              </div>
                              <div class="mb-3">
                                 <label class="form-label d-block">Phương thức thanh toán</label>
                                 <label class="d-flex align-items-start gap-2 mb-2">
                                    <input type="radio" name="payment_method" value="pay_later" @checked(old('payment_method', 'pay_later') === 'pay_later')>
                                    <span>{{ __('rooms.pay_after_confirm') }}</span>
                                 </label>
                                 <label class="d-flex align-items-start gap-2">
                                    <input type="radio" name="payment_method" value="vnpay" @checked(old('payment_method') === 'vnpay')>
                                    <span>{{ __('rooms.pay_online') }}</span>
                                 </label>
                              </div>
                              <div class="sisf-m-button text-center w-100">
                                 <button type="submit" class="btn-default w-100">Đặt ngay</button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sisf-room-related-items">
                  <div class="sis-e-items-title">
                     <h3 class="sisf-m-title">{{ __('rooms.similar_rooms') }}</h3>
                  </div>
                  <div class="row">
                     <div class="col-lg-4 col-md-6">
                        <div class="sisf-room-list-item sisf-room-list--item wow bounceInLeft">
                           <div class="sisf-e-inner">
                              <div class="sisf-e-media position-relative">
                                 <div class="sisf-single-slider sisf-e-media-slider">
                                    <div class="swiper">
                                       <div class="swiper-wrapper">
                                          <div class="swiper-slide">
                                             <div class="sisf-image-holder">
                                                <figure>
                                                   <img src="{{ asset('images/room-slider-image9.png') }}" class="w-100" alt="Luxestay">
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
                                          <div class="swiper-slide">
                                             <div class="sisf-image-holder">
                                                <figure>
                                                   <img src="{{ asset('images/room-slider-image10.png') }}" class="w-100" alt="Luxestay">
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
                                 <span class="sisf-e-price-label">{{ __('rooms.price_from') }}</span>
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
                                          <span>4 {{ __('common.guests') }}</span>
                                       </li>
                                       <li class="sisf-e-item sisf-e-beds text-black">
                                          <span><img src="{{ asset('images/small-img3.png') }}" alt="LuxeStay"></span>
                                          <span>{{ __('common.bed') }}</span>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="sisf-e-content-text">
                                    <h4 class="sisf-e-title entry-title mb-2">
                                       <a href="{{ route('rooms.index') }}">
                                       Garden View Room
                                       </a>
                                    </h4>
                                    <p class="sisf-e-excerpt mb-2">A Garden View Room typically refers to a hotel or resort room that offers a view of the property's gardens or landscaped areas. These rooms often p</p>
                                    <div class="sisf-m-button sisf-sis-clear">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.index') }}">
                                       <span class="sisf-m-text">{{ __('common.explore_more') }}</span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="sisf-room-list-item sisf-room-list--item wow zoomInUp">
                           <div class="sisf-e-inner">
                              <div class="sisf-e-media position-relative">
                                 <div class="sisf-single-slider sisf-e-media-slider">
                                    <div class="swiper">
                                       <div class="swiper-wrapper">
                                          <div class="swiper-slide">
                                             <div class="sisf-image-holder">
                                                <figure>
                                                   <img src="{{ asset('images/room-slider-image3.png') }}" class="w-100" alt="Luxestay">
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
                                          <span class="custom--icon custom-icon-left-2"><i class="fa-solid fa-play fa-flip-horizontal"></i></span>
                                          <span class="custom--icon custom-icon-right-2"><i class="fa-solid fa-play"></i></span>
                                       </div>
                                    </div>
                                 </div>
                                 <span class="sisf-e-price">
                                 <span class="sisf-e-price-label">{{ __('rooms.price_from') }}</span>
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
                                          <span>4 {{ __('common.guests') }}</span>
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
                                    <p class="sisf-e-excerpt mb-2">Experience the ultimate in coastal luxury with our Ocean View Room. Situated on the upper floors, this room offers stunning panoramic views of the shimme</p>
                                    <div class="sisf-m-button sisf-sis-clear">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.index') }}">
                                       <span class="sisf-m-text">{{ __('common.explore_more') }}</span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="sisf-room-list-item sisf-room-list--item wow bounceInRight">
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
                                                   <img src="{{ asset('images/room-slider-image7.png') }}" class="w-100" alt="Luxestay">
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
                                          <div class="swiper-slide">
                                             <div class="sisf-image-holder">
                                                <figure>
                                                   <img src="{{ asset('images/room-slider-image6.png') }}" class="w-100" alt="Luxestay">
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
                                 <span class="sisf-e-price-label">{{ __('rooms.price_from') }}</span>
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
                                          <span>4 {{ __('common.guests') }}</span>
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
                                       Luxury Suit
                                       </a>
                                    </h4>
                                    <p class="sisf-e-excerpt mb-2">A luxury suite room is typically a premium, upscale accommodation in a hotel or resort, offering a higher level of comfort, amenities, and services compa</p>
                                    <div class="sisf-m-button sisf-sis-clear">
                                       <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.index') }}">
                                       <span class="sisf-m-text">{{ __('common.explore_more') }}</span>
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
      <!-- Room Detail Section End -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var today = new Date().toISOString().split('T')[0];
    var tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];

    var checkinPicker = flatpickr('#checkin', {
        minDate: 'today',
        defaultDate: today,
        dateFormat: 'Y-m-d',
        onChange: function(selectedDates) {
            var nextDay = new Date(selectedDates[0].getTime() + 86400000);
            checkoutPicker.set('minDate', nextDay);
            if (checkoutPicker.selectedDates[0] <= selectedDates[0]) {
                checkoutPicker.setDate(nextDay);
            }
        }
    });

    var checkoutPicker = flatpickr('#checkout', {
        minDate: tomorrow,
        defaultDate: tomorrow,
        dateFormat: 'Y-m-d',
    });
});
</script>
@endpush
