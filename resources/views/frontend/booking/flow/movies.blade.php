@extends('frontend.layouts.app')

@section('title', 'Đặt vé - Chọn phim & giờ chiếu')

@section('content')
<h1 class="text-2xl font-bold mb-2">Đặt vé - {{ $cinema->name }}</h1>

{{-- Thanh chọn ngày --}}
@if($dates->isEmpty())
    <p class="text-sm text-slate-400 mb-4">
        Hiện chưa có suất chiếu nào cho rạp này.
    </p>
@else
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-4">
        @foreach($dates as $d)
            @php
                $value = $d->date; // Y-m-d
                $carbon = \Carbon\Carbon::parse($d->date);
                $labelDay = $carbon->format('d/m');
                $labelWeekday = $carbon->translatedFormat('D'); // T2, T3... (nếu cấu hình locale)
                $isActive = $currentDate === $value;
            @endphp

            <a href="{{ route('booking.flow.movie', [
                    'cinema_id' => $cinema->id,
                    'date'      => $value,
                ]) }}"
               class="flex flex-col items-center px-3 py-2 rounded-xl border
                      text-sm min-w-[70px]
                      {{ $isActive
                          ? 'bg-emerald-500 border-emerald-500 text-slate-900'
                          : 'bg-slate-900 border-slate-700 text-slate-100 hover:border-emerald-400' }}">
                <span class="text-[11px] uppercase">{{ $labelWeekday }}</span>
                <span class="font-semibold">{{ $labelDay }}</span>
            </a>
        @endforeach
    </div>
@endif

{{-- Danh sách phim & suất chiếu cho ngày đang chọn --}}
@if(!$currentDate)
    <p class="text-sm text-slate-400">Không có ngày nào có suất chiếu.</p>
@elseif($showtimesByMovie->isEmpty())
    <p class="text-sm text-slate-400">
        Không có suất chiếu nào trong ngày
        {{ \Carbon\Carbon::parse($currentDate)->format('d/m/Y') }}.
    </p>
@else
    <p class="text-sm text-slate-400 mb-3">
        Ngày: {{ \Carbon\Carbon::parse($currentDate)->format('d/m/Y') }}
    </p>

    <div class="space-y-4">
        @foreach($showtimesByMovie as $movieId => $showtimes)
            @php $movie = $movies[$movieId]; @endphp

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <div class="flex items-start gap-4">
                    <div class="w-20 shrink-0">
                        <img src="{{ $movie->poster_url }}"
                             class="w-20 h-28 object-cover rounded border border-slate-700">
                    </div>

                    <div class="flex-1">
                        <h2 class="text-lg font-semibold mb-1">{{ $movie->title }}</h2>
                        <p class="text-xs text-slate-400 mb-2">
                            Thời lượng: {{ $movie->duration_minutes }} phút • {{ $movie->age_rating }}
                        </p>

                        <div class="flex flex-wrap gap-2">
                            @foreach($showtimes as $showtime)
                                <a href="{{ route('booking.select-seats', $showtime) }}"
                                   class="px-3 py-1 rounded bg-slate-800 hover:bg-emerald-500 text-xs">
                                    {{ $showtime->start_time->format('H:i') }}
                                    – Phòng {{ $showtime->room->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Quay lại chọn rạp --}}
<div class="mt-6">
    <a href="{{ route('booking.flow.cinema') }}"
       class="inline-flex items-center text-sm text-slate-400 hover:text-emerald-400">
        ⬅ Quay lại chọn rạp
    </a>
</div>
@endsection
