@extends('frontend.layouts.app')

@section('title', 'Danh sách phim')

@section('content')

<h1 class="text-3xl font-bold mb-4">Danh sách phim</h1>

@if(request('q'))
    <p class="text-sm text-slate-400 mb-3">
        Kết quả tìm kiếm cho: <span class="text-emerald-400">"{{ request('q') }}"</span>
    </p>
@endif
<div class="grid md:grid-cols-4 gap-4">

    @foreach($movies as $movie)
        <a href="{{ route('movies.show', $movie->slug) }}"
           class="bg-slate-900 border border-slate-800 rounded-xl hover:-translate-y-1 overflow-hidden transition">
           @php
    $poster = $movie->poster_url ?: 'https://via.placeholder.com/300x450?text=No+Image';
@endphp
<img src="{{ $poster }}" class="w-full h-64 object-cover">

            <div class="p-3">
                <h2 class="font-semibold text-sm">{{ $movie->title }}</h2>
                <p class="text-xs text-slate-400">{{ $movie->duration_minutes }} phút</p>
            </div>
        </a>
    @endforeach

</div>

<div class="mt-4">
    {{ $movies->links() }}
</div>

@endsection
