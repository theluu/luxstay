@extends('emails.layouts.base')
@section('subject', 'Chào mừng bạn đến với LuxeStay Newsletter!')
@section('content')
<h2>🎉 Bạn đã đăng ký thành công!</h2>
<p>Cảm ơn bạn đã đăng ký nhận bản tin của <strong>LuxeStay</strong>.</p>
<p>Bạn sẽ là người đầu tiên nhận được:</p>
<ul style="line-height:2;color:#374151;font-size:15px;padding-left:20px;">
  <li>Ưu đãi độc quyền dành riêng cho thành viên</li>
  <li>Thông tin về phòng và gói dịch vụ mới</li>
  <li>Tin tức sự kiện và hoạt động tại resort</li>
</ul>
<p style="text-align:center;margin-top:28px;">
  <a href="{{ config('app.url') }}/vi" class="btn">Khám phá LuxeStay</a>
</p>
<hr class="divider">
<p style="font-size:12px;color:#9ca3af;text-align:center;">
  Để hủy đăng ký, bạn có thể liên hệ với chúng tôi bất kỳ lúc nào.
</p>
@endsection
