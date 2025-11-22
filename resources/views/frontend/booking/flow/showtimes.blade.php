@extends('frontend.layouts.app')

@section('title', 'Chọn suất chiếu')

@section('content')
    <h1 class="text-2xl font-bold mb-2">Chọn suất chiếu</h1>

    <p class="text-sm text-slate-300 mb-4">
        Rạp: {{ $cinema->name }} <br>
        Phim: {{ $movie->title }}
    </p>

    {{-- dải ngày 5 ngày tới --}}
    <div class="flex gap-2 mb-4">
        @foreach(range(0, 4) as $i)
            @php
                $d = now()->addDays($i);
            @endphp

            <a href="{{ route('booking.flow.showtime', [
                    'cinema_id' => $cinema->id,
                    'movie_id'  => $movie->id,
                    'date'      => $d->toDateString(),
                ]) }}"
               class="px-3 py-1 rounded border text-sm
                    {{ $d->isSameDay($date) ? 'bg-emerald-500 border-emerald-500 text-slate-900' : 'bg-slate-900 border-slate-700 text-slate-200' }}">
                {{ $d->format('d/m') }}
            </a>
        @endforeach
    </div>

    @if($showtimes->isEmpty())
        <p class="text-sm text-slate-400">Không có suất chiếu trong ngày này.</p>
    @else
        <div class="flex flex-wrap gap-2">
            @foreach($showtimes as $showtime)
                <a href="{{ route('booking.select-seats', $showtime) }}"
                   class="px-3 py-2 rounded bg-slate-800 hover:bg-emerald-500 text-xs">
                    {{ $showtime->start_time->format('H:i') }}
                    – {{ $showtime->room->name }}
                </a>
            @endforeach
        </div>
    @endif
@endsection
