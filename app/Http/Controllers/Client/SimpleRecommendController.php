<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class SimpleRecommendController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Thể loại user hay xem nhất (dựa trên bookings của user đó)
        $favoriteGenreId = Booking::query()
            ->where('user_id', $user->id)
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->selectRaw('movies.genre_id, COUNT(*) as total')
            ->groupBy('movies.genre_id')
            ->orderByDesc('total')
            ->value('genre_id'); // genre_id xuất hiện nhiều nhất

        // 2. Query danh sách phim: dùng total_bookings trong bảng movies
        $query = Movie::query()
            ->with('genre'); // nhớ có relation genre() trong Movie

        // Nếu muốn chỉ lấy phim mới trong 6 tháng gần đây (optional)
        // $query->whereDate('release_date', '>=', now()->subMonths(6));

        // 3. Ưu tiên thể loại user thích
        if ($favoriteGenreId) {
            $query->orderByRaw('CASE WHEN genre_id = ? THEN 0 ELSE 1 END', [$favoriteGenreId]);
        }

        // 4. Sort theo độ hot + featured + ngày phát hành
        $recommendedMovies = $query
            ->orderByDesc('total_bookings') // cột thật trong bảng movies
            ->orderByDesc('is_featured')    // phim nổi bật lên trước
            ->orderByDesc('release_date')   // phim mới hơn lên trước
            ->limit(10)
            ->get();

        return view('frontend.movies.simple_recommend', compact('recommendedMovies', 'favoriteGenreId'));
    }
}
