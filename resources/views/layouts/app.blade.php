<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="@yield('meta_description', 'LuxeStay – Luxury Hotel & Resort Booking')">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>@yield('title', 'LuxeStay – Luxury Hotel & Resort Booking')</title>
   @if(!empty($siteSettings['favicon']))
   <link rel="icon" type="image/png" href="{{ asset($siteSettings['favicon']) }}">
   @else
   <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
   @endif
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
   <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-line-icons/css/simple-line-icons.css">
   <link href="{{ asset('css/slicknav.min.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
   <link href="{{ asset('css/all.css') }}" rel="stylesheet">
   <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
   <link rel="stylesheet" href="{{ asset('css/plyr.css') }}">
   <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
   <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
   @stack('styles')
</head>
<body>
   <x-header />
   @if(session('success'))
      <div class="alert-flash alert-success-flash" style="position:fixed;top:80px;right:20px;z-index:9999;background:#1a1a1a;color:#fff;padding:14px 24px;border-radius:8px;font-size:14px;box-shadow:0 4px 20px rgba(0,0,0,.25);animation:slideIn .3s ease">
         {{ session('success') }}
      </div>
      <script>setTimeout(()=>document.querySelector('.alert-flash')?.remove(),3500)</script>
   @endif
   @if(session('error'))
      <div class="alert-flash" style="position:fixed;top:80px;right:20px;z-index:9999;background:#c0392b;color:#fff;padding:14px 24px;border-radius:8px;font-size:14px;box-shadow:0 4px 20px rgba(0,0,0,.25)">
         {{ session('error') }}
      </div>
      <script>setTimeout(()=>document.querySelector('.alert-flash')?.remove(),4000)</script>
   @endif
   @yield('content')
   <x-footer />

   {{-- Search Overlay --}}
   <div id="search-overlay" class="search-overlay">
      <div class="search-overlay-inner">
         <button id="search-close" class="search-close-btn" type="button" aria-label="Close search">
            <i class="fa-solid fa-xmark"></i>
         </button>
         <form action="{{ route('search') }}" method="GET" class="search-overlay-form">
            <div class="search-overlay-input-wrap">
               <i class="fa-solid fa-magnifying-glass search-overlay-icon"></i>
               <input type="text" name="q" id="search-overlay-input"
                  value="{{ request('q') }}"
                  placeholder="Tìm kiếm phòng, blog, cửa hàng…"
                  autocomplete="off">
            </div>
            <p class="search-overlay-hint">Nhấn Enter để tìm kiếm</p>
         </form>
      </div>
   </div>

   <style>
      /* Search toggle button */
      .search-toggle-btn {
         background: none;
         border: none;
         color: #fff;
         font-size: 18px;
         cursor: pointer;
         padding: 4px 8px;
         opacity: .85;
         transition: opacity .2s;
         line-height: 1;
      }
      .search-toggle-btn:hover { opacity: 1; }

      /* Overlay */
      .search-overlay {
         position: fixed;
         inset: 0;
         background: rgba(10,10,10,.92);
         z-index: 99999;
         display: flex;
         align-items: center;
         justify-content: center;
         opacity: 0;
         visibility: hidden;
         transition: opacity .3s ease, visibility .3s ease;
      }
      .search-overlay.is-open {
         opacity: 1;
         visibility: visible;
      }
      .search-overlay-inner {
         width: 100%;
         max-width: 680px;
         padding: 0 24px;
         position: relative;
      }
      .search-close-btn {
         position: absolute;
         top: -56px;
         right: 24px;
         background: none;
         border: none;
         color: rgba(255,255,255,.6);
         font-size: 28px;
         cursor: pointer;
         transition: color .2s;
         line-height: 1;
      }
      .search-close-btn:hover { color: #fff; }
      .search-overlay-form { width: 100%; }
      .search-overlay-input-wrap {
         display: flex;
         align-items: center;
         border-bottom: 2px solid rgba(255,255,255,.25);
         padding-bottom: 12px;
         transition: border-color .2s;
      }
      .search-overlay-input-wrap:focus-within {
         border-bottom-color: rgba(255,255,255,.8);
      }
      .search-overlay-icon {
         color: rgba(255,255,255,.5);
         font-size: 22px;
         margin-right: 16px;
         flex-shrink: 0;
      }
      .search-overlay-input-wrap input {
         flex: 1;
         background: none;
         border: none;
         outline: none;
         color: #fff;
         font-size: clamp(22px, 4vw, 36px);
         font-family: var(--heading-font, inherit);
         font-weight: 300;
         letter-spacing: .5px;
      }
      .search-overlay-input-wrap input::placeholder {
         color: rgba(255,255,255,.3);
      }
      .search-overlay-hint {
         margin-top: 14px;
         color: rgba(255,255,255,.3);
         font-size: 13px;
         letter-spacing: .5px;
      }
   </style>

   @if($recaptchaEnabled && $recaptchaSiteKey)
   {{-- reCAPTCHA v3: load script --}}
   <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}" async defer></script>
   <script>
   (function () {
      var SITE_KEY = '{{ $recaptchaSiteKey }}';
      function attachRecaptcha(form) {
         form.addEventListener('submit', function (e) {
            var existing = form.querySelector('input[name="recaptcha_token"]');
            if (existing && existing.value) return; // already has token, let it through
            e.preventDefault();
            var action = form.dataset.recaptchaAction || 'submit';
            grecaptcha.ready(function () {
               grecaptcha.execute(SITE_KEY, { action: action }).then(function (token) {
                  if (!existing) {
                     existing = document.createElement('input');
                     existing.type = 'hidden';
                     existing.name = 'recaptcha_token';
                     form.appendChild(existing);
                  }
                  existing.value = token;
                  form.submit();
               });
            });
         });
      }
      document.addEventListener('DOMContentLoaded', function () {
         document.querySelectorAll('form[data-recaptcha]').forEach(attachRecaptcha);
      });
      window.__recaptchaSiteKey = SITE_KEY;
   })();
   </script>
   @endif

   <script>
      (function () {
         var overlay = document.getElementById('search-overlay');
         var input   = document.getElementById('search-overlay-input');
         var toggle  = document.getElementById('search-toggle');
         var close   = document.getElementById('search-close');

         function open()  { overlay.classList.add('is-open');    document.body.style.overflow='hidden'; setTimeout(function(){input.focus();},100); }
         function shut()  { overlay.classList.remove('is-open'); document.body.style.overflow=''; }

         toggle && toggle.addEventListener('click', open);
         close  && close.addEventListener('click', shut);
         overlay && overlay.addEventListener('click', function(e){ if(e.target===overlay) shut(); });
         document.addEventListener('keydown', function(e){ if(e.key==='Escape') shut(); });
      })();
   </script>
</body>
</html>
