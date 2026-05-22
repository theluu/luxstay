@extends('emails.layouts.base')
@section('subject', 'Xác nhận đơn hàng #' . $order->id . ' — LuxeStay')
@section('content')
<h2>🛍️ Đơn hàng đã được đặt thành công!</h2>
<p>Xin chào <strong>{{ $customerName }}</strong>,</p>
<p>Cảm ơn bạn đã mua sắm tại LuxeStay Shop. Đơn hàng của bạn đã được tiếp nhận.</p>
<div class="detail-box">
  <table>
    <tr><td>Mã đơn hàng</td><td>#{{ $order->id }}</td></tr>
    <tr><td>Trạng thái</td><td>{{ $order->status === 'pending' ? 'Chờ xử lý' : 'Đang xử lý' }}</td></tr>
    <tr><td>Phương thức</td><td>{{ $order->payment_status === 'paid' ? 'Đã thanh toán (VNPAY)' : 'Thanh toán khi nhận hàng (COD)' }}</td></tr>
    <tr><td>Tổng tiền</td><td>{{ number_format($order->total, 0, ',', '.') }} VNĐ</td></tr>
    @if($order->shipping_address)
    <tr><td>Địa chỉ giao</td><td>
      {{ $order->shipping_address['address_1'] ?? '' }},
      {{ $order->shipping_address['city'] ?? '' }}
    </td></tr>
    @endif
  </table>
</div>
@if($order->items && $order->items->count())
<p><strong>Sản phẩm đã đặt:</strong></p>
<div class="detail-box">
  <table>
    @foreach($order->items as $item)
    <tr>
      <td>{{ $item->product->name ?? 'Sản phẩm #' . $item->product_id }}</td>
      <td style="text-align:right">{{ $item->quantity }} × {{ number_format($item->unit_price, 0, ',', '.') }} VNĐ</td>
    </tr>
    @endforeach
  </table>
</div>
@endif
<p style="text-align:center;margin-top:24px;">
  <a href="{{ config('app.url') }}/vi/account/orders/{{ $order->id }}" class="btn">Xem chi tiết đơn hàng</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu có thắc mắc, vui lòng liên hệ với chúng tôi qua email hoặc điện thoại.</p>
@endsection
