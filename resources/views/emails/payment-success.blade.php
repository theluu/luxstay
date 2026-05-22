@extends('emails.layouts.base')
@section('subject', 'Thanh toán VNPAY thành công')
@section('content')
<h2>✅ Thanh toán thành công!</h2>
<p>Xin chào <strong>{{ $customerName }}</strong>,</p>
<p>Giao dịch VNPAY của bạn đã được xác nhận thành công.</p>
<div class="detail-box">
  <table>
    @if($payableType === 'booking')
    <tr><td>Mã đặt phòng</td><td>#{{ $payable->id }}</td></tr>
    <tr><td>Phòng</td><td>{{ $payable->room->name ?? 'N/A' }}</td></tr>
    <tr><td>Nhận phòng</td><td>{{ \Carbon\Carbon::parse($payable->check_in)->format('d/m/Y') }}</td></tr>
    <tr><td>Trả phòng</td><td>{{ \Carbon\Carbon::parse($payable->check_out)->format('d/m/Y') }}</td></tr>
    <tr><td>Tổng tiền</td><td>{{ number_format($payable->total_price, 0, ',', '.') }} VNĐ</td></tr>
    @else
    <tr><td>Mã đơn hàng</td><td>#{{ $payable->id }}</td></tr>
    <tr><td>Tổng tiền</td><td>{{ number_format($payable->total, 0, ',', '.') }} VNĐ</td></tr>
    @endif
    <tr><td>Trạng thái thanh toán</td><td style="color:#059669;font-weight:600;">Đã thanh toán</td></tr>
  </table>
</div>
<p style="text-align:center;margin-top:24px;">
  @if($payableType === 'booking')
  <a href="{{ config('app.url') }}/vi/bookings/{{ $payable->id }}/confirmation" class="btn">Xem đặt phòng</a>
  @else
  <a href="{{ config('app.url') }}/vi/account/orders/{{ $payable->id }}" class="btn">Xem đơn hàng</a>
  @endif
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Đây là email xác nhận tự động. Vui lòng lưu lại để tham khảo.</p>
@endsection
