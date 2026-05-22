<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('subject', 'LuxeStay')</title>
  <style>
    body { margin:0; padding:0; background:#f4f4f5; font-family:'Helvetica Neue',Arial,sans-serif; color:#1a1a1a; }
    .wrapper { max-width:600px; margin:32px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .header { background:#0d0d0d; padding:32px 40px; text-align:center; }
    .header h1 { margin:0; color:#e8c97a; font-size:26px; letter-spacing:3px; font-weight:300; text-transform:uppercase; }
    .header p { margin:6px 0 0; color:#9ca3af; font-size:12px; letter-spacing:2px; text-transform:uppercase; }
    .content { padding:40px; }
    .content h2 { font-size:20px; margin:0 0 16px; color:#111; }
    .content p { line-height:1.7; color:#374151; margin:0 0 14px; font-size:15px; }
    .detail-box { background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:20px 24px; margin:20px 0; }
    .detail-box table { width:100%; border-collapse:collapse; }
    .detail-box td { padding:6px 0; font-size:14px; color:#374151; vertical-align:top; }
    .detail-box td:first-child { font-weight:600; color:#111; width:40%; padding-right:12px; }
    .btn { display:inline-block; background:#0d0d0d; color:#e8c97a !important; padding:13px 32px; border-radius:4px; text-decoration:none; font-size:14px; font-weight:600; letter-spacing:1px; margin:8px 0; }
    .divider { border:none; border-top:1px solid #f3f4f6; margin:24px 0; }
    .footer { background:#f9fafb; padding:24px 40px; text-align:center; border-top:1px solid #f3f4f6; }
    .footer p { font-size:12px; color:#9ca3af; margin:0 0 4px; }
    .footer a { color:#6b7280; text-decoration:none; }
    @media (max-width:600px) {
      .content { padding:28px 20px; }
      .header { padding:24px 20px; }
      .footer { padding:20px; }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>LuxeStay</h1>
    <p>Luxury Hotel &amp; Resort</p>
  </div>
  <div class="content">
    @yield('content')
  </div>
  <div class="footer">
    <p>© {{ date('Y') }} LuxeStay — Luxury Hotel &amp; Resort</p>
    <p><a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>
  </div>
</div>
</body>
</html>
