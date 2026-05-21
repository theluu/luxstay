<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'footer_gallery' => self::getFooterGallery(),
                'footer_menu'    => self::getFooterMenu(),
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'footer_gallery'                 => 'nullable|array|max:10',
            'footer_gallery.*'               => 'nullable|string',
            'footer_menu'                    => 'nullable|array|max:20',
            'footer_menu.*.label'            => 'required|string|max:100',
            'footer_menu.*.url'              => 'required|string|max:255',
            'footer_menu.*.children'         => 'nullable|array|max:10',
            'footer_menu.*.children.*.label' => 'required|string|max:100',
            'footer_menu.*.children.*.url'   => 'required|string|max:255',
        ]);

        SiteSetting::set('footer_gallery', json_encode(array_values($data['footer_gallery'] ?? [])));
        SiteSetting::set('footer_menu',    json_encode(array_values($data['footer_menu']    ?? [])));

        return $this->index();
    }

    public static function getFooterGallery(): array
    {
        $stored = json_decode(SiteSetting::get('footer_gallery', '[]'), true);

        return is_array($stored) && $stored ? array_values($stored) : self::defaultFooterGallery();
    }

    public static function getFooterMenu(): array
    {
        $stored = json_decode(SiteSetting::get('footer_menu', '[]'), true);

        return is_array($stored) && $stored ? array_values($stored) : self::defaultFooterMenu();
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

    public static function defaultFooterMenu(): array
    {
        return [
            ['label' => 'Trang chủ',    'url' => '/',        'children' => []],
            ['label' => 'Về chúng tôi', 'url' => '/about',   'children' => []],
            ['label' => 'Phòng',        'url' => '/rooms',   'children' => []],
            ['label' => 'Cửa hàng',     'url' => '/shop',    'children' => []],
            ['label' => 'Blog',         'url' => '/blog',    'children' => []],
            ['label' => 'Liên hệ',      'url' => '/contact', 'children' => []],
        ];
    }
}
