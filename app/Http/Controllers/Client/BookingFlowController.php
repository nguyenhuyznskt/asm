<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;

class BookingFlowController extends Controller
{
    // B1: chọn rạp
    public function chooseCinema()
    {
        $cinemas = Cinema::orderBy('name')->get();

        return view('frontend.booking.flow.cinemas', compact('cinemas'));
    }

    public function chooseMovie(Request $request)
    {
        $cinemaId = $request->input('cinema_id');

        $cinema = Cinema::findOrFail($cinemaId);

        // Lấy tất cả ngày có suất chiếu ở rạp này
        $dates = Showtime::whereHas('room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            })
            ->selectRaw('DATE(start_time) as date')
            ->distinct()
            ->orderBy('date')
            ->get();

        // Xác định ngày đang được chọn
        $currentDate = $request->input('date');
        if (!$currentDate) {
            $currentDate = optional($dates->first())->date; // ngày đầu tiên có suất
        }

        // Nếu vẫn không có ngày nào => không có suất chiếu
        $showtimesByMovie = collect();
        $movies = collect();

        if ($currentDate) {
            // Lấy suất chiếu của rạp trong ngày đang chọn
            $showtimes = Showtime::with(['movie', 'room'])
                ->whereHas('room', function ($q) use ($cinemaId) {
                    $q->where('cinema_id', $cinemaId);
                })
                ->whereDate('start_time', $currentDate)
                ->orderBy('movie_id')
                ->orderBy('start_time')
                ->get();

            $showtimesByMovie = $showtimes->groupBy('movie_id');

            // Lấy danh sách phim tương ứng
            $movies = Movie::whereIn('id', $showtimesByMovie->keys())
                ->get()
                ->keyBy('id');
        }

        return view('frontend.booking.flow.movies', [
            'cinema'           => $cinema,
            'dates'            => $dates,
            'currentDate'      => $currentDate,
            'movies'           => $movies,
            'showtimesByMovie' => $showtimesByMovie,
        ]);
    }
}
