<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingFlowController extends Controller
{
    // B1: chọn rạp
    public function chooseCinema(Request $request)
    {
        // nếu mày có cột city thì có thể filter theo city, còn không thì bỏ
        $cinemas = Cinema::orderBy('name')->get();

        return view('frontend.booking.flow.cinemas', compact('cinemas'));
    }

    // B2: chọn phim theo rạp
    public function chooseMovie(Request $request)
    {
        $cinemaId = $request->get('cinema_id');
        $cinema   = Cinema::findOrFail($cinemaId);

        // tìm phim có suất chiếu ở rạp này
        $movies = Movie::whereHas('showtimes.room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            })
            ->distinct()
            ->get();

        return view('frontend.booking.flow.movies', compact('cinema', 'movies'));
    }

    // B3: chọn ngày + suất chiếu
    public function chooseShowtime(Request $request)
    {
        $cinemaId = $request->get('cinema_id');
        $movieId  = $request->get('movie_id');
        $date     = $request->get('date', now()->toDateString()); // yyyy-mm-dd

        $cinema = Cinema::findOrFail($cinemaId);
        $movie  = Movie::findOrFail($movieId);

        $showtimes = Showtime::with('room')
            ->where('movie_id', $movieId)
            ->whereHas('room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            })
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get();

        return view('frontend.booking.flow.showtimes', [
            'cinema'    => $cinema,
            'movie'     => $movie,
            'date'      => Carbon::parse($date),
            'showtimes' => $showtimes,
        ]);
    }
}
