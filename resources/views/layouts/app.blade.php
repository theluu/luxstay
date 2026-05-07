<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="@yield('meta_description', 'LuxeStay – Luxury Hotel & Resort Booking')">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>@yield('title', 'LuxeStay – Luxury Hotel & Resort Booking')</title>
   <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Nanum+Myeongjo&display=swap" rel="stylesheet">
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
   @yield('content')
   <x-footer />
</body>
</html>
