<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class HomeController extends Controller
{
    public function index()
    {
        $now = now();

        // Phim đang chiếu
        $nowShowing = Movie::whereDate('release_date', '<=', $now)
            ->orderBy('release_date', 'desc')
            ->take(8)
            ->get();

        // Phim sắp chiếu
        $comingSoon = Movie::whereDate('release_date', '>', $now)
            ->orderBy('release_date', 'asc')
            ->take(8)
            ->get();

        return view('frontend.home', compact('nowShowing', 'comingSoon'));
    }
}
