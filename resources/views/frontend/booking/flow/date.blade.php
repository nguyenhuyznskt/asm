@extends('frontend.layouts.app')

@section('title', 'Đặt vé - Chọn ngày')

@section('content')
<h1 class="text-2xl font-bold mb-2">Chọn ngày chiếu</h1>

<p class="text-sm text-slate-400 mb-4">
    Rạp: {{ $cinema->name }}
</p>

@if($dates->isEmpty())
    <p class="text-sm text-slate-400">Hiện chưa có suất chiếu nào cho rạp này.</p>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        @foreach($dates as $d)
            @php
                $value = $d->date; // Y-m-d
                $label = \Carbon\Carbon::parse($d->date)->format('d/m');
                $full  = \Carbon\Carbon::parse($d->date)->format('d/m/Y');
            @endphp

            <a href="{{ route('booking.flow.movie', [
                    'cinema_id' => $cinema->id,
                    'date'      => $value,
                ]) }}"
               class="block text-center bg-slate-900 border border-slate-800
                      hover:border-emerald-400 rounded-xl px-3 py-2 text-sm
                      transition shadow-sm hover:shadow-md">
                <div class="font-semibold">{{ $label }}</div>
                <div class="text-[11px] text-slate-400">{{ $full }}</div>
            </a>
        @endforeach
    </div>
@endif

{{-- nút quay lại chọn rạp --}}
<div class="mt-6">
    <a href="{{ route('booking.flow.cinema') }}"
       class="inline-flex items-center text-sm text-slate-400 hover:text-emerald-400">
        ⬅ Quay lại chọn rạp
    </a>
</div>
@endsection
