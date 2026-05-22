<?php
namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $appUrl = config('app.url', 'https://luxestay.ddev.site');

        $templates = [
            [
                'key'       => 'contact_received',
                'name'      => 'Thông báo liên hệ (Admin)',
                'subject'   => 'Tin nhắn liên hệ mới từ {sender_name}',
                'variables' => ['sender_name', 'sender_email', 'message', 'source'],
                'body'      => <<<HTML
<h2>📩 Tin nhắn liên hệ mới</h2>
<p>Bạn vừa nhận được một tin nhắn liên hệ từ website LuxeStay.</p>
<div class="detail-box">
  <table>
    <tr><td>Họ tên</td><td>{sender_name}</td></tr>
    <tr><td>Email</td><td>{sender_email}</td></tr>
    <tr><td>Nguồn</td><td>{source}</td></tr>
    <tr><td>Nội dung</td><td>{message}</td></tr>
  </table>
</div>
<p>Hãy phản hồi sớm để duy trì trải nghiệm khách hàng tốt nhất.</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Email này được gửi tự động từ hệ thống LuxeStay.</p>
HTML,
            ],
            [
                'key'       => 'contact_auto_reply',
                'name'      => 'Xác nhận liên hệ (Khách)',
                'subject'   => 'Cảm ơn bạn đã liên hệ với LuxeStay',
                'variables' => ['sender_name', 'message'],
                'body'      => <<<HTML
<h2>Cảm ơn bạn, {sender_name}!</h2>
<p>Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong vòng <strong>24 giờ làm việc</strong>.</p>
<div class="detail-box">
  <table>
    <tr><td>Nội dung đã gửi</td><td>{message}</td></tr>
  </table>
</div>
<p>Trong thời gian chờ đợi, bạn có thể khám phá các phòng và dịch vụ của chúng tôi:</p>
<p style="text-align:center;margin-top:24px;">
  <a href="{app_url}/vi/rooms" class="btn">Khám phá phòng nghỉ</a>
</p>
HTML,
            ],
            [
                'key'       => 'booking_confirmation',
                'name'      => 'Xác nhận đặt phòng',
                'subject'   => 'Xác nhận đặt phòng #{booking_id} — LuxeStay',
                'variables' => ['guest_name', 'booking_id', 'room_name', 'check_in', 'check_out', 'guests', 'total_price'],
                'body'      => <<<HTML
<h2>🏨 Đặt phòng đã được xác nhận!</h2>
<p>Xin chào <strong>{guest_name}</strong>,</p>
<p>Chúng tôi đã nhận được yêu cầu đặt phòng của bạn. Chi tiết như sau:</p>
<div class="detail-box">
  <table>
    <tr><td>Mã đặt phòng</td><td>#{booking_id}</td></tr>
    <tr><td>Phòng</td><td>{room_name}</td></tr>
    <tr><td>Nhận phòng</td><td>{check_in}</td></tr>
    <tr><td>Trả phòng</td><td>{check_out}</td></tr>
    <tr><td>Số khách</td><td>{guests}</td></tr>
    <tr><td>Tổng tiền</td><td>{total_price} VNĐ</td></tr>
  </table>
</div>
<p style="text-align:center;margin-top:24px;">
  <a href="{app_url}/vi/bookings/{booking_id}/confirmation" class="btn">Xem chi tiết đặt phòng</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu có thắc mắc, vui lòng liên hệ với chúng tôi.</p>
HTML,
            ],
            [
                'key'       => 'order_confirmation',
                'name'      => 'Xác nhận đơn hàng',
                'subject'   => 'Xác nhận đơn hàng #{order_id} — LuxeStay',
                'variables' => ['customer_name', 'order_id', 'total', 'payment_method'],
                'body'      => <<<HTML
<h2>🛍️ Đơn hàng đã được đặt thành công!</h2>
<p>Xin chào <strong>{customer_name}</strong>,</p>
<p>Cảm ơn bạn đã mua sắm tại LuxeStay Shop. Đơn hàng của bạn đã được tiếp nhận.</p>
<div class="detail-box">
  <table>
    <tr><td>Mã đơn hàng</td><td>#{order_id}</td></tr>
    <tr><td>Phương thức</td><td>{payment_method}</td></tr>
    <tr><td>Tổng tiền</td><td>{total} VNĐ</td></tr>
  </table>
</div>
<p style="text-align:center;margin-top:24px;">
  <a href="{app_url}/vi/account/orders/{order_id}" class="btn">Xem chi tiết đơn hàng</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu có thắc mắc, vui lòng liên hệ với chúng tôi.</p>
HTML,
            ],
            [
                'key'       => 'payment_success',
                'name'      => 'Thanh toán VNPAY thành công',
                'subject'   => 'Thanh toán VNPAY thành công — {payable_type} #{payable_id}',
                'variables' => ['customer_name', 'payable_id', 'payable_type', 'total'],
                'body'      => <<<HTML
<h2>✅ Thanh toán thành công!</h2>
<p>Xin chào <strong>{customer_name}</strong>,</p>
<p>Giao dịch VNPAY của bạn đã được xác nhận thành công.</p>
<div class="detail-box">
  <table>
    <tr><td>Loại</td><td>{payable_type}</td></tr>
    <tr><td>Mã</td><td>#{payable_id}</td></tr>
    <tr><td>Tổng tiền</td><td>{total} VNĐ</td></tr>
    <tr><td>Trạng thái</td><td style="color:#059669;font-weight:600;">Đã thanh toán</td></tr>
  </table>
</div>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Đây là email xác nhận tự động. Vui lòng lưu lại để tham khảo.</p>
HTML,
            ],
            [
                'key'       => 'subscriber_welcome',
                'name'      => 'Chào mừng đăng ký nhận tin',
                'subject'   => 'Chào mừng bạn đến với LuxeStay Newsletter!',
                'variables' => [],
                'body'      => <<<HTML
<h2>🎉 Bạn đã đăng ký thành công!</h2>
<p>Cảm ơn bạn đã đăng ký nhận bản tin của <strong>LuxeStay</strong>.</p>
<p>Bạn sẽ là người đầu tiên nhận được:</p>
<ul style="line-height:2;color:#374151;font-size:15px;padding-left:20px;">
  <li>Ưu đãi độc quyền dành riêng cho thành viên</li>
  <li>Thông tin về phòng và gói dịch vụ mới</li>
  <li>Tin tức sự kiện và hoạt động tại resort</li>
</ul>
<p style="text-align:center;margin-top:28px;">
  <a href="{app_url}/vi" class="btn">Khám phá LuxeStay</a>
</p>
HTML,
            ],
            [
                'key'       => 'welcome',
                'name'      => 'Chào mừng tài khoản mới',
                'subject'   => 'Chào mừng bạn đến với LuxeStay, {name}!',
                'variables' => ['name', 'email', 'phone'],
                'body'      => <<<HTML
<h2>Chào mừng, {name}! 👋</h2>
<p>Tài khoản LuxeStay của bạn đã được tạo thành công. Bây giờ bạn có thể:</p>
<ul style="line-height:2;color:#374151;font-size:15px;padding-left:20px;">
  <li>Đặt phòng và theo dõi lịch sử đặt phòng</li>
  <li>Mua sản phẩm từ cửa hàng resort</li>
  <li>Quản lý thông tin cá nhân</li>
</ul>
<div class="detail-box">
  <table>
    <tr><td>Email đăng nhập</td><td>{email}</td></tr>
  </table>
</div>
<p style="text-align:center;margin-top:24px;">
  <a href="{app_url}/vi/rooms" class="btn">Khám phá phòng nghỉ</a>
</p>
HTML,
            ],
            [
                'key'       => 'reset_password',
                'name'      => 'Đặt lại mật khẩu',
                'subject'   => 'Đặt lại mật khẩu LuxeStay của bạn',
                'variables' => ['name', 'reset_url'],
                'body'      => <<<HTML
<h2>Đặt lại mật khẩu</h2>
<p>Xin chào <strong>{name}</strong>,</p>
<p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản LuxeStay của bạn.</p>
<p>Nhấp vào nút bên dưới để tạo mật khẩu mới. Liên kết này sẽ hết hạn sau <strong>60 phút</strong>.</p>
<p style="text-align:center;margin-top:28px;">
  <a href="{reset_url}" class="btn">Đặt lại mật khẩu</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này. Tài khoản của bạn vẫn an toàn.</p>
HTML,
            ],
        ];

        foreach ($templates as $data) {
            EmailTemplate::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
