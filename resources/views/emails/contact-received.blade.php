@extends('emails.layouts.base')
@section('subject', 'Tin nhắn liên hệ mới từ ' . $senderName)
@section('content')
<h2>📩 Tin nhắn liên hệ mới</h2>
<p>Bạn vừa nhận được một tin nhắn liên hệ từ website LuxeStay.</p>
<div class="detail-box">
  <table>
    <tr><td>Họ tên</td><td>{{ $senderName }}</td></tr>
    <tr><td>Email</td><td>{{ $senderEmail }}</td></tr>
    <tr><td>Nguồn</td><td>{{ $source }}</td></tr>
    <tr><td>Nội dung</td><td>{{ $messageText }}</td></tr>
  </table>
</div>
<p>Hãy phản hồi sớm để duy trì trải nghiệm khách hàng tốt nhất.</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Email này được gửi tự động từ hệ thống LuxeStay.</p>
@endsection
