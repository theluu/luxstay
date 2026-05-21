@php
    $guestRecaptchaEnabled = \App\Services\RecaptchaService::isEnabled();
    $guestRecaptchaSiteKey = \App\Services\RecaptchaService::siteKey();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @if($guestRecaptchaEnabled && $guestRecaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $guestRecaptchaSiteKey }}" async defer></script>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        @if($guestRecaptchaEnabled && $guestRecaptchaSiteKey)
        <script>
        (function () {
            var SITE_KEY = '{{ $guestRecaptchaSiteKey }}';
            function attachRecaptcha(form) {
                form.addEventListener('submit', function (e) {
                    var existing = form.querySelector('input[name="recaptcha_token"]');
                    if (existing && existing.value) return;
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
        })();
        </script>
        @endif
    </body>
</html>
