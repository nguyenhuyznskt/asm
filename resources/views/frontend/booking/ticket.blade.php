@extends('frontend.layouts.app')

@section('title', 'Vé xem phim #' . $booking->id)

@section('content')
<div class="max-w-xl mx-auto space-y-4">
    @if(session('success'))
        <div class="mb-3 text-sm text-emerald-300 bg-emerald-500/10 border border-emerald-500/40 px-3 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">
                    Vé xem phim
                </div>
                <div class="text-lg font-semibold text-emerald-400">
                    #TICKET-{{ $booking->id }}
                </div>
            </div>
            <div class="text-right text-xs text-slate-400">
                Thanh toán lúc<br>
                {{ optional($booking->paid_at)->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="border-t border-slate-800 pt-3 grid grid-cols-2 gap-2 text-xs text-slate-300">
            <div>
                <div class="text-slate-500 text-[11px] uppercase tracking-[0.16em]">Phim</div>
                <div class="font-medium">
                    {{ $booking->showtime->movie->title ?? '—' }}
                </div>
            </div>
            <div>
                <div class="text-slate-500 text-[11px] uppercase tracking-[0.16em]">Rạp / Phòng</div>
                <div class="font-medium">
                    {{ $booking->showtime->room->cinema->name ?? '' }}
                    @if(optional($booking->showtime->room)->name)
                        – {{ $booking->showtime->room->name }}
                    @endif
                </div>
            </div>
            <div>
                <div class="text-slate-500 text-[11px] uppercase tracking-[0.16em]">Suất chiếu</div>
                <div class="font-medium">
                    {{ optional($booking->showtime->start_time)->format('d/m/Y H:i') }}
                </div>
            </div>
            <div>
                <div class="text-slate-500 text-[11px] uppercase tracking-[0.16em]">Ghế</div>
                <div class="font-medium">
                    @if($booking->seats->isNotEmpty())
            {{ $booking->seats->map(function ($seat) {
                return $seat->code ?? ($seat->row . $seat->number);
            })->join(', ') }}
        @else
            —
        @endif
                </div>
            </div>
        </div>

        <div class="mt-4 flex flex-col items-center gap-2">
            <div class="text-xs text-slate-400">
                QR check-in tại rạp
            </div>
            <div class="bg-white p-3 rounded-xl">
                {!! QrCode::size(200)->generate($ticketPayload) !!}
            </div>
            <div class="text-[11px] text-slate-500 text-center">
                Nhân viên rạp sẽ quét QR này để xác nhận vé hợp lệ.<br>
                Không chia sẻ QR này cho người khác.
            </div>
        </div>
    </div>
</div>
@endsection
