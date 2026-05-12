<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>LuxeStay Admin</title>
   @vite(['resources/js/admin/main.js'])
</head>
<body>
   <div id="admin-app"></div>
</body>
</html>
