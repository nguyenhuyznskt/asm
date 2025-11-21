@extends('frontend.layouts.app')

@section('title', 'Trang chủ - Laravel Cinema')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Phim đang chiếu</h1>

    {{-- ở đây mày show danh sách phim đang chiếu từ controller --}}
    @isset($nowShowing)
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            @forelse($nowShowing as $movie)
                <a href="{{ route('movies.show', $movie->slug) }}"
                   class="bg-slate-900 rounded-xl overflow-hidden hover:-translate-y-1 transition border border-slate-800">
                   @php
                   $poster = $movie->poster_url ?: 'https://via.placeholder.com/300x450?text=No+Image';
               @endphp
               <img src="{{ $poster }}" class="w-full h-64 object-cover">
               
                    <div class="p-3">
                        <h2 class="font-semibold text-sm mb-1 line-clamp-2">{{ $movie->title }}</h2>
                        <p class="text-xs text-slate-400">
                            {{ $movie->duration_minutes }} phút • {{ $movie->age_rating }}
                        </p>
                    </div>
                </a>
            @empty
                <p>Không có phim nào.</p>
            @endforelse
        </div>
    @endisset
@endsection
