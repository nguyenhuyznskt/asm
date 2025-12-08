@extends('frontend.layouts.app')

@section('title', 'Thanh toán đơn #' . $booking->id)

@section('content')
<div class="max-w-xl mx-auto space-y-4">
    <h1 class="text-2xl font-bold text-emerald-400 mb-2">
        Thanh toán online
    </h1>

    @if(session('error'))
        <div class="mb-3 text-sm text-rose-300 bg-rose-500/10 border border-rose-500/40 px-3 py-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4 space-y-4">
        <div>
            <div class="text-sm text-slate-300">
                Mã đơn: <span class="font-semibold text-emerald-400">#{{ $booking->id }}</span>
            </div>
            <div class="text-sm text-slate-300">
                Số tiền: <span class="font-semibold text-emerald-400">
                    {{ number_format($amount, 0, ',', '.') }} đ
                </span>
            </div>
            <div class="text-sm text-slate-300">
                Nội dung chuyển khoản:
                <span class="font-mono text-xs bg-slate-800 px-2 py-1 rounded">
                    {{ $addInfo }}
                </span>
            </div>
        </div>

        <div class="flex flex-col items-center gap-3">
            <div class="text-xs text-slate-400">
                Quét QR bằng app ngân hàng để thanh toán
            </div>
            <div class="bg-white p-3 rounded-xl">
                <img src="{{ $vietqrUrl }}" alt="VietQR" class="w-56 h-56 object-contain">
            </div>
        </div>

        {{-- DEMO: nút tự xác nhận thanh toán --}}
        <form action="{{ route('booking.payment.confirm', $booking) }}" method="POST" class="text-center">
            @csrf
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm
                           bg-emerald-500 hover:bg-emerald-600 text-slate-900 font-semibold">
                ✅ Tôi đã thanh toán xong
            </button>
        </form>

        <div class="text-[11px] text-slate-500">
            * Trong thực tế, việc xác nhận sẽ tự động thông qua hệ thống ngân hàng/cổng thanh toán.
        </div>
    </div>
</div>
@endsection
