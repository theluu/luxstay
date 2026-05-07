<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts      = Post::published()->with('category', 'author')->latest('published_at')->paginate(9);
        $categories = PostCategory::withCount(['posts' => fn ($q) => $q->published()])->get();
        return view('pages.blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug): View
    {
        $post   = Post::published()->where('slug', $slug)->with('category', 'author')->firstOrFail();
        $recent = Post::published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();
        return view('pages.blog.show', compact('post', 'recent'));
    }
}
