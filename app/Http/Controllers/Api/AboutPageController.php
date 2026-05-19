<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'about_page' => $this->aboutPage(),
                'footer_gallery' => $this->footerGallery(),
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'about_page' => 'required|array',
            'footer_gallery' => 'nullable|array|max:10',
        ]);

        SiteSetting::set('about_page', json_encode($data['about_page']));
        SiteSetting::set('footer_gallery', json_encode(array_values($data['footer_gallery'] ?? [])));

        return $this->index();
    }

    public static function getAboutPage(): array
    {
        $stored = json_decode(SiteSetting::get('about_page', '[]'), true);

        return array_replace_recursive(self::defaultAboutPage(), is_array($stored) ? $stored : []);
    }

    public static function getFooterGallery(): array
    {
        $stored = json_decode(SiteSetting::get('footer_gallery', '[]'), true);

        return is_array($stored) && $stored ? array_values($stored) : self::defaultFooterGallery();
    }

    public static function defaultAboutPage(): array
    {
        return [
            'hero' => [
                'title' => 'About Us',
                'image' => 'images/about_hero_img.png',
            ],
            'intro' => [
                'title' => "Quality Services &\nActivities Near you",
                'paragraphs' => [
                    'Our Comfort Is Our Priority" expresses a commitment to providing the highest level of comfort and satisfaction for our customers. Whether you\'re staying with us, using our services, or purchasing our products, we prioritize your needs and ensure a relaxing and enjoyable experience.',
                    'From cozy accommodations and personalized services to high-quality products designed with your comfort in mind, everything we do is centered around making you feel at ease and valued. Your comfort isn\'t just our goal—it\'s our top priority.',
                ],
                'cards' => [
                    [
                        'title' => 'About Us',
                        'description' => 'It refers to an establishment that provides lodging, typically on a short-term basis.',
                        'button_text' => 'Explore More',
                        'url' => '/about',
                        'image' => 'images/about_us_img-1.png',
                    ],
                    [
                        'title' => 'Luxury Rooms',
                        'description' => 'It refers to an establishment that provides lodging, typically on a short-term basis.',
                        'button_text' => 'Explore More',
                        'url' => '/rooms',
                        'image' => 'images/about_us_img-2.png',
                    ],
                ],
            ],
            'band' => [
                'title' => "Quality Services\n& Activities Near you",
            ],
            'comfort' => [
                'title' => "YOUR COMFORT\nIS OUR HIGH PRIORITY",
                'left_image' => 'images/your_comfort_img-1.png',
                'right_image' => 'images/your_comfort_img-2.png',
                'counters' => [
                    ['number' => '45', 'suffix' => '+', 'label' => 'Rooms'],
                    ['number' => '12', 'suffix' => 'K', 'label' => 'REVIEWS'],
                    ['number' => '250', 'suffix' => '+', 'label' => 'STAFFS'],
                    ['number' => '15', 'suffix' => '+', 'label' => 'YEARS JOURNEY'],
                ],
            ],
            'partners' => [
                'title' => "OUR TRUSTED\nPARTNERS",
                'logos' => [
                    'images/client_logo1.png',
                    'images/client_logo2.png',
                    'images/client_logo3.png',
                    'images/client_logo4.png',
                    'images/client_logo5.png',
                ],
            ],
        ];
    }

    public static function defaultFooterGallery(): array
    {
        return [
            'images/footer_img1.png',
            'images/footer_img2.png',
            'images/footer_img3.png',
            'images/footer_img4.png',
            'images/footer_img5.png',
        ];
    }

    private function aboutPage(): array
    {
        return self::getAboutPage();
    }

    private function footerGallery(): array
    {
        return self::getFooterGallery();
    }
}
