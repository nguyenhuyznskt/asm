@extends('frontend.layouts.app')

@section('title', 'Chọn phim')

@section('content')
    <h1 class="text-2xl font-bold mb-2">Chọn phim</h1>
    <p class="text-sm text-slate-300 mb-4">
        Rạp: {{ $cinema->name }}
    </p>

    @if($movies->isEmpty())
        <p class="text-sm text-slate-400">Hiện chưa có phim nào chiếu tại rạp này.</p>
    @else
        <div class="grid md:grid-cols-4 gap-4">
            @foreach($movies as $movie)
                <a href="{{ route('booking.flow.showtime', [
                        'cinema_id' => $cinema->id,
                        'movie_id'  => $movie->id,
                    ]) }}"
                   class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-emerald-500 transition">
                    <img src="{{ $movie->poster_url }}" class="w-full h-48 object-cover">
                    <div class="p-2">
                        <p class="text-sm font-semibold line-clamp-2">{{ $movie->title }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
