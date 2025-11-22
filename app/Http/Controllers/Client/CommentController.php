<?php

namespace App\Http\Controllers\Client;

use App\Models\Comment;
use App\Models\Movie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'rating'  => 'required|integer|min:1|max:5',
        ]);

        // chống spam: 1 IP / movie không được gửi quá nhanh
        $ip = $request->ip();
        $recent = Comment::where('movie_id', $movie->id)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes(1))
            ->exists();

        if ($recent) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Bạn vừa gửi bình luận, vui lòng thử lại sau ít phút.',
                ], 429);
            }

            return back()->with('error', 'Bạn vừa gửi bình luận, vui lòng thử lại sau ít phút.');
        }

        $data['movie_id'] = $movie->id;
        $data['ip_address'] = $ip;

        $comment = Comment::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Cảm ơn bạn đã đánh giá!',
                'comment' => [
                    'id'         => $comment->id,
                    'name'       => $comment->name,
                    'content'    => $comment->content,
                    'rating'     => $comment->rating,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'likes'      => $comment->likes,
                    'dislikes'   => $comment->dislikes,
                ],
            ]);
        }

        return back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    public function like(Comment $comment, Request $request)
    {
        $comment->increment('likes');

        if ($request->expectsJson()) {
            return response()->json(['likes' => $comment->likes]);
        }

        return back();
    }

    public function dislike(Comment $comment, Request $request)
    {
        $comment->increment('dislikes');

        if ($request->expectsJson()) {
            return response()->json(['dislikes' => $comment->dislikes]);
        }

        return back();
    }
}
