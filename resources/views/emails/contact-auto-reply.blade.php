@extends('emails.layouts.base')
@section('subject', 'Cảm ơn bạn đã liên hệ với LuxeStay')
@section('content')
<h2>Cảm ơn bạn, {{ $senderName }}!</h2>
<p>Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong vòng <strong>24 giờ làm việc</strong>.</p>
<div class="detail-box">
  <table>
    <tr><td>Nội dung đã gửi</td><td>{{ $messageText }}</td></tr>
  </table>
</div>
<p>Trong thời gian chờ đợi, bạn có thể khám phá các phòng và dịch vụ của chúng tôi:</p>
<p style="text-align:center;margin-top:24px;">
  <a href="{{ config('app.url') }}/vi/rooms" class="btn">Khám phá phòng nghỉ</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu bạn cần hỗ trợ khẩn cấp, hãy liên hệ trực tiếp qua điện thoại.</p>
@endsection
