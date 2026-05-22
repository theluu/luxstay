<footer class="main-footer">
   <div class="sisf-page-footer-inner-area">
      <div class="sisf-page-footer-top-area">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-7">
                  <div class="footer-top-heading">
                     <h3>{{ __('footer.cta_heading') }}</h3>
                  </div>
               </div>
               <div class="col-md-3">
                  <p class="sisf-e-title mb-0">
                     <a href="tel:+150262517802">
                     <span class="sisf-icon-simple-line-icons icon-screen-smartphone sisf-icon sisf-e"></span>
                     +1 502 6251 7802</a>
                  </p>
                  <p class="sisf-e-title mb-0">
                     <a href="mailto:info@luxestay.com">
                     <span class="sisf-icon-simple-line-icons icon-envelope-open sisf-icon sisf-e"></span>
                     info@luxestay.com</a>
                  </p>
               </div>
               <div class="col-md-2 d-flex justify-content-end">
                  <div class="contact-button">
                     <a href="{{ route('contact') }}" class="sisf-m footer-btn">
                     <span class="text-uppercase">{{ __('footer.contact_us') }}</span>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="sisf-page-footer-middle-area comman-bg">
         <div class="container">
            <div class="row">
               <div class="col-md-7 ms-auto me-auto">
                  <h5 class="subscription text-center">{{ __('footer.newsletter_title') }}</h5>
                  <div class="subscription-container text-center wow fadeInUp">
                     <form id="newsletterForm" class="d-flex justify-content-center align-items-center">
                        @csrf
                        <input type="email" id="newsletterEmail" class="form-control" placeholder="{{ __('footer.newsletter_email') }}" required>
                        <button type="submit" id="newsletterBtn" class="btn btn-link ms-2">{{ __('footer.newsletter_btn') }}</button>
                     </form>
                     <div id="newsletterMsg" style="display:none;margin-top:12px;font-size:14px"></div>
                  </div>
               </div>
               <div class="footer-social-links d-flex align-items-center justify-content-center wow fadeInUp">
                  <ul class="mb-0">
                     <li><a href="{{ $siteSettings['facebook_url'] ?? '#' }}"><i class="fa-brands fa-facebook"></i></a></li>
                     <li><a href="{{ $siteSettings['instagram_url'] ?? '#' }}"><i class="fa-brands fa-instagram"></i></a></li>
                     <li><a href="{{ $siteSettings['linkedin_url'] ?? '#' }}"><i class="fa-brands fa-linkedin"></i></a></li>
                     <li><a href="{{ $siteSettings['twitter_url'] ?? '#' }}"><i class="fa-brands fa-x-twitter"></i></a></li>
                  </ul>
               </div>
               @php
                  $footerMenuRaw   = json_decode($siteSettings['footer_menu'] ?? '[]', true);
                  $footerMenuItems = (is_array($footerMenuRaw) && $footerMenuRaw) ? $footerMenuRaw : [
                     ['label' => __('nav.home'),    'url' => '/',        'children' => []],
                     ['label' => __('nav.about'),   'url' => '/about',   'children' => []],
                     ['label' => __('nav.rooms'),   'url' => '/rooms',   'children' => []],
                     ['label' => __('nav.shop'),    'url' => '/shop',    'children' => []],
                     ['label' => __('nav.blog'),    'url' => '/blog',    'children' => []],
                     ['label' => __('nav.contact'), 'url' => '/contact', 'children' => []],
                  ];
               @endphp
               <div class="menu-footer-menu-container d-flex align-items-center justify-content-center wow fadeInUp">
                  <ul class="menu list-unstyled p-0 mb-0 d-flex align-items-center">
                     @foreach($footerMenuItems as $footerItem)
                        @if(empty($footerItem['children']))
                           <li class="menu-item text-uppercase">
                              <a href="{{ localizedUrl($footerItem['url']) }}">{{ $footerItem['label'] }}</a>
                           </li>
                        @else
                           <li class="menu-item text-uppercase submenu">
                              <a href="{{ localizedUrl($footerItem['url']) }}">{{ $footerItem['label'] }} <i class="fas fa-chevron-down" style="font-size:10px;margin-left:4px"></i></a>
                              <ul class="sub-menu">
                                 @foreach($footerItem['children'] as $footerChild)
                                    <li class="menu-item"><a href="{{ localizedUrl($footerChild['url']) }}">{{ $footerChild['label'] }}</a></li>
                                 @endforeach
                              </ul>
                           </li>
                        @endif
                     @endforeach
                  </ul>
               </div>
               <div class="gallery-container gallery-items">
                  @php
                     $footerGallery = json_decode($siteSettings['footer_gallery'] ?? '[]', true);
                     $footerGallery = is_array($footerGallery) && $footerGallery ? $footerGallery : [
                        'images/footer_img1.png',
                        'images/footer_img2.png',
                        'images/footer_img3.png',
                        'images/footer_img4.png',
                        'images/footer_img5.png',
                     ];
                     $footerMediaUrl = fn ($path) => preg_match('/^https?:\/\//', $path ?? '') ? $path : asset($path ?? '');
                  @endphp
                  @foreach($footerGallery as $img)
                  <div class="gallery-item single-center">
                     <div class="wow fadeInUp">
                        <a href="{{ $footerMediaUrl($img) }}">
                           <figure><img src="{{ $footerMediaUrl($img) }}" alt="LuxeStay"></figure>
                        </a>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
      <div class="sisf-page-footer-bottom-area">
         <div class="container">
            <div class="footer-copyright">
               <div class="row align-items-center">
                  <div class="col-lg-6 col-md-6">
                     <div class="footer-copyright-text wow fadeInUp">
                        <p class="text-black">&copy; {{ __('footer.copyright', ['year' => date('Y')]) }}</p>
                     </div>
                  </div>
                  <div class="col-lg-6 col-md-6">
                     <div class="footer-privacy-policy wow fadeInUp">
                        <ul><li><a href="{{ route('privacy') }}" class="text-black">Chính sách bảo mật</a></li></ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</footer>
<!-- Back to Top -->
<div class="back-to-top-button">
   <button class="back-to-top" id="backToTop">
   <span class="mt-1"><span class="icon-arrow-up"></span></span>
   </button>
</div>
<!-- JS -->
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/validator.min.js') }}"></script>
<script src="{{ asset('js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/SmoothScroll.js') }}"></script>
<script src="{{ asset('js/parallaxie.js') }}"></script>
<script src="{{ asset('js/gsap.min.js') }}"></script>
<script src="{{ asset('js/magiccursor.js') }}"></script>
<script src="{{ asset('js/SplitText.js') }}"></script>
<script src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
<script src="{{ asset('js/jquery.mb.YTPlayer.min.js') }}"></script>
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/wow.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/ripple.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('js/function.js') }}"></script>
@stack('scripts')
<script>
$(function () {
    $('#newsletterForm').on('submit', function (e) {
        e.preventDefault();
        var $btn = $('#newsletterBtn');
        var $msg = $('#newsletterMsg');
        var email = $('#newsletterEmail').val().trim();
        $btn.prop('disabled', true).text('Đang gửi...');

        function doPost(token) {
            $.ajax({
                url: '{{ route('subscribe') }}',
                method: 'POST',
                data: { email: email, _token: $('input[name=_token]').val(), recaptcha_token: token || '' },
                success: function (res) {
                    $msg.css({ color: '#2d6a4f', display: 'block' }).text(res.message);
                    $('#newsletterEmail').val('');
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                    $msg.css({ color: '#c0392b', display: 'block' }).text(msg);
                },
                complete: function () {
                    $btn.prop('disabled', false).text('ĐĂNG KÝ');
                }
            });
        }

        @if($recaptchaEnabled && $recaptchaSiteKey)
        if (typeof grecaptcha !== 'undefined' && window.__recaptchaSiteKey) {
            grecaptcha.ready(function () {
                grecaptcha.execute(window.__recaptchaSiteKey, { action: 'subscribe' }).then(function (token) {
                    doPost(token);
                });
            });
        } else {
            doPost('');
        }
        @else
        doPost('');
        @endif
    });
});
</script>
