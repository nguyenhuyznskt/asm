<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    //
    public function index()
    {
        $movies = Movie::orderBy('release_date', 'desc')
            ->paginate(8);

        return view('frontend.movies.index', compact('movies'));
    }

    public function show(string $slug)
    {
        $movie = Movie::with(['showtimes.room.cinema'])
            ->where('slug', $slug)
            ->firstOrFail();

        $showtimesByDate = $movie->showtimes
            ->sortBy('start_time')
            ->groupBy(fn($show) => $show->start_time->format('Y-m-d'));

        return view('frontend.movies.show', compact('movie', 'showtimesByDate'));
    }
}
