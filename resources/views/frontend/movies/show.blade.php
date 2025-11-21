@extends('frontend.layouts.app')

@section('title', $movie->title)

@section('content')

<div class="grid md:grid-cols-3 gap-6">

    <div>
        <img src="{{ $movie->poster_url }}" class="w-full rounded-xl border border-slate-800">
    </div>

    <div class="md:col-span-2">
        <h1 class="text-3xl font-bold mb-2">{{ $movie->title }}</h1>

        <p class="text-sm text-slate-300 mb-4">
            Thời lượng: {{ $movie->duration_minutes }} phút • {{ $movie->age_rating }}<br>
            Khởi chiếu: {{ optional($movie->release_date)->format('d/m/Y') }}
        </p>

        <p class="text-sm text-slate-200 mb-4">
            {{ $movie->description }}
        </p>

        <h2 class="text-xl font-semibold mb-3">Suất chiếu</h2>

        @forelse($showtimesByDate as $date => $showtimes)

            <p class="text-sm text-slate-400 mb-1">
                Ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
            </p>

            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($showtimes as $showtime)
                    <a href="{{ route('booking.select-seats', $showtime) }}"
                       class="px-3 py-1 rounded bg-slate-800 hover:bg-emerald-500 text-xs">
                        {{ $showtime->start_time->format('H:i') }}
                        • {{ $showtime->room->cinema->name }}
                        – {{ $showtime->room->name }}
                    </a>
                @endforeach
            </div>

        @empty
            <p class="text-slate-400">Chưa có suất chiếu nào.</p>
        @endforelse
    </div>

</div>

@endsection
