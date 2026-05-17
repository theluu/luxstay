<header id="sisf-page-header" class="sisf-main-header">
   <div class="header-top">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="header_col">
                  <div class="sisf-widget-holder sisf--left">
                     <div class="sisf-icon-list-item sisf-icon--icon-pack">
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $siteSettings['phone'] ?? '6176232338') }}" target="_self">
                        <span class="sisf-e-title-inner">
                        <span class="sisf-e-title-text"><span class="sisf-icon-simple-line-icons icon-call-out sisf-icon sisf-e me-1"></span> Gọi cho chúng tôi: {{ $siteSettings['phone'] ?? '(617) 623-2338' }}</span>
                        </span>
                        </a>
                     </div>
                  </div>
                  <div class="header-center-icons">
                     @if(!empty($siteSettings['facebook_url']))
                        <a href="{{ $siteSettings['facebook_url'] }}" target="_blank" class="me-3"><i class="fa-brands fa-facebook"></i></a>
                     @endif
                     @if(!empty($siteSettings['instagram_url']))
                        <a href="{{ $siteSettings['instagram_url'] }}" target="_blank" class="me-3"><i class="fa-brands fa-instagram"></i></a>
                     @endif
                     @if(!empty($siteSettings['linkedin_url']))
                        <a href="{{ $siteSettings['linkedin_url'] }}" target="_blank" class="me-3"><i class="fa-brands fa-linkedin"></i></a>
                     @endif
                     @if(!empty($siteSettings['twitter_url']))
                        <a href="{{ $siteSettings['twitter_url'] }}" target="_blank" class="me-3"><i class="fa-brands fa-x-twitter"></i></a>
                     @endif
                  </div>
                  <div class="mail-us">
                     <a href="mailto:{{ $siteSettings['email'] ?? 'info@luxestay.com' }}">
                        <span class="sisf-e-title-text"><span class="sisf-icon-simple-line-icons icon-envelope-open sisf-icon sisf-e me-2"></span> Email cho chúng tôi: {{ $siteSettings['email'] ?? 'info@luxestay.com' }}</span>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="sisf-page-header-inner" class="sisf-skin--light sisf-page position-relative">
      <div class="container-fluid">
         <div class="sisf-divided-header-left-wrapper d-flex align-items-center justify-content-between">
            {{-- Search --}}
            <div class="sisf-icon-list-item">
               <button id="search-toggle" class="search-toggle-btn" type="button" aria-label="Search">
                  <i class="fa-solid fa-magnifying-glass"></i>
               </button>
            </div>
            {{-- Left nav (dynamic from DB) --}}
            <nav class="navbar navbar-expand-lg">
               <div class="collapse navbar-collapse main-menu">
                  <div class="nav-menu-wrapper">
                     <ul class="navbar-nav mr-auto" id="menu">
                        @foreach($navItems as $item)
                           @if(empty($item['children']))
                              <li class="nav-item me-3">
                                 <a class="nav-link" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                              </li>
                           @else
                              <li class="nav-item submenu">
                                 <a class="nav-link" href="{{ $item['url'] }}">{{ $item['label'] }}<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                                 <ul class="sub-menu">
                                    @foreach($item['children'] as $child)
                                       <li class="nav-item"><a class="nav-link" href="{{ $child['url'] }}">{{ $child['label'] }}</a></li>
                                    @endforeach
                                 </ul>
                              </li>
                           @endif
                        @endforeach
                     </ul>
                  </div>
               </div>
            </nav>
         </div>
         <!-- Logo -->
         <a class="navbar-brand sisf-header-logo-link mobile-none" href="{{ route('home') }}">
            <img src="{{ asset($siteSettings['logo'] ?? 'images/logo.png') }}" alt="{{ $siteSettings['site_name'] ?? 'LuxeStay' }}">
         </a>
         <a class="navbar-brand sisf-header-logo-link mobile-block" href="{{ route('home') }}">
            <img src="{{ asset($siteSettings['logo'] ?? 'images/logo.png') }}" alt="{{ $siteSettings['site_name'] ?? 'LuxeStay' }}">
         </a>
         <div class="sisf-divided-header-right-wrapper justify-content-between d-flex align-items-center">
            {{-- Right nav: Activities (dynamic from DB) --}}
            <nav class="navbar navbar-expand-lg">
               <div class="collapse navbar-collapse main-menu">
                  <div class="nav-menu-wrapper">
                     <ul class="navbar-nav mr-auto" id="menu2">
                        <li class="nav-item submenu">
                           <a class="nav-link" href="#">Hoạt động<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              @foreach($navActivities as $activity)
                              <li class="nav-item"><a class="nav-link" href="{{ route('activity.show', $activity->slug) }}">{{ $activity->title }}</a></li>
                              @endforeach
                           </ul>
                        </li>
                        <li class="nav-item me-3">
                           <a class="nav-link" href="{{ route('blog.index') }}">Blog</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="{{ route('landing') }}">Trang đích</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </nav>
            <div class="sisf-widget-holder sisf--two d-flex align-items-center gap-3">
               <a href="{{ route('cart.index') }}" class="position-relative text-white" style="font-size:18px">
                  <i class="fa-solid fa-cart-shopping"></i>
                  @if($cartItemCount > 0)
                     <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px">
                        {{ $cartItemCount }}
                     </span>
                  @endif
               </a>
               <div class="header-btn">
                  @auth
                     <a href="{{ route('account.index') }}" class="sisf-button sisf-layout--outlined">Tài khoản của tôi</a>
                  @else
                     <a href="{{ route('rooms.index') }}" class="sisf-button sisf-layout--outlined">Đặt ngay</a>
                  @endauth
               </div>
            </div>
         </div>
         <div class="navbar-toggle"></div>
         <div class="responsive-menu"></div>
      </div>
   </div>
</header>
