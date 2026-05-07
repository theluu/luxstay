@extends('layouts.app')
@section('title', 'Blog – LuxeStay')
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
                  <h1 class="sisf-m-title text-center entry-title">Blogs</h1>
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
                     <div class="sisf-blog">
                        @forelse($posts as $post)
                        <div class="sisf-blog-item">
                           <div class="sisf-e-inner">
                              <div class="sisf-e-media position-relative">
                                 <div class="sisf-e-media-image">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                       <figure class="image-anime reveal">
                                          <img src="{{ $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('images/blog-list_1.png') }}" class="image-fluid" alt="LuxeStay">
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
                                       <a class="sisf-e-title-link" href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h2>
                                    <p class="sisf-e-excerpt">{{ $post->excerpt }}</p>
                                 </div>
                                 <div class="sisf-m-button mt-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn-default btn-secondary"><span>VIEW MORE <i class="fa-solid fa-arrow-right"></i></span></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @empty
                        <p class="text-center">No posts found.</p>
                        @endforelse
                        {{ $posts->links() }}
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
                                 @foreach($categories as $category)
                                 <li class="product-categories-list-item">
                                    <a href="{{ route('blog.index') }}">
                                    <span>{{ $category->name }}</span>
                                    </a>
                                 </li>
                                 @endforeach
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
                                 @foreach($posts->take(3) as $recentPost)
                                 <li>
                                    <div class="sisf-image">
                                       <a href="{{ route('blog.show', $recentPost->slug) }}"><img src="{{ $recentPost->thumbnail ? asset('storage/' . $recentPost->thumbnail) : asset('images/blog-list_1.png') }}" class="image-fluid" alt="LuxeStay"></a>
                                    </div>
                                    <div class="sisf-blog-content">
                                       <h5 class="sisf-blog-title"><a href="{{ route('blog.show', $recentPost->slug) }}">{{ $recentPost->title }}</a></h5>
                                       <div class="sisf-date">
                                          <span class="publish-date text-uppercase sisf-e-colored">{{ $recentPost->published_at->format('F d, Y') }}</span>
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
                              <a class="sisf-author-info-image mb-3 d-block" href="{{ route('blog.index') }}">
                              <img src="{{ asset('images/default_user.png') }}" class="image-fluid" alt="LuxeStay">
                              </a>
                              <h4 class="sisf-author-info-name mb-1"><a href="{{ route('blog.index') }}"><span class="fn">Luis Peter</span></a></h4>
                              <p class="sisf-author-info-description mb-0">Adventure Seeker</p>
                           </div>
                           <div class="sisf-social-links-widget my-4">
                              <ul class="sisf-social-list d-flex justify-content-between">
                                 <li class="sisf-social-icon mb-0">
                                    <a href="{{ route('blog.index') }}"><i class="fa-brands fa-facebook"></i></a>
                                 </li>
                                 <li class="sisf-social-icon mb-0">
                                    <a href="{{ route('blog.index') }}"><i class="fa-brands fa-x-twitter"></i></a>
                                 </li>
                                 <li class="sisf-social-icon mb-0">
                                    <a href="{{ route('blog.index') }}"><i class="fa-brands fa-linkedin"></i></a>
                                 </li>
                                 <li class="sisf-social-icon mb-0">
                                    <a href="{{ route('blog.index') }}"><i class="fa-brands fa-instagram"></i></a>
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
