<header id="sisf-page-header" class="sisf-main-header">
   <div class="header-top">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="header_col">
                  <div class="sisf-widget-holder sisf--left">
                     <div class="sisf-icon-list-item sisf-icon--icon-pack">
                        <a href="tel:6176232338" target="_self">
                        <span class="sisf-e-title-inner">
                        <span class="sisf-e-title-text"><span class="sisf-icon-simple-line-icons icon-call-out sisf-icon sisf-e me-1"></span> Call us: (617) 623-2338</span>
                        </span>
                        </a>
                     </div>
                  </div>
                  <div class="header-center-icons">
                     <a class="me-3"><i class="fa-brands fa-facebook"></i></a>
                     <a class="me-3"><i class="fa-brands fa-instagram"></i></a>
                     <a class="me-3"><i class="fa-brands fa-linkedin"></i></a>
                     <a class="me-3"><i class="fa-brands fa-x-twitter"></i></a>
                  </div>
                  <div class="mail-us">
                     <a href="mailto:info@luxestay.com"><span class="sisf-e-title-text"><span class="sisf-icon-simple-line-icons icon-envelope-open sisf-icon sisf-e me-2"></span> Mail us : info@luxestay.com</span></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="sisf-page-header-inner" class="sisf-skin--light sisf-page position-relative">
      <div class="container-fluid">
         <div class="sisf-divided-header-left-wrapper d-flex align-items-center justify-content-between">
            <div class="sisf-icon-list-item">
               <div class="input-group">
                  <span class="pt-1 text-white border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                  <input type="text" class="form-control text-white shadow-none border-0" placeholder="Search...">
               </div>
            </div>
            <nav class="navbar navbar-expand-lg">
               <div class="collapse navbar-collapse main-menu">
                  <div class="nav-menu-wrapper">
                     <ul class="navbar-nav mr-auto" id="menu">
                        <li class="nav-item submenu mega-menu-item">
                           <a class="nav-link" href="{{ route('home') }}">Home<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu mega-menu">
                              <li class="nav-item">
                                 <a href="{{ route('home') }}" class="page-link"><img src="{{ asset('images/mega-menu_home_page1.png') }}" class="w-100" alt="LuxeStay"></a>
                                 <a class="nav-link" href="{{ route('home') }}">Mountain Home Page</a>
                              </li>
                           </ul>
                        </li>
                        <li class="nav-item submenu">
                           <a class="nav-link" href="{{ route('rooms.index') }}">Rooms<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              <li class="nav-item"><a class="nav-link" href="{{ route('rooms.suites') }}">Rooms & Suits</a></li>
                           </ul>
                        </li>
                        <li class="nav-item submenu">
                           <a class="nav-link" href="#">Pages<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('offers') }}">Offers & Promotions</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'restaurant') }}">Restaurant</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                           </ul>
                        </li>
                        <li class="nav-item submenu mega-menu-item">
                           <a class="nav-link" href="{{ route('shop.index') }}">Shop<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu mega-menu mega-menu1">
                              <li class="nav-item">
                                 <h5 class="sisf-menu-title">Shop Related Pages</h5>
                                 <a class="nav-link" href="{{ route('shop.index') }}">Shop Main</a>
                                 <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                                 <a class="nav-link" href="{{ route('checkout.index') }}">Checkout</a>
                                 <a class="nav-link" href="{{ route('account.index') }}">My account</a>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </div>
               </div>
            </nav>
         </div>
         <!-- Logo -->
         <a class="navbar-brand sisf-header-logo-link mobile-none" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="LuxeStay">
         </a>
         <a class="navbar-brand sisf-header-logo-link mobile-block" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="LuxeStay">
         </a>
         <div class="sisf-divided-header-right-wrapper justify-content-between d-flex align-items-center">
            <nav class="navbar navbar-expand-lg">
               <div class="collapse navbar-collapse main-menu">
                  <div class="nav-menu-wrapper">
                     <ul class="navbar-nav mr-auto" id="menu2">
                        <li class="nav-item submenu">
                           <a class="nav-link" href="#">Activities<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'event-wedding') }}">Event & Wedding</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'fitness-and-wellness') }}">Fitness and Wellness</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'golf-courses') }}">Golf Courses</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'hiking-and-trekking') }}">Hiking and Trekking</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'leisure-and-entertainment') }}">Leisure and Entertainment</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'nature-and-exploration') }}">Nature and Exploration</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'ski-snowboarding') }}">Ski & Snowboarding</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'spa-wellness') }}">Spa & Wellness</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'unique-experiences') }}">Unique Experiences</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'water-sports') }}">Water Sports</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'winter-hiking') }}">Winter Hiking</a></li>
                           </ul>
                        </li>
                        <li class="nav-item submenu">
                           <a class="nav-link" href="{{ route('blog.index') }}">Blogs<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              <li class="nav-item"><a class="nav-link" href="{{ route('blog.index') }}">Blog</a></li>
                           </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}">Landing</a></li>
                     </ul>
                  </div>
               </div>
            </nav>
            <div class="sisf-widget-holder sisf--two d-flex align-items-center">
               <div class="header-btn">
                  @auth
                     <a href="{{ route('account.index') }}" class="sisf-button sisf-layout--outlined">My Account</a>
                  @else
                     <a href="{{ route('login') }}" class="sisf-button sisf-layout--outlined">Book Now</a>
                  @endauth
               </div>
            </div>
         </div>
         <div class="navbar-toggle"></div>
         <div class="responsive-menu"></div>
      </div>
   </div>
</header>
