@extends('layouts.app')
@section('title', 'Booking Confirmed – LuxeStay')
@section('content')

<div class="sisf-banner position-relative">
   <div class="banner-img">
      <figure><img src="{{ asset('images/title-banner.png') }}" alt="LuxeStay"></figure>
   </div>
   <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
      <div class="sisf-m-inner">
         <div class="sisf-m-content sisf-content-grid">
            <h1 class="sisf-m-title text-center entry-title">Đặt phòng thành công</h1>
         </div>
      </div>
   </div>
</div>

<div class="section">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-7">
            <div class="text-center mb-5">
               <div style="font-size:64px; line-height:1">✓</div>
               <h2 class="mt-3 mb-2">Cảm ơn bạn{{ Auth::check() ? ', ' . Auth::user()->name : '' }}!</h2>
               <p class="text-muted">Đặt phòng của bạn đã được nhận. Chúng tôi sẽ gửi xác nhận đến email của bạn sớm.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
               <h5 class="mb-3 fw-bold">Tóm tắt đặt phòng</h5>
               <table class="table table-borderless mb-0">
                  <tbody>
                     <tr>
                        <td class="text-muted ps-0">Đặt phòng #</td>
                        <td class="fw-medium text-end">{{ $booking->id }}</td>
                     </tr>
                     <tr>
                        <td class="text-muted ps-0">Phòng</td>
                        <td class="fw-medium text-end">{{ $booking->room->name }}</td>
                     </tr>
                     <tr>
                        <td class="text-muted ps-0">Loại phòng</td>
                        <td class="fw-medium text-end">{{ $booking->room->roomType->name ?? '—' }}</td>
                     </tr>
                     <tr>
                        <td class="text-muted ps-0">Nhận phòng</td>
                        <td class="fw-medium text-end">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</td>
                     </tr>
                     <tr>
                        <td class="text-muted ps-0">Trả phòng</td>
                        <td class="fw-medium text-end">{{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</td>
                     </tr>
                     <tr>
                        <td class="text-muted ps-0">Số đêm</td>
                        <td class="fw-medium text-end">{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays($booking->check_out) }}</td>
                     </tr>
                     <tr>
                        <td class="text-muted ps-0">Khách</td>
                        <td class="fw-medium text-end">{{ $booking->guests }}</td>
                     </tr>
                     @if($booking->special_requests)
                     <tr>
                        <td class="text-muted ps-0">Yêu cầu đặc biệt</td>
                        <td class="fw-medium text-end">{{ $booking->special_requests }}</td>
                     </tr>
                     @endif
                     <tr class="border-top">
                        <td class="ps-0 fw-bold pt-3">Tổng tiền</td>
                        <td class="fw-bold text-end pt-3">${{ number_format($booking->total_price, 2) }}</td>
                     </tr>
                     <tr>
                        <td class="ps-0 text-muted">Trạng thái thanh toán</td>
                        <td class="text-end">
                           <span class="badge bg-warning text-dark">{{ ucfirst($booking->payment_status) }}</span>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>

            <div class="d-flex gap-3 justify-content-center">
               <a href="{{ route('home') }}" class="btn-default">Về trang chủ</a>
               @auth
               <a href="{{ route('bookings.index') }}" class="sisf-shortcode sisf-text-underline sisf-underline--left">
                  <span class="sisf-m-text">Xem tất cả đặt phòng</span>
               </a>
               @endauth
            </div>
         </div>
      </div>
   </div>
</div>

@endsection
