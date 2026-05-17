<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status', 'pending'); // pending | approved | all

        $query = PostComment::with('post:id,title,slug')
            ->whereNull('parent_id')
            ->latest();

        if ($status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($status === 'approved') {
            $query->where('is_approved', true);
        }

        return response()->json($query->paginate(20));
    }

    public function approve(PostComment $comment): JsonResponse
    {
        $comment->update(['is_approved' => true]);
        return response()->json(['ok' => true]);
    }

    public function reject(PostComment $comment): JsonResponse
    {
        $comment->delete();
        return response()->json(['ok' => true]);
    }

    public function reply(Request $request, PostComment $comment): JsonResponse
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $reply = PostComment::create([
            'post_id'        => $comment->post_id,
            'parent_id'      => $comment->id,
            'author_name'    => 'LuxeStay Team',
            'author_email'   => 'admin@luxestay.com',
            'body'           => $request->body,
            'is_admin_reply' => true,
            'is_approved'    => true,
        ]);

        return response()->json($reply, 201);
    }
}
