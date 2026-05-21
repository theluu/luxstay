<?php

namespace App\View\Composers;

use App\Http\Controllers\Api\MenuController;
use App\Models\Activity;
use App\Models\SiteSetting;
use App\Services\RecaptchaService;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $settings   = SiteSetting::allKeyed();
        $activities = Activity::orderBy('sort_order')->get(['title', 'slug']);

        $view->with('navActivities', $activities);
        $view->with('cartItemCount', array_sum(session()->get('cart', [])));
        $view->with('siteSettings', $settings);

        // Left nav — DB managed
        $view->with('navItems', MenuController::getNavItems());

        // Right nav — DB managed items only (Hoạt động rendered separately in blade)
        $view->with('navRightItems', MenuController::getRightNavItems());

        // reCAPTCHA site key (public — safe to expose to frontend)
        $view->with('recaptchaSiteKey', RecaptchaService::siteKey());
        $view->with('recaptchaEnabled', RecaptchaService::isEnabled());
    }
}
