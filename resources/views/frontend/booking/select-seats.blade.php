@extends('frontend.layouts.app')

@section('title', 'Chọn ghế - ' . $showtime->movie->title)

@section('content')

<h1 class="text-2xl font-bold mb-3">Chọn ghế - {{ $showtime->movie->title }}</h1>

<p class="text-sm mb-4 text-slate-300">
    Rạp: {{ $showtime->room->cinema->name }} – {{ $showtime->room->name }} <br>
    Suất: {{ $showtime->start_time->format('d/m/Y H:i') }} <br>
    Giá vé: {{ number_format($showtime->price) }}đ
</p>

<div class="bg-slate-900 p-4 rounded-xl mb-6">

    <p class="text-center text-xs text-slate-400 mb-2">Màn hình</p>
    <div class="h-1 bg-slate-500 mb-4"></div>

    <form method="POST" action="{{ route('booking.store', $showtime) }}">
        @csrf

        <div class="space-y-2">

            @foreach($showtime->room->seats->groupBy('row') as $row => $seats)

                <div class="flex items-center gap-2">
                    <span class="w-6 text-xs text-slate-400">{{ $row }}</span>

                    @foreach($seats as $seat)
                        @php $isBooked = in_array($seat->id, $bookedSeatIds); @endphp

                        <label class="w-7 h-7 text-[10px] flex items-center justify-center rounded cursor-pointer
                            {{ $isBooked ? 'bg-red-600 opacity-60 cursor-not-allowed' : 'bg-slate-700 hover:bg-emerald-500' }}">
                            @unless($isBooked)
                                <input type="checkbox" name="seats[]" value="{{ $seat->id }}" class="hidden">
                            @endunless
                            {{ $seat->number }}
                        </label>
                    @endforeach
                </div>

            @endforeach

        </div>

        <div class="mt-6 grid md:grid-cols-3 gap-4">

            <div class="md:col-span-2">
                <input type="text" name="customer_name" required placeholder="Họ tên"
                       class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 mb-2">

                <input type="email" name="customer_email" placeholder="Email"
                       class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 mb-2">

                <input type="text" name="customer_phone" placeholder="Số điện thoại"
                       class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700">
            </div>

            <div>
                <button type="submit"
                        class="w-full px-4 py-2 rounded bg-emerald-500 hover:bg-emerald-600 font-semibold">
                    Xác nhận đặt vé
                </button>
            </div>

        </div>

    </form>

</div>

@endsection
