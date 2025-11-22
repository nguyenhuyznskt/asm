@extends('frontend.layouts.app')

@section('title', 'Đặt vé - Chọn rạp')

@section('content')
<h1 class="text-2xl font-bold mb-4">Chọn rạp</h1>

@if($cinemas->isEmpty())
    <p class="text-sm text-slate-400">Hiện chưa có rạp nào.</p>
@else
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($cinemas as $cinema)
            <a href="{{ route('booking.flow.movie', ['cinema_id' => $cinema->id]) }}"
               class="block bg-slate-900 border border-slate-800 hover:border-emerald-400
                      rounded-xl p-4 transition shadow-sm hover:shadow-md">
                <h2 class="font-semibold mb-1">{{ $cinema->name }}</h2>
                @if($cinema->address)
                    <p class="text-xs text-slate-400">{{ $cinema->address }}</p>
                @endif
            </a>
        @endforeach
    </div>
@endif
@endsection
