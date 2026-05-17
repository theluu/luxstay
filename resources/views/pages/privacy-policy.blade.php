@extends('layouts.app')
@section('title', 'Chính sách bảo mật – LuxeStay')
@section('content')
   <!-- Banner Section Start -->
   <div class="sisf-banner position-relative">
      <div class="banner-img">
         <figure>
            <img src="{{ asset('images/title-banner.png') }}" alt="LuxeStay">
         </figure>
      </div>
      <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
         <div class="sisf-m-inner">
            <div class="sisf-m-content sisf-content-grid">
               <h1 class="sisf-m-title text-center entry-title">Chính sách bảo mật</h1>
            </div>
         </div>
      </div>
   </div>
   <!-- Banner Section End -->

   <!-- Privacy Policy Content Start -->
   <div class="section">
      <div class="container">
         <div class="row">
            <div class="col-md-10 ms-auto me-auto">
               <div class="privacy-policy-content" style="line-height:1.9;color:#444;font-size:16px">

                  <h3 style="margin-top:40px;margin-bottom:12px;color:#1a1a1a">1. Thông tin chúng tôi thu thập</h3>
                  <p>LuxeStay thu thập thông tin bạn cung cấp trực tiếp khi đặt phòng, đăng ký tài khoản, liên hệ hoặc đăng ký nhận bản tin. Thông tin này có thể bao gồm họ tên, địa chỉ email, số điện thoại, địa chỉ thanh toán và thông tin thanh toán.</p>
                  <p>Chúng tôi cũng tự động thu thập một số thông tin kỹ thuật khi bạn truy cập website như địa chỉ IP, loại trình duyệt, trang giới thiệu và thời gian truy cập thông qua cookies và các công nghệ tương tự.</p>

                  <h3 style="margin-top:40px;margin-bottom:12px;color:#1a1a1a">2. Cách chúng tôi sử dụng thông tin</h3>
                  <p>Thông tin thu thập được sử dụng để:</p>
                  <ul style="padding-left:20px;margin-bottom:16px">
                     <li style="margin-bottom:8px">Xử lý và quản lý đặt phòng, đơn hàng của bạn.</li>
                     <li style="margin-bottom:8px">Gửi xác nhận đặt phòng và thông báo liên quan đến dịch vụ.</li>
                     <li style="margin-bottom:8px">Gửi bản tin và ưu đãi độc quyền nếu bạn đã đăng ký.</li>
                     <li style="margin-bottom:8px">Cải thiện chất lượng dịch vụ và trải nghiệm người dùng.</li>
                     <li style="margin-bottom:8px">Tuân thủ các nghĩa vụ pháp lý.</li>
                  </ul>

                  <h3 style="margin-top:40px;margin-bottom:12px;color:#1a1a1a">3. Chia sẻ thông tin</h3>
                  <p>LuxeStay không bán, trao đổi hoặc chuyển giao thông tin cá nhân của bạn cho bên thứ ba vì mục đích thương mại. Chúng tôi chỉ chia sẻ thông tin trong các trường hợp:</p>
                  <ul style="padding-left:20px;margin-bottom:16px">
                     <li style="margin-bottom:8px">Với các đối tác dịch vụ hỗ trợ vận hành (xử lý thanh toán, gửi email) với cam kết bảo mật.</li>
                     <li style="margin-bottom:8px">Khi được yêu cầu bởi pháp luật hoặc cơ quan có thẩm quyền.</li>
                     <li style="margin-bottom:8px">Để bảo vệ quyền lợi và tài sản của LuxeStay trong các tình huống khẩn cấp.</li>
                  </ul>

                  <h3 style="margin-top:40px;margin-bottom:12px;color:#1a1a1a">4. Bảo mật dữ liệu</h3>
                  <p>Chúng tôi áp dụng các biện pháp bảo mật kỹ thuật và quản lý phù hợp để bảo vệ thông tin của bạn khỏi truy cập trái phép, thay đổi, tiết lộ hoặc phá hủy. Tuy nhiên, không có phương thức truyền tải qua Internet nào là an toàn tuyệt đối.</p>

                  <h3 style="margin-top:40px;margin-bottom:12px;color:#1a1a1a">5. Cookies</h3>
                  <p>Website sử dụng cookies để cải thiện trải nghiệm người dùng, ghi nhớ phiên đăng nhập và phân tích lưu lượng truy cập. Bạn có thể tắt cookies trong cài đặt trình duyệt, nhưng điều này có thể ảnh hưởng đến một số tính năng của website.</p>

                  <h3 style="margin-top:40px;margin-bottom:12px;color:#1a1a1a">6. Quyền của bạn</h3>
                  <p>Bạn có quyền:</p>
                  <ul style="padding-left:20px;margin-bottom:16px">
                     <li style="margin-bottom:8px">Yêu cầu truy cập, chỉnh sửa hoặc xóa thông tin cá nhân của mình.</li>
                     <li style="margin-bottom:8px">Hủy đăng ký nhận bản tin bất kỳ lúc nào.</li>
                     <li style="margin-bottom:8px">Phản đối việc xử lý thông tin của bạn trong các trường hợp nhất định.</li>
                  </ul>
                  <p>Để thực hiện các quyền này, vui lòng liên hệ với chúng tôi qua email: <a href="mailto:info@luxestay.com" style="color:#1a1a1a;text-decoration:underline">info@luxestay.com</a></p>

                  <h3 style="margin-top:40px;margin-bottom:12px;color:#1a1a1a">7. Thay đổi chính sách</h3>
                  <p>LuxeStay có thể cập nhật chính sách bảo mật này theo thời gian. Mọi thay đổi sẽ được đăng tải trên trang này với ngày cập nhật mới nhất. Chúng tôi khuyến khích bạn định kỳ xem lại trang này.</p>

                  <h3 style="margin-top:40px;margin-bottom:12px;color:#1a1a1a">8. Liên hệ</h3>
                  <p>Nếu bạn có bất kỳ câu hỏi nào về chính sách bảo mật này, vui lòng liên hệ:</p>
                  <ul style="padding-left:20px;margin-bottom:16px;list-style:none">
                     <li style="margin-bottom:6px"><strong>LuxeStay</strong></li>
                     <li style="margin-bottom:6px">222 West 56th Street, New York, NY 10019</li>
                     <li style="margin-bottom:6px">Email: <a href="mailto:info@luxestay.com" style="color:#1a1a1a;text-decoration:underline">info@luxestay.com</a></li>
                     <li style="margin-bottom:6px">Điện thoại: +1 502 6251 7802</li>
                  </ul>

                  <p style="margin-top:48px;color:#999;font-size:14px"><em>Cập nhật lần cuối: {{ date('d/m/Y') }}</em></p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Privacy Policy Content End -->
@endsection
