<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer(['components.header', 'components.footer', 'layouts.app'], NavigationComposer::class);

        try {
            $mailer = SiteSetting::get('mail_mailer');
            if ($mailer && $mailer !== 'log') {
                config([
                    'mail.default'                      => $mailer,
                    'mail.mailers.smtp.host'            => SiteSetting::get('mail_host', '127.0.0.1'),
                    'mail.mailers.smtp.port'            => (int) SiteSetting::get('mail_port', 587),
                    'mail.mailers.smtp.username'        => SiteSetting::get('mail_username'),
                    'mail.mailers.smtp.password'        => SiteSetting::get('mail_password'),
                    'mail.mailers.smtp.encryption'      => SiteSetting::get('mail_encryption', 'tls') ?: null,
                    'mail.from.address'                 => SiteSetting::get('mail_from_address', config('mail.from.address')),
                    'mail.from.name'                    => SiteSetting::get('mail_from_name', config('mail.from.name')),
                ]);
            }
        } catch (\Exception) {
            // DB not ready (e.g. fresh install, migrations pending) — use .env defaults
        }
    }
}
