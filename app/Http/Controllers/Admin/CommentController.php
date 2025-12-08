<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $movies = Movie::orderBy('title')->get();

        $query = Comment::with('movie') // nhớ khai báo quan hệ movie() trong model Comment
            ->orderByDesc('created_at');

        // filter theo phim
        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        // search theo tên / nội dung / ip
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('content', 'like', '%'.$search.'%')
                    ->orWhere('ip_address', 'like', '%'.$search.'%');
            });
        }

        $comments = $query->paginate(15)->withQueryString();

        return view('admin.comments.index', compact('comments', 'movies'));
    }



    public function edit(Comment $comment)
    {
        $movies = Movie::orderBy('title')->get();

        return view('admin.comments.edit', compact('comment', 'movies'));
    }

    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'name'       => 'required|string|max:255',
            'content'    => 'required|string',
            'rating'     => 'required|integer|min:1|max:5',
            'likes'      => 'nullable|integer|min:0',
            'dislikes'   => 'nullable|integer|min:0',
        ]);

        $comment->update($data);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Cập nhật bình luận thành công');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Xóa bình luận thành công');
    }

    // mấy cái này không dùng trong admin (ko cho tạo comment từ admin)
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function show(Comment $comment) { abort(404); }
}
