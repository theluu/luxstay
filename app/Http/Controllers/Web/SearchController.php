<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Post;
use App\Models\Product;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim($request->query('q', ''));

        $rooms = $posts = $products = $activities = collect();

        if (strlen($q) >= 2) {
            $rooms = Room::with('roomType')
                ->where('is_available', true)
                ->where(fn($query) => $query
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%"))
                ->take(6)->get();

            $posts = Post::published()
                ->where(fn($query) => $query
                    ->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%"))
                ->latest('published_at')->take(6)->get();

            $products = Product::where('is_active', true)
                ->where(fn($query) => $query
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%"))
                ->take(6)->get();

            $activities = Activity::where(fn($query) => $query
                    ->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%"))
                ->take(6)->get();
        }

        $total = $rooms->count() + $posts->count() + $products->count() + $activities->count();

        return view('pages.search', compact('q', 'rooms', 'posts', 'products', 'activities', 'total'));
    }
}
