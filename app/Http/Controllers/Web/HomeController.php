<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Post;
use App\Models\Room;
use App\Models\SiteSetting;
use App\Models\Slider;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $rooms      = Room::with('roomType')->where('is_available', true)->take(8)->get();
        $posts      = Post::published()->latest('published_at')->take(4)->get();
        $activities = Activity::where('is_featured', true)->orderBy('sort_order')->take(4)->get();
        $sliders    = Slider::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $servicesVideoUrl = SiteSetting::get(
            'services_video_url',
            'https://luxestay.wpthemeverse.com/wp-content/uploads/2024/07/video2.mp4'
        );

        return view('pages.home', compact('rooms', 'posts', 'activities', 'sliders', 'servicesVideoUrl'));
    }
}
