<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return PostResource::collection(Post::with('category', 'author')->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|unique:posts,slug',
            'excerpt'          => 'nullable|string',
            'content'          => 'required|string',
            'thumbnail'        => 'nullable|string',
            'type'             => 'in:standard,video,quote',
            'status'           => 'in:draft,published',
            'post_category_id' => 'nullable|exists:post_categories,id',
            'published_at'     => 'nullable|date',
        ]);
        $data['author_id'] = Auth::id();
        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        $post = Post::create($data);
        return (new PostResource($post->load('category', 'author')))->response()->setStatusCode(201);
    }

    public function show(Post $post): PostResource
    {
        return new PostResource($post->load('category', 'author'));
    }

    public function update(Request $request, Post $post): PostResource
    {
        $data = $request->validate([
            'title'            => 'sometimes|string|max:255',
            'slug'             => 'sometimes|string|unique:posts,slug,' . $post->id,
            'excerpt'          => 'nullable|string',
            'content'          => 'sometimes|string',
            'thumbnail'        => 'nullable|string',
            'type'             => 'in:standard,video,quote',
            'status'           => 'in:draft,published',
            'post_category_id' => 'nullable|exists:post_categories,id',
            'published_at'     => 'nullable|date',
        ]);
        if (($data['status'] ?? null) === 'published' && ! $post->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        $post->update($data);
        return new PostResource($post->load('category', 'author'));
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json(null, 204);
    }
}
