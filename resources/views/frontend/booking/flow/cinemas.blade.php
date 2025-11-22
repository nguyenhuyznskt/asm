@extends('frontend.layouts.app')

@section('title', 'Chọn rạp')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Chọn rạp</h1>

    <div class="grid md:grid-cols-3 gap-4">
        @foreach($cinemas as $cinema)
            <a href="{{ route('booking.flow.movie', ['cinema_id' => $cinema->id]) }}"
               class="bg-slate-900 border border-slate-800 rounded-xl p-3 hover:border-emerald-500 transition">
                <h2 class="font-semibold">{{ $cinema->name }}</h2>
                <p class="text-xs text-slate-400 mt-1">
                    {{ $cinema->address }}
                </p>
            </a>
        @endforeach
    </div>
@endsection
