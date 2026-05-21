<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'nav_items'       => self::getNavItems(),
                'nav_items_right' => self::getRightNavItems(),
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nav_items'                          => 'nullable|array|max:30',
            'nav_items.*.label'                  => 'required|string|max:100',
            'nav_items.*.url'                    => 'required|string|max:255',
            'nav_items.*.children'               => 'nullable|array|max:15',
            'nav_items.*.children.*.label'       => 'required|string|max:100',
            'nav_items.*.children.*.url'         => 'required|string|max:255',
            'nav_items_right'                    => 'nullable|array|max:20',
            'nav_items_right.*.label'            => 'required|string|max:100',
            'nav_items_right.*.url'              => 'required|string|max:255',
            'nav_items_right.*.children'         => 'nullable|array|max:15',
            'nav_items_right.*.children.*.label' => 'required|string|max:100',
            'nav_items_right.*.children.*.url'   => 'required|string|max:255',
        ]);

        if ($request->has('nav_items')) {
            SiteSetting::set('nav_items', json_encode(array_values($data['nav_items'] ?? [])));
        }

        if ($request->has('nav_items_right')) {
            SiteSetting::set('nav_items_right', json_encode(array_values($data['nav_items_right'] ?? [])));
        }

        return $this->index();
    }

    public static function getNavItems(): array
    {
        $stored = json_decode(SiteSetting::get('nav_items', '[]'), true);

        if (is_string($stored)) {
            $stored = json_decode($stored, true);
        }

        return is_array($stored) ? array_values($stored) : [];
    }

    public static function getRightNavItems(): array
    {
        $stored = json_decode(SiteSetting::get('nav_items_right', '[]'), true);

        if (is_string($stored)) {
            $stored = json_decode($stored, true);
        }

        return is_array($stored) && $stored ? array_values($stored) : self::defaultRightNavItems();
    }

    public static function defaultRightNavItems(): array
    {
        return [
            ['label' => 'Blog',       'url' => '/blog',    'children' => []],
            ['label' => 'Trang đích', 'url' => '/landing', 'children' => []],
        ];
    }
}
