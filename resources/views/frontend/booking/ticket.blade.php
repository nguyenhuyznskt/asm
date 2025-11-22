@extends('frontend.layouts.app')

@section('title', 'Vé xem phim #' . $booking->id)

@section('content')

<h1 class="text-2xl font-bold mb-4">Vé xem phim #{{ $booking->id }}</h1>

<div class="bg-slate-900 p-6 rounded-xl max-w-xl mx-auto">

    <p><strong>Phim:</strong> {{ $booking->showtime->movie->title }}</p>
    <p><strong>Rạp:</strong> {{ $booking->showtime->room->cinema->name }}</p>
    <p><strong>Phòng:</strong> {{ $booking->showtime->room->name }}</p>
    <p><strong>Suất chiếu:</strong> {{ $booking->showtime->start_time->format('d/m/Y H:i') }}</p>

    <p><strong>Ghế:</strong>
        @if($booking->seats->isNotEmpty())
            @foreach($booking->seats as $seat)
                {{ $seat->row }}{{ $seat->number }}@if(!$loop->last), @endif
            @endforeach
        @else
            Không có ghế nào.
        @endif
    </p>

    <p><strong>Tổng tiền:</strong> {{ number_format($booking->total_price) }}đ</p>

    <div class="mt-4 flex justify-center">
        <img src="https://img.vietqr.io/image/970416-000000000000-qr_only.png?amount={{ $booking->total_price }}&addInfo=VE{{ $booking->id }}"
             class="w-64 h-64 mx-auto rounded">
    </div>

    {{-- Nút quay lại --}}
    <div class="mt-6 text-center">
        <a href="{{ route('home') }}"
           class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm text-slate-200">
            ⬅ Quay lại trang chủ
        </a>
    </div>

</div>

@endsection
