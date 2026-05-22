@extends('emails.layouts.base')
@section('subject', 'Đặt lại mật khẩu LuxeStay')
@section('content')
<h2>Đặt lại mật khẩu</h2>
<p>Xin chào <strong>{{ $userName }}</strong>,</p>
<p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản LuxeStay của bạn.</p>
<p>Nhấp vào nút bên dưới để tạo mật khẩu mới. Liên kết này sẽ hết hạn sau <strong>60 phút</strong>.</p>
<p style="text-align:center;margin-top:28px;">
  <a href="{{ $resetUrl }}" class="btn">Đặt lại mật khẩu</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu nút không hoạt động, hãy sao chép và dán URL sau vào trình duyệt:</p>
<p style="font-size:12px;word-break:break-all;color:#6b7280;">{{ $resetUrl }}</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này. Tài khoản của bạn vẫn an toàn.</p>
@endsection
