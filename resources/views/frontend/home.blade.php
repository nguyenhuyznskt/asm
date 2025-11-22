@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')

{{-- Phim nổi bật --}}
@if($featured->count())
    <h2 class="text-2xl font-bold mb-3">🎯 Phim nổi bật</h2>

    <div class="grid md:grid-cols-4 gap-4 mb-8">
        @foreach($featured as $movie)
            <a href="{{ route('movies.show', $movie->slug) }}"
               class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-emerald-500 transition">
                <img src="{{ $movie->poster_url }}" class="w-full h-56 object-cover">
                <div class="p-3">
                    <p class="font-semibold text-sm line-clamp-2">{{ $movie->title }}</p>
                    <p class="text-xs text-slate-400 mt-1">
                        Khởi chiếu: {{ optional($movie->release_date)->format('d/m/Y') }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
@endif

{{-- Đang chiếu --}}
<h2 class="text-2xl font-bold mb-3">🎬 Đang chiếu</h2>

@if($nowShowing->count())
    <div class="grid md:grid-cols-4 gap-4 mb-8">
        @foreach($nowShowing as $movie)
            <a href="{{ route('movies.show', $movie->slug) }}"
               class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-emerald-500 transition">
                <img src="{{ $movie->poster_url }}" class="w-full h-56 object-cover">
                <div class="p-3">
                    <p class="font-semibold text-sm line-clamp-2">{{ $movie->title }}</p>
                </div>
            </a>
        @endforeach
    </div>
@else
    <p class="text-sm text-slate-400 mb-8">Hiện chưa có phim nào đang chiếu.</p>
@endif

{{-- Sắp chiếu --}}
<h2 class="text-2xl font-bold mb-3">🎞️ Sắp chiếu</h2>

@if($comingSoon->count())
    <div class="grid md:grid-cols-4 gap-4">
        @foreach($comingSoon as $movie)
            <a href="{{ route('movies.show', $movie->slug) }}"
               class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-amber-500/70 transition">
                <img src="{{ $movie->poster_url }}" class="w-full h-56 object-cover opacity-80">
                <div class="p-3">
                    <p class="font-semibold text-sm line-clamp-2">{{ $movie->title }}</p>
                    <p class="text-xs text-amber-400 mt-1">
                        Khởi chiếu: {{ optional($movie->release_date)->format('d/m/Y') }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
@else
    <p class="text-sm text-slate-400">Chưa có phim sắp chiếu.</p>
@endif

@endsection
