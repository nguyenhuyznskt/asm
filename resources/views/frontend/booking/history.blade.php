@extends('frontend.layouts.app')

@section('title', 'Lịch sử đặt vé')

@section('content')

<h1 class="text-2xl font-bold mb-4">Lịch sử đặt vé</h1>

@if($bookings->isEmpty())
    <p class="text-sm text-slate-400">
        Bạn chưa có vé nào. Hãy đặt vé ngay hôm nay để trải nghiệm rạp nhé 🎬
    </p>
@else
    <div class="space-y-3">
        @foreach($bookings as $booking)
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <p class="text-sm text-slate-400 mb-1">
                        Mã vé: <span class="font-mono text-slate-200">#{{ $booking->id }}</span>
                    </p>

                    <p class="font-semibold">
                        {{ $booking->showtime->movie->title ?? 'N/A' }}
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Rạp: {{ $booking->showtime->room->cinema->name ?? 'N/A' }} –
                        Phòng: {{ $booking->showtime->room->name ?? 'N/A' }}
                    </p>

                    <p class="text-xs text-slate-400">
                        Suất chiếu:
                        {{ optional($booking->showtime->start_time)->format('d/m/Y H:i') }}
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Ghế:
                        @if($booking->seats->isNotEmpty())
                            @foreach($booking->seats as $seat)
                                {{ $seat->row }}{{ $seat->number }}@if(!$loop->last), @endif
                            @endforeach
                        @else
                            <span>—</span>
                        @endif
                    </p>

                    <p class="text-sm text-emerald-400 mt-1 font-semibold">
                        Tổng tiền: {{ number_format($booking->total_price) }}đ
                    </p>

                    <p class="text-xs text-slate-500 mt-1">
                        Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="flex gap-2 md:flex-col md:items-end">
                    <a href="{{ route('ticket.show', $booking) }}"
                       class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-sm font-semibold text-slate-900">
                        Xem vé
                    </a>

                    {{-- sau này có thể thêm nút hủy / đặt lại --}}
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
@endif

@endsection
