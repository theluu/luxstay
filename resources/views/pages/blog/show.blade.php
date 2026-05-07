@extends('layouts.app')
@section('title', $post->title . ' – LuxeStay')
@section('content')
      <!-- Banner Section Start -->
      <div class="sisf-banner position-relative">
         <div class="banner-img">
            <figure>
               <img src="{{ asset('images/title-banner.png') }}" alt="Luxestay">
            </figure>
         </div>
         <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
            <div class="sisf-m-inner">
               <div class="sisf-m-content sisf-content-grid ">
                  <h1 class="sisf-m-title text-center entry-title">{{ $post->title }}</h1>
               </div>
            </div>
         </div>
      </div>
      <!-- Banner Section End -->
      <!-- Page Section Start -->
      <div class="sisf-page-section section">
         <div class="sisf-grid sisf-layout--template">
            <div class="sisf-grid-inner container">
               <div class="row">
                  <div class="col-lg-9">
                     <div class="sisf-blog sisf-blog-single">
                        <div class="sisf-blog-item">
                           <div class="sisf-e-inner">
                              <div class="sisf-e-media position-relative">
                                 <div class="sisf-e-media-image">
                                    <a href="#">
                                       <figure class="image-anime reveal">
                                          <img src="{{ $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('images/blog-author-bg.png') }}" class="image-fluid" alt="LuxeStay">
                                       </figure>
                                    </a>
                                 </div>
                                 <div class="sisf-e-info sisf-e-content sisf-info--top sisf-info--top-holder">
                                    <div class="sisf-e-info-date">
                                       <a href="{{ route('blog.index') }}" class="text-uppercase">{{ $post->published_at->format('d M, Y') }}</a>
                                    </div>
                                    <span class="sisf-e-info-divider"></span>
                                    <div class="sisf-e-info-category">
                                       <a href="{{ route('blog.index') }}" class="text-uppercase">{{ $post->category->name }}</a>
                                    </div>
                                 </div>
                              </div>
                              <div class="sisf-e-content">
                                 <div class="sisf-e-text">
                                    <h2 class="sisf-e-title">
                                       <a class="sisf-e-title--link" href="#">{{ $post->title }}</a>
                                    </h2>
                                    {!! $post->content !!}
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <figure class="image-anime reveal">
                                          <img src="{{ asset('images/capturing-img1.png') }}" class="image-fluid" alt="LuxeStay">
                                       </figure>
                                    </div>
                                    <div class="col-md-8">
                                       <div class="sisf-m-icon-wrapper">
                                          <div class="sisf-m-icon-holder">
                                             <div class="sisf-e-qoute-background-text sisf-e-colored fa-solid fa-quote-left"></div>
                                          </div>
                                          <div class="sisf-m-content mt-3">
                                             <h4 class="sisf-m-title mb-3">
                                                <span class="sisf-m-title-text fw-bold">Stay with Peace</span>
                                             </h4>
                                             <div class="sisf-m-text">
                                                <p>A stay in a converted lighthouse might transport you to a bygone era, with panoramic ocean views and the gentle hum of waves crashing against the shore. Alternatively, a night in a modern igloo, complete with transparent walls, could offer a front-row seat to the northern lights, turning an ordinary night into a spectacular display of natural wonder. For those seeking a blend of comfort and adventure, staying in a sleek, eco-friendly cabin in the heart of the wilderness combines cutting-edge design with a deep connection to nature, ensuring a harmonious and rejuvenating retreat.</p>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="sisf-title--with-text mt-3">
                                    <h2 class="sisf-m-title fw-bold mb-3">
                                       "Unique accommodations"
                                    </h2>
                                    <div class="sisf-m-text">
                                       <p>Each of these unique accommodations offers its own story, charm, and sense of adventure, inviting you to step outside the confines of traditional lodging and experience something truly special. Whether it's the thrill of sleeping under a glass dome or the serene escape of a secluded boat house, these stays promise to be more than just a backdrop to your travels—they will be a highlight of your journey, leaving you with cherished memories and a fresh perspective on the world.</p>
                                       <p>Booking well in advance can sometimes secure better rates, especially for popular destinations and peak travel times. Conversely, last-minute bookings can also lead to great deals as hotels may lower prices to fill unsold rooms.</p>
                                    </div>
                                 </div>
                                 <div class="row pt-3 mb-4">
                                    <div class="col-md-6">
                                       <div class="gallery-items page-gallery-box">
                                          <div class="gallery-item box-gallery">
                                             <a href="{{ asset('images/blog-single-gallery1.png') }}">
                                                <figure class="image-anime reveal">
                                                   <img src="{{ asset('images/blog-single-gallery1.png') }}" class="w-100" alt="LuxeStay">
                                                </figure>
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="gallery-items page-gallery-box">
                                          <div class="gallery-item blog--gallery box-gallery">
                                             <a href="{{ asset('images/room_gallery1.png') }}">
                                                <figure class="image-anime reveal">
                                                   <img src="{{ asset('images/room_gallery1.png') }}" class="w-100" alt="LuxeStay">
                                                </figure>
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="gallery-items page-gallery-box mt-3">
                                          <div class="gallery-item box-gallery wow fadeIn">
                                             <a href="{{ asset('images/blog-single-gallery2.png') }}">
                                                <figure class="image-anime reveal">
                                                   <img src="{{ asset('images/blog-single-gallery2.png') }}" class="w-100" alt="LuxeStay">
                                                </figure>
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="sisf-bottom-side">
                              <p>Flavors Around the Globe invites you on an epicurean journey that transcends borders, tantalizing your taste buds with a symphony of global cuisines. From the fiery spices of Indian curries to the delicate subtlety of Japanese sushi, each dish tells a story of culture, tradition, and culinary mastery. Embark on a gastronomic adventure where the vibrant colors of Mexican street food beckon, the rich aromas of Italian pasta linger in the air, and the exotic spices of Moroccan tagines transport you to the bustling markets of Marrakech.</p>
                              <p>A stay in a converted lighthouse might transport you to a bygone era, with panoramic ocean views and the gentle hum of waves crashing against the shore. Alternatively, a night in a modern igloo, complete with transparent walls, could offer a front-row seat to the northern lights, turning an ordinary night into a spectacular display of natural wonder. For those seeking a blend of comfort and adventure, staying in a sleek, eco-friendly cabin in the heart of the wilderness combines cutting-edge design with a deep connection to nature, ensuring a harmonious and rejuvenating retreat.</p>
                           </div>
                        </div>
                        <div class="sisf-e-bottom-holder">
                           <div class="sisf-e-left sisf-e-info">
                              <a href="{{ route('blog.index') }}" class="text-uppercase">{{ $post->category->name }}</a>
                           </div>
                        </div>
                        <div class="sisf-author-info mt-5">
                           <div class="sisf-m-inner align-items-center d-flex">
                              <div class="sisf-m-image">
                                 <figure>
                                    <img src="{{ asset('images/default_user.png') }}" class="image-fluid" alt="Luxestay">
                                 </figure>
                              </div>
                              <div class="sisf-m-content">
                                 <h4 class="sisf-m-author mb-3">
                                    {{ $post->author->name }}
                                 </h4>
                                 <p class="sisf-m-description mb-2">Adventure Seeker</p>
                                 <ul class="sisf-social-list list-unstyled ps-0 d-flex mb-0 text-white justify-content-around">
                                    <li class="sisf-social-icon mb-0 me-4">
                                       <a href="#"><i class="fa-brands fa-facebook-f text-white"></i></a>
                                    </li>
                                    <li class="sisf-social-icon mb-0 me-4">
                                       <a href="#"><i class="fa-brands fa-x-twitter text-white"></i></a>
                                    </li>
                                    <li class="sisf-social-icon mb-0 me-4">
                                       <a href="#"><i class="fa-brands fa-linkedin-in text-white"></i></a>
                                    </li>
                                    <li class="sisf-social-icon mb-0 me-4">
                                       <a href="#"><i class="fa-brands fa-instagram text-white"></i></a>
                                    </li>
                                    <li class="sisf-social-icon mb-0 me-4">
                                       <a href="#"><i class="fa-brands fa-pinterest text-white"></i></a>
                                    </li>
                                    <li class="sisf-social-icon mb-0 me-4">
                                       <a href="#"><i class="fa-brands fa-tumblr text-white"></i></a>
                                    </li>
                                    <li class="sisf-social-icon mb-0 me-4">
                                       <a href="#"><i class="fa-brands fa-vk text-white"></i></a>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="sisf-page-comments mt-5">
                           <div class="comments">
                              <h2 class="reviews-title">Comments (3)</h2>
                              <ul class="commentlist">
                                 <li class="review-list">
                                    <div class="comment_container">
                                       <div class="sisf-e-image mt-1">
                                          <figure>
                                             <img src="{{ asset('images/default_user.png') }}" class="image-fluid" alt="Luxestay">
                                          </figure>
                                       </div>
                                       <div class="comment-text">
                                          <div class="meta d-flex align-items-center mb-2">
                                             <strong class="review-author m-0">Gia Villi</strong>
                                             <span class="review-published-date">July 19, 2024</span>
                                          </div>
                                          <div class="sisf-ratings">
                                             <div class="sisf-m-star sisf--initial">
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="description">
                                       <p>Hotel prices can vary significantly based on the time of year, day of the week, and local events. Being flexible with your travel dates can help you find lower rates.</p>
                                    </div>
                                 </li>
                                 <li class="review-list">
                                    <div class="comment_container">
                                       <div class="sisf-e-image mt-1">
                                          <figure>
                                             <img src="{{ asset('images/default_user.png') }}" class="image-fluid" alt="Luxestay">
                                          </figure>
                                       </div>
                                       <div class="comment-text">
                                          <div class="meta d-flex align-items-center mb-2">
                                             <strong class="review-author m-0">Era Scott</strong>
                                             <span class="review-published-date">July 19, 2024</span>
                                          </div>
                                          <div class="sisf-ratings">
                                             <div class="sisf-m-star sisf--initial">
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="description">
                                       <p>Hotel prices can vary significantly based on the time of year, day of the week, and local events. Being flexible with your travel dates can help you find lower rates.</p>
                                    </div>
                                 </li>
                                 <li class="review-list">
                                    <div class="comment_container">
                                       <div class="sisf-e-image mt-1">
                                          <figure>
                                             <img src="{{ asset('images/default_user.png') }}" class="image-fluid" alt="Luxestay">
                                          </figure>
                                       </div>
                                       <div class="comment-text">
                                          <div class="meta d-flex align-items-center mb-2">
                                             <strong class="review-author m-0">Eva Brayer</strong>
                                             <span class="review-published-date">July 19, 2024</span>
                                          </div>
                                          <div class="sisf-ratings">
                                             <div class="sisf-m-star sisf--initial">
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                                <span class="star sisf-e-colored">★</span>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="description">
                                       <p>Hotel prices can vary significantly based on the time of year, day of the week, and local events. Being flexible with your travel dates can help you find lower rates.</p>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                           <div class="review_form review--form mt-3">
                              <h2 class="comment-reply-title">Leave a Reply</h2>
                              <form id="commentform">
                                 <p class="comment-notes">Your email address will not be published. Required fields are marked
                                    <span class="required">*</span>
                                 </p>
                                 <div class="review_form_box">
                                    <div class="form_box-grid d-flex">
                                       <div class="form_box-item me-4">
                                          <input id="author" name="author" placeholder="Your Name *" type="text" value="" size="30" maxlength="245" required="required">
                                       </div>
                                       <div class="form_box-item">
                                          <input id="email" name="email" placeholder="Email *" type="text" value="" size="30" maxlength="100" required="required">
                                       </div>
                                    </div>
                                    <div class="w-100">
                                       <input id="url" name="url" placeholder="Website" type="text" value="" size="30" maxlength="200">
                                    </div>
                                    <div class="comment-form-comment">
                                       <textarea id="comment" name="comment" placeholder="Your Review *" cols="45" rows="4" maxlength="65525" required="required"></textarea>
                                    </div>
                                    <div class="form-check d-flex align-items-center">
                                       <input class="form-check-input" name="wp-comment-cookies-consent" type="checkbox" value="yes">
                                       <label class="form-check-label ms-2 mt-1">Save my name, and email in this browser for the next time I comment.</label>
                                    </div>
                                    <div class="sisf-m-button mt-4">
                                       <button type="submit" class="btn-default btn-secondary rounded-0"><span>Post Comment</span>
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Blog Sidebar Start -->
                  <div class="col-lg-3">
                     <div class="sisf-page-sidebar">
                        <div class="sidebar-widget widget_search">
                           <div class="sisf-search-form">
                              <div class="sisf-search-form-inner border-bottom-0">
                                 <input type="search" class="sisf-search-form-field " name="s" value="" placeholder="Search…" required="">
                                 <button type="submit" class="sisf-search-form-button">
                                 <i class="fa-solid fa-magnifying-glass"></i>
                                 </button>
                              </div>
                           </div>
                        </div>
                        <div class="sidebar-separator">
                           <hr class="separator sidebar-line">
                        </div>
                        <div class="sidebar-widget widget_categories">
                           <h3 class="sidebar-title">Categories</h3>
                           <div class="product-categories">
                              <ul class="product-categories-list">
                                 <li class="product-categories-list-item">
                                    <a href="{{ route('blog.index') }}">
                                    <span>Best Hotels</span>
                                    </a>
                                 </li>
                                 <li class="product-categories-list-item">
                                    <a href="{{ route('blog.index') }}">
                                    <span>Budget Travel</span>
                                    </a>
                                 </li>
                                 <li class="product-categories-list-item">
                                    <a href="{{ route('blog.index') }}">
                                    <span>Destination Guides</span>
                                    </a>
                                 </li>
                                 <li class="product-categories-list-item">
                                    <a href="{{ route('blog.index') }}">
                                    <span>Travel Tips</span>
                                    </a>
                                 </li>
                                 <li class="product-categories-list-item">
                                    <a href="{{ route('blog.index') }}">
                                    <span>Ultimate Guide</span>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                        <div class="sidebar-separator">
                           <hr class="separator sidebar-line">
                        </div>
                        <div class="sidebar-widget widget_popular_blog">
                           <h3 class="sidebar-title">Latest Blog</h3>
                           <div class="sidebar_content-list">
                              <ul class="content_list_widget">
                                 @foreach($recent as $recentPost)
                                 <li>
                                    <div class="sisf-image">
                                       <a href="{{ route('blog.show', $recentPost->slug) }}"><img src="{{ $recentPost->thumbnail ? asset('storage/' . $recentPost->thumbnail) : asset('images/blog-list_1.png') }}" class="image-fluid" alt="LuxeStay"></a>
                                    </div>
                                    <div class="sisf-blog-content">
                                       <h5 class="sisf-blog-title"><a href="{{ route('blog.show', $recentPost->slug) }}">{{ $recentPost->title }}</a></h5>
                                       <div class="sisf-date">
                                          <span class="publish-date text-uppercase sisf-e-colored">{{ $recentPost->published_at->format('M d, Y') }}</span>
                                       </div>
                                    </div>
                                 </li>
                                 @endforeach
                              </ul>
                           </div>
                        </div>
                        <div class="sidebar-separator">
                           <hr class="separator sidebar-line">
                        </div>
                        <div class="sidebar-widget widget_popular_tag">
                           <h3 class="sidebar-title">Popular Tags</h3>
                           <div class="sidebar_tag-list">
                              <a href="{{ route('blog.index') }}" class="tag">Best Hotels</a>
                              <a href="{{ route('blog.index') }}" class="tag">Booking Tips</a>
                              <a href="{{ route('blog.index') }}" class="tag">Budget Travel</a>
                              <a href="{{ route('blog.index') }}" class="tag">Travel Guide</a>
                              <a href="{{ route('blog.index') }}" class="tag">Ultimate Guide</a>
                           </div>
                        </div>
                        <div class="sidebar-separator">
                           <hr class="separator sidebar-line">
                        </div>
                        <div class="sidebar-widget widget_follow_us">
                           <h3 class="sidebar-title">Follow Us On</h3>
                           <div class="sisf-author-info text-center">
                              <a class="sisf-author-info-image mb-3 d-block" href="#">
                              <img src="{{ asset('images/default_user.png') }}" class="image-fluid" alt="LuxeStay">
                              </a>
                              <h4 class="sisf-author-info-name mb-1"><a href="#"><span class="fn">{{ $post->author->name }}</span></a></h4>
                              <p class="sisf-author-info-description mb-0">Adventure Seeker</p>
                           </div>
                           <div class="sisf-social-links-widget my-4">
                              <ul class="sisf-social-list d-flex justify-content-between">
                                 <li class="sisf-social-icon mb-0">
                                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                                 </li>
                                 <li class="sisf-social-icon mb-0">
                                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                                 </li>
                                 <li class="sisf-social-icon mb-0">
                                    <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                                 </li>
                                 <li class="sisf-social-icon mb-0">
                                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Blog Sidebar End -->
               </div>
            </div>
         </div>
      </div>
      <!-- Page Section End -->
@endsection
