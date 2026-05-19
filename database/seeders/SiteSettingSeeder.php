<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\AboutPageController;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name'       => 'LuxeStay',
            'logo'            => 'images/logo.png',
            'favicon'         => 'images/favicon.png',
            'facebook_url'    => 'https://facebook.com',
            'instagram_url'   => 'https://instagram.com',
            'linkedin_url'    => 'https://linkedin.com',
            'twitter_url'     => 'https://twitter.com',
            'phone'           => '(617) 623-2338',
            'email'           => 'info@luxestay.com',
            'services_video_url' => 'https://luxestay.wpthemeverse.com/wp-content/uploads/2024/07/video2.mp4',
            'about_page'      => json_encode(AboutPageController::defaultAboutPage()),
            'footer_gallery'  => json_encode(AboutPageController::defaultFooterGallery()),
            'nav_items'       => json_encode([
                ['label' => 'Home',  'url' => '/',     'children' => []],
                ['label' => 'Rooms', 'url' => '/rooms','children' => [
                    ['label' => 'Rooms & Suites', 'url' => '/rooms/suites'],
                ]],
                ['label' => 'Pages', 'url' => '#',     'children' => [
                    ['label' => 'About Us',            'url' => '/about'],
                    ['label' => 'Offers & Promotions', 'url' => '/offers'],
                    ['label' => 'Restaurant',          'url' => '/activities/restaurant'],
                    ['label' => 'Contact',             'url' => '/contact'],
                ]],
                ['label' => 'Shop',  'url' => '/shop', 'children' => [
                    ['label' => 'Shop',       'url' => '/shop'],
                    ['label' => 'Cart',       'url' => '/cart'],
                    ['label' => 'Checkout',   'url' => '/checkout'],
                    ['label' => 'My Account', 'url' => '/account'],
                ]],
            ]),
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
