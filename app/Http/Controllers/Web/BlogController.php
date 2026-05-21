<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostComment;
use App\Services\RecaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $post     = Post::published()->where('slug', $slug)->with('category', 'author')->firstOrFail();
        $recent   = Post::published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();
        $comments = $post->comments()->approved()->topLevel()->with(['replies' => fn($q) => $q->approved()->oldest()])->latest()->get();
        return view('pages.blog.show', compact('post', 'recent', 'comments'));
    }

    public function storeComment(Request $request, string $slug): RedirectResponse
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        $request->validate([
            'author'  => 'required|string|max:100',
            'email'   => 'required|email|max:255',
            'url'     => 'nullable|url|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        if (!RecaptchaService::verify($request->input('recaptcha_token', ''), 'comment')) {
            return back()->withInput()->with('error', 'Xác minh bảo mật thất bại. Vui lòng thử lại.');
        }

        PostComment::create([
            'post_id'          => $post->id,
            'author_name'      => $request->author,
            'author_email'     => $request->email,
            'author_website'   => $request->url ?: null,
            'body'             => $request->comment,
        ]);

        return redirect()->route('blog.show', $slug)
            ->with('comment_success', 'Thank you! Your comment is awaiting moderation.')
            ->withFragment('comments');
    }
}
