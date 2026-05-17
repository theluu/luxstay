<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>LuxeStay Admin</title>
   <link rel="icon" type="image/svg+xml" href="/favicon-admin.svg">
   <link rel="alternate icon" href="/favicon.ico">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
   @vite(['resources/js/admin/main.js'])
</head>
<body>
   <div id="admin-app"></div>
</body>
</html>
