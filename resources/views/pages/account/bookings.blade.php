@extends('layouts.app')
@section('title', 'My Bookings – LuxeStay')
@section('content')

<div class="sisf-banner position-relative">
   <div class="banner-img">
      <figure><img src="{{ asset('images/address-bg.png') }}" alt="LuxeStay"></figure>
   </div>
   <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
      <div class="sisf-m-inner">
         <div class="sisf-m-content sisf-content-grid">
            <h1 class="sisf-m-title text-center entry-title">Đặt phòng của tôi</h1>
         </div>
      </div>
   </div>
</div>

<div class="section">
   <div class="container">
      <div class="row">
         {{-- Sidebar --}}
         <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-3 p-3">
               <p class="fw-bold mb-3">{{ Auth::user()->name }}</p>
               <nav class="d-flex flex-column gap-1">
                  <a href="{{ route('account.index') }}" class="text-decoration-none text-muted py-1">Tài khoản của tôi</a>
                  <a href="{{ route('bookings.index') }}" class="text-decoration-none fw-medium text-black py-1">Đặt phòng của tôi</a>
                  <a href="{{ route('orders.index') }}" class="text-decoration-none text-muted py-1">Đơn hàng của tôi</a>
                  <a href="{{ route('account.address') }}" class="text-decoration-none text-muted py-1">Địa chỉ</a>
               </nav>
            </div>
         </div>

         {{-- Bookings table --}}
         <div class="col-lg-9">
            @if($bookings->isEmpty())
               <div class="text-center py-5">
                  <p class="text-muted mb-4">Bạn chưa có đặt phòng nào.</p>
                  <a href="{{ route('rooms.index') }}" class="btn-default">Xem phòng</a>
               </div>
            @else
               <div class="table-responsive">
                  <table class="table align-middle">
                     <thead class="table-light">
                        <tr>
                           <th>#</th>
                           <th>Phòng</th>
                           <th>Nhận phòng</th>
                           <th>Trả phòng</th>
                           <th>Khách</th>
                           <th>Tổng tiền</th>
                           <th>Trạng thái</th>
                           <th>Thanh toán</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                           <td>{{ $booking->id }}</td>
                           <td>{{ $booking->room->name ?? '—' }}</td>
                           <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</td>
                           <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</td>
                           <td>{{ $booking->guests }}</td>
                           <td>${{ number_format($booking->total_price, 2) }}</td>
                           <td>
                              @php $s = $booking->status @endphp
                              <span class="badge {{ $s === 'confirmed' ? 'bg-success' : ($s === 'cancelled' ? 'bg-danger' : ($s === 'completed' ? 'bg-secondary' : 'bg-warning text-dark')) }}">
                                 {{ ucfirst($s) }}
                              </span>
                           </td>
                           <td>
                              @php $p = $booking->payment_status @endphp
                              <span class="badge {{ $p === 'paid' ? 'bg-success' : ($p === 'refunded' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                                 {{ ucfirst($p) }}
                              </span>
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div class="mt-4">{{ $bookings->links() }}</div>
            @endif
         </div>
      </div>
   </div>
</div>

@endsection
