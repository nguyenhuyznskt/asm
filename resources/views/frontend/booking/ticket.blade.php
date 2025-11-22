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
        @foreach($booking->seats as $seat)
            {{ $seat->row }}{{ $seat->number }}
        @endforeach
    </p>

    <p><strong>Tổng tiền:</strong> {{ number_format($booking->total_price) }}đ</p>

    <div class="mt-4 flex justify-center">
       <img src="data:image/png;base64,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" alt="">
        {{-- {!! QrCode::size(180)->generate(route('ticket.show', $booking->id)) !!} --}}
       
    </div>

</div>

@endsection
