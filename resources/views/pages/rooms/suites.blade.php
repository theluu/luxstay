@extends('layouts.app')
@section('title', 'Rooms & Suites – LuxeStay')
@section('content')
      <!-- Banner Section Start -->
      <div class="sisf-banner position-relative">
         <div class="banner-img">
            <figure>
               <img src="{{ asset('images/room_hero_img2.png') }}" alt="Luxestay">
            </figure>
         </div>
         <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
            <div class="sisf-m-inner">
               <div class="sisf-m-content sisf-content-grid ">
                  <h1 class="sisf-m-title text-center entry-title">Room & Suits</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Check In Check Out Section Start -->
      <div class="check-in-out--section">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <div class="check-in-out-form bg-black p-4 form-section wow bounceInRight">
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
      <!-- Rooms Section Start -->
      <div class="rooms-section section pb-0">
         <div class="container">
            <div class="row">
               @forelse($rooms as $room)
               <div class="col-lg-4 col-md-6">
                  <div class="sisf-room-list-item sisf-room-list--item sisf-item--full wow zoomInUp">
                     <div class="sisf-e-inner">
                        <div class="sisf-e-media position-relative">
                           <div class="sisf-image-holder">
                              <figure>
                                 <img src="{{ $room->thumbnail ? asset('storage/' . $room->thumbnail) : asset('images/room-suits-img1.png') }}" class="w-100" alt="{{ $room->name }}">
                              </figure>
                           </div>
                           <span class="sisf-e-price">
                           <span class="sisf-e-price-label">From</span>
                           <span class="sisf-e-price-value">${{ number_format($room->price_per_night, 0) }}</span>
                           </span>
                        </div>
                        <div class="sisf-e-content">
                           <div class="sisf-e-content-info">
                              <ul class="sisf-e-basic-info d-flex align-items-center ps-0 mb-3">
                                 <li class="sisf-e-item sisf-e-room-size text-black ms-0">
                                    <span><img src="{{ asset('images/small-img1.png') }}" alt="LuxeStay"></span>
                                    <span>{{ $room->size_sqm }}m2</span>
                                 </li>
                                 <li class="sisf-e-item sisf-e-capacity text-black">
                                    <span><img src="{{ asset('images/small-img2.png') }}" alt="LuxeStay"></span>
                                    <span>{{ $room->max_guests }} Guests</span>
                                 </li>
                              </ul>
                           </div>
                           <div class="sisf-e-content-text">
                              <h4 class="sisf-e-title entry-title mb-2">
                                 <a href="{{ route('rooms.show', $room->slug) }}">
                                 {{ $room->name }}
                                 </a>
                              </h4>
                              <p class="sisf-e-excerpt mb-2">{{ Str::limit($room->description, 90) }}</p>
                              <div class="sisf-m-button sisf-sis-clear">
                                 <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.show', $room->slug) }}">
                                 <span class="sisf-m-text">Explore More</span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @empty
               <div class="col-12 text-center py-5"><p>No rooms available at this time.</p></div>
               @endforelse
            </div>
         </div>
      </div>
      <!-- Rooms Section End -->
@endsection
