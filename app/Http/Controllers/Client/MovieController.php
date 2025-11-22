<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();
    
        $type = $request->get('type');
    
        if ($type === 'coming_soon') {
            $query->comingSoon();
        } elseif ($type === 'now_showing') {
            $query->nowShowing();
        } elseif ($type === 'featured') {
            $query->featured();
        }
    
        if ($search = $request->input('q')) {
            $query->where('title', 'like', '%' . $search . '%');
        }
    
        $movies = $query->orderBy('release_date', 'desc')->paginate(12)->withQueryString();
    
        return view('frontend.movies.index', compact('movies', 'type'));
    }
    

    public function show(string $slug)
    {
        $movie = Movie::with(['showtimes.room.cinema'])
            ->where('slug', $slug)
            ->firstOrFail();
    
        $showtimesByDate = $movie->showtimes
            ->sortBy('start_time')
            ->groupBy(fn($show) => $show->start_time->format('Y-m-d'));
    
        // phân trang comment
        $comments = $movie->comments()
            ->latest()
            ->paginate(5);
    
        $avgRating = round((float) $movie->avgRating(), 1);
    
        return view('frontend.movies.show', compact(
            'movie',
            'showtimesByDate',
            'comments',
            'avgRating'
        ));
    }
}

