@extends('emails.layouts.base')
@section('subject', 'Xác nhận đặt phòng #' . $booking->id . ' — LuxeStay')
@section('content')
<h2>🏨 Đặt phòng đã được xác nhận!</h2>
<p>Xin chào <strong>{{ $guestName }}</strong>,</p>
<p>Chúng tôi đã nhận được yêu cầu đặt phòng của bạn. Chi tiết như sau:</p>
<div class="detail-box">
  <table>
    <tr><td>Mã đặt phòng</td><td>#{{ $booking->id }}</td></tr>
    <tr><td>Phòng</td><td>{{ $booking->room->name ?? 'N/A' }}</td></tr>
    <tr><td>Nhận phòng</td><td>{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</td></tr>
    <tr><td>Trả phòng</td><td>{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</td></tr>
    <tr><td>Số khách</td><td>{{ $booking->guests }}</td></tr>
    <tr><td>Tổng tiền</td><td>{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</td></tr>
    <tr><td>Trạng thái</td><td>{{ $booking->payment_method === 'vnpay' ? 'Đã thanh toán online' : 'Thanh toán khi đến' }}</td></tr>
  </table>
</div>
@if($booking->special_requests)
<p><strong>Yêu cầu đặc biệt:</strong> {{ $booking->special_requests }}</p>
@endif
<p style="text-align:center;margin-top:24px;">
  <a href="{{ config('app.url') }}/vi/bookings/{{ $booking->id }}/confirmation" class="btn">Xem chi tiết đặt phòng</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu có thắc mắc, vui lòng liên hệ với chúng tôi qua email hoặc điện thoại.</p>
@endsection
