@extends('emails.layouts.base')
@section('subject', 'Chào mừng bạn đến với LuxeStay, ' . $user->name . '!')
@section('content')
<h2>Chào mừng, {{ $user->name }}! 👋</h2>
<p>Tài khoản LuxeStay của bạn đã được tạo thành công. Bây giờ bạn có thể:</p>
<ul style="line-height:2;color:#374151;font-size:15px;padding-left:20px;">
  <li>Đặt phòng và theo dõi lịch sử đặt phòng</li>
  <li>Mua sản phẩm từ cửa hàng resort</li>
  <li>Quản lý thông tin cá nhân</li>
</ul>
<div class="detail-box">
  <table>
    <tr><td>Email đăng nhập</td><td>{{ $user->email }}</td></tr>
    @if($user->phone)
    <tr><td>Số điện thoại</td><td>{{ $user->phone }}</td></tr>
    @endif
  </table>
</div>
<p style="text-align:center;margin-top:24px;">
  <a href="{{ config('app.url') }}/vi/rooms" class="btn">Khám phá phòng nghỉ</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu bạn không tạo tài khoản này, hãy bỏ qua email này.</p>
@endsection
