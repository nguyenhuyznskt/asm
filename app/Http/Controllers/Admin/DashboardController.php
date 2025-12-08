<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Booking;
use App\Models\Genre;
use App\Models\Showtime;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cards tổng quan
        $totalMovies   = Movie::count();
        $totalCinemas  = Cinema::count();
        $totalBookings = Booking::count();

        // 1. Biểu đồ tròn: tỉ lệ thể loại phim (số phim / thể loại)
        $genreStats = Genre::select('genres.name')
            ->selectRaw('COUNT(movies.id) as total')
            ->leftJoin('movies', 'movies.genre_id', '=', 'genres.id')
            ->groupBy('genres.id', 'genres.name')
            ->orderBy('genres.name')
            ->get();

        $genreLabels = $genreStats->pluck('name');
        $genreCounts = $genreStats->pluck('total');

        // 2. Biểu đồ số lượng phim theo tháng (dựa vào release_date, 6–12 tháng gần nhất)
        $moviesByMonth = Movie::selectRaw("DATE_FORMAT(release_date, '%Y-%m') as m")
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('release_date')
            ->groupBy('m')
            ->orderBy('m')
            ->limit(12)
            ->get();

        $moviesMonthLabels = $moviesByMonth->pluck('m');      // ví dụ: 2025-01
        $moviesMonthCounts = $moviesByMonth->pluck('total');

        // 3. Biểu đồ số phim của từng rạp
        // tính số phim có suất chiếu ở mỗi rạp (distinct movie_id)
        $moviesByCinema = Cinema::select('cinemas.name')
            ->selectRaw('COUNT(DISTINCT showtimes.movie_id) as total')
            ->join('rooms', 'rooms.cinema_id', '=', 'cinemas.id')
            ->join('showtimes', 'showtimes.room_id', '=', 'rooms.id')
            ->groupBy('cinemas.id', 'cinemas.name')
            ->orderBy('cinemas.name')
            ->get();

        $cinemaMovieLabels = $moviesByCinema->pluck('name');
        $cinemaMovieCounts = $moviesByCinema->pluck('total');

        // 4a. Biểu đồ khách hàng xem phim theo rạp (số booking / rạp)
        $bookingsByCinema = Cinema::select('cinemas.name')
            ->selectRaw('COUNT(bookings.id) as total')
            ->join('rooms', 'rooms.cinema_id', '=', 'cinemas.id')
            ->join('showtimes', 'showtimes.room_id', '=', 'rooms.id')
            ->join('bookings', 'bookings.showtime_id', '=', 'showtimes.id')
            ->groupBy('cinemas.id', 'cinemas.name')
            ->orderByDesc('total')
            ->get();

        $cinemaBookingLabels = $bookingsByCinema->pluck('name');
        $cinemaBookingCounts = $bookingsByCinema->pluck('total');

        // 4b. Biểu đồ khách hàng xem phim theo phim (top 10)
        $bookingsByMovie = Movie::select('movies.title')
            ->selectRaw('COUNT(bookings.id) as total')
            ->join('showtimes', 'showtimes.movie_id', '=', 'movies.id')
            ->join('bookings', 'bookings.showtime_id', '=', 'showtimes.id')
            ->groupBy('movies.id', 'movies.title')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $movieBookingLabels = $bookingsByMovie->pluck('title');
        $movieBookingCounts = $bookingsByMovie->pluck('total');

        return view('admin.dashboard', compact(
            'totalMovies',
            'totalCinemas',
            'totalBookings',
            'genreLabels',
            'genreCounts',
            'moviesMonthLabels',
            'moviesMonthCounts',
            'cinemaMovieLabels',
            'cinemaMovieCounts',
            'cinemaBookingLabels',
            'cinemaBookingCounts',
            'movieBookingLabels',
            'movieBookingCounts'
        ));
    }
}
