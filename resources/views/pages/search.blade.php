@extends('layouts.app')
@section('title', $q ? "Search: {$q}" : 'Search – LuxeStay')
@section('content')

<div class="sisf-banner position-relative">
   <div class="banner-img">
      <figure><img src="{{ asset('images/title-banner.png') }}" alt="LuxeStay"></figure>
   </div>
   <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
      <div class="sisf-m-inner">
         <div class="sisf-m-content sisf-content-grid">
            <h1 class="sisf-m-title text-center entry-title">Search Results</h1>
         </div>
      </div>
   </div>
</div>

<div class="section">
   <div class="container">

      {{-- Search bar --}}
      <form action="{{ route('search') }}" method="GET" class="mb-5">
         <div class="input-group" style="max-width:560px;margin:0 auto">
            <input type="text" name="q" value="{{ $q }}" class="form-control form-control-lg"
               placeholder="Search rooms, blog, products…" autofocus>
            <button type="submit" class="btn-default px-4">Search</button>
         </div>
      </form>

      @if($q && strlen($q) < 2)
         <p class="text-center text-muted">Please enter at least 2 characters.</p>
      @elseif($q && $total === 0)
         <p class="text-center text-muted">No results found for "<strong>{{ $q }}</strong>".</p>
      @elseif($q)
         <p class="text-center text-muted mb-5">{{ $total }} result{{ $total !== 1 ? 's' : '' }} for "<strong>{{ $q }}</strong>"</p>

         {{-- Rooms --}}
         @if($rooms->count())
         <h3 class="mb-4">Rooms ({{ $rooms->count() }})</h3>
         <div class="row mb-5">
            @foreach($rooms as $room)
            <div class="col-lg-4 col-md-6 mb-4">
               <div class="sisf-room-list-item sisf-room-list--item sisf-item--full">
                  <div class="sisf-e-inner">
                     <div class="sisf-e-media position-relative">
                        <div class="sisf-image-holder">
                           <figure>
                              <img src="{{ $room->thumbnail ? asset($room->thumbnail) : asset('images/room-image-1.png') }}" class="w-100" alt="{{ $room->name }}">
                           </figure>
                        </div>
                        <span class="sisf-e-price">
                           <span class="sisf-e-price-label">From</span>
                           <span class="sisf-e-price-value">${{ number_format($room->price_per_night, 0) }}</span>
                        </span>
                     </div>
                     <div class="sisf-e-content">
                        <h4 class="sisf-e-title entry-title mb-2">
                           <a href="{{ route('rooms.show', $room->slug) }}">{{ $room->name }}</a>
                        </h4>
                        <p class="sisf-e-excerpt mb-2">{{ Str::limit($room->description, 90) }}</p>
                        <a class="sisf-shortcode sisf-text-underline sisf-underline--left" href="{{ route('rooms.show', $room->slug) }}">
                           <span class="sisf-m-text">Explore More</span>
                        </a>
                     </div>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
         @endif

         {{-- Blog Posts --}}
         @if($posts->count())
         <h3 class="mb-4">Blog Posts ({{ $posts->count() }})</h3>
         <div class="row mb-5">
            @foreach($posts as $post)
            <div class="col-lg-4 col-md-6 mb-4">
               <div class="card border-0 shadow-sm h-100">
                  @if($post->thumbnail)
                  <img src="{{ asset($post->thumbnail) }}" class="card-img-top" style="height:200px;object-fit:cover" alt="{{ $post->title }}">
                  @endif
                  <div class="card-body">
                     <h5 class="card-title"><a href="{{ route('blog.show', $post->slug) }}" class="text-dark">{{ $post->title }}</a></h5>
                     <p class="card-text text-muted small">{{ Str::limit($post->excerpt, 100) }}</p>
                     <a href="{{ route('blog.show', $post->slug) }}" class="sisf-shortcode sisf-text-underline sisf-underline--left">
                        <span class="sisf-m-text">Read More</span>
                     </a>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
         @endif

         {{-- Products --}}
         @if($products->count())
         <h3 class="mb-4">Shop Products ({{ $products->count() }})</h3>
         <div class="row mb-5">
            @foreach($products as $product)
            <div class="col-lg-3 col-md-6 mb-4">
               <div class="card border-0 shadow-sm h-100 text-center p-3">
                  <img src="{{ $product->thumbnail ? asset($product->thumbnail) : asset('images/product1.png') }}"
                     class="mx-auto mb-3" style="height:160px;object-fit:cover;width:100%;border-radius:6px" alt="{{ $product->name }}">
                  <h6><a href="{{ route('shop.show', $product->slug) }}" class="text-dark">{{ $product->name }}</a></h6>
                  <p class="text-muted mb-2">${{ number_format($product->price, 2) }}</p>
                  <a href="{{ route('shop.show', $product->slug) }}" class="sisf-shortcode sisf-text-underline sisf-underline--left d-inline-block">
                     <span class="sisf-m-text">View</span>
                  </a>
               </div>
            </div>
            @endforeach
         </div>
         @endif

         {{-- Activities --}}
         @if($activities->count())
         <h3 class="mb-4">Activities ({{ $activities->count() }})</h3>
         <div class="row mb-5">
            @foreach($activities as $activity)
            <div class="col-lg-4 col-md-6 mb-4">
               <div class="card border-0 shadow-sm h-100">
                  @if($activity->thumbnail)
                  <img src="{{ asset($activity->thumbnail) }}" class="card-img-top" style="height:200px;object-fit:cover" alt="{{ $activity->title }}">
                  @endif
                  <div class="card-body">
                     <h5 class="card-title"><a href="{{ route('activity.show', $activity->slug) }}" class="text-dark">{{ $activity->title }}</a></h5>
                     <a href="{{ route('activity.show', $activity->slug) }}" class="sisf-shortcode sisf-text-underline sisf-underline--left">
                        <span class="sisf-m-text">Explore</span>
                     </a>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
         @endif
      @else
         <p class="text-center text-muted">Enter a keyword above to search across rooms, blog, shop and activities.</p>
      @endif
   </div>
</div>
@endsection
