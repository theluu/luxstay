<?php

namespace App\View\Composers;

use App\Models\Activity;
use App\Models\SiteSetting;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $settings = SiteSetting::allKeyed();

        $view->with('navActivities', Activity::orderBy('sort_order')->get(['title', 'slug']));
        $view->with('cartItemCount', array_sum(session()->get('cart', [])));
        $view->with('siteSettings', $settings);
        $view->with('navItems', json_decode($settings['nav_items'] ?? '[]', true));
    }
}
