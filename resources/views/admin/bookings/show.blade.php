<div>
    <!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
</div>
@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn đặt vé')
@section('page_title', 'Chi tiết đơn đặt vé')
@section('page_subtitle', 'Mã đơn #'.$booking->id)

@section('styles')
<style>
    .bk-main-card {
        background: rgba(15,23,42,0.95);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
        max-width: 1040px;
    }
    .bk-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
        gap: 16px;
    }
    .bk-block {
        background: rgba(15,23,42,0.95);
        border-radius: 14px;
        border: 1px solid #1f2937;
        padding: 12px 14px;
        font-size: 0.85rem;
    }
    .bk-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9ca3af;
        margin-bottom: 2px;
    }
    .bk-value {
        color: #e5e7eb;
        font-size: 0.9rem;
    }
    .bk-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }
    .bk-table th {
        text-align: left;
        padding: 6px 8px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: #020617;
        color: #9ca3af;
        border-bottom: 1px solid #1f2937;
        white-space: nowrap;
    }
    .bk-table td {
        padding: 6px 8px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
        vertical-align: top;
    }
    .bk-table tr:hover td {
        background: rgba(30,64,175,0.2);
    }
    .badge-status {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
        border-width: 1px;
        border-style: solid;
    }
    .badge-confirmed {
        background: rgba(16,185,129,0.15);
        color: #6ee7b7;
        border-color: rgba(16,185,129,0.6);
    }
    .badge-pending {
        background: rgba(245,158,11,0.15);
        color: #fcd34d;
        border-color: rgba(245,158,11,0.6);
    }
    .badge-cancelled {
        background: rgba(248,113,113,0.15);
        color: #fecaca;
        border-color: rgba(248,113,113,0.7);
    }
    .badge-default {
        background: rgba(55,65,81,0.8);
        color: #e5e7eb;
        border-color: rgba(75,85,99,1);
    }
    .bk-total {
        font-size: 0.9rem;
        font-weight: 600;
        color: #bbf7d0;
    }
</style>
@endsection

@section('content')
@php
    $status = $booking->status;
    $statusClass = 'badge-default';
    $statusLabel = $status;

    if ($status === 'confirmed') {
        $statusClass = 'badge-confirmed';
        $statusLabel = 'Đã xác nhận';
    } elseif ($status === 'pending') {
        $statusClass = 'badge-pending';
        $statusLabel = 'Đang chờ';
    } elseif (in_array($status, ['cancelled','canceled'])) {
        $statusClass = 'badge-cancelled';
        $statusLabel = 'Đã hủy';
    }
@endphp

<div class="bk-main-card">
    {{-- Thông tin tổng quan --}}
    <div class="bk-grid mb-4">
        <div class="space-y-3">
            <div class="bk-block">
                <div class="bk-label">Thông tin khách hàng</div>
                <div class="bk-value font-semibold">
                    {{ $booking->customer_name }}
                </div>
                <div class="text-xs text-slate-400 mt-1">
                    Email: {{ $booking->customer_email ?? '—' }}<br>
                    SĐT: {{ $booking->customer_phone ?? '—' }}
                </div>

                @if($booking->user)
                    <div class="text-xs text-emerald-300 mt-2">
                        Tài khoản: {{ $booking->user->email }}
                    </div>
                @endif
            </div>

            <div class="bk-block">
                <div class="bk-label">Chi tiết suất chiếu</div>
                <div class="bk-value font-semibold">
                    {{ optional($booking->showtime->movie ?? null)->title ?? '—' }}
                </div>
                <div class="text-xs text-slate-400 mt-1">
                    @if($booking->showtime && $booking->showtime->start_time)
                        Giờ chiếu: {{ $booking->showtime->start_time->format('d/m/Y H:i') }}
                    @else
                        Giờ chiếu: —
                    @endif
                </div>
                <div class="text-xs text-slate-400">
                    @php
                        $room = $booking->showtime->room ?? null;
                        $cinema = $room?->cinema;
                    @endphp
                    Rạp: {{ $cinema->name ?? '—' }}<br>
                    Phòng: {{ $room->name ?? '—' }}
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <div class="bk-block">
                <div class="bk-label">Trạng thái đơn</div>
                <div class="mt-1">
                    <span class="badge-status {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                <div class="text-xs text-slate-400 mt-2">
                    Mã đơn: #{{ $booking->id }}<br>
                    Tạo lúc: {{ optional($booking->created_at)->format('d/m/Y H:i') ?? '—' }}<br>
                    Cập nhật: {{ optional($booking->updated_at)->format('d/m/Y H:i') ?? '—' }}
                </div>
            </div>

            <div class="bk-block">
                <div class="bk-label">Tổng tiền</div>
                <div class="bk-total mt-1">
                    {{ number_format($booking->total_price, 0, ',', '.') }} đ
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách ghế --}}
    <div class="grid md:grid-cols-2 gap-4 mb-4">
        <div class="bk-block">
            <div class="bk-label mb-2">Ghế đã đặt</div>
            @if($seatLines->isEmpty())
                <div class="text-xs text-slate-400">
                    Không có ghế nào (dữ liệu trống?).
                </div>
            @else
                <table class="bk-table">
                    <thead>
                    <tr>
                        <th>Ghế</th>
                        <th>Loại</th>
                        <th>Giá</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($seatLines as $line)
                        @php
                            $seat = $line->seat;
                        @endphp
                        <tr>
                            <td class="text-xs text-slate-200">
                                @if($seat)
                                    Hàng {{ $seat->row }} - Ghế {{ $seat->number }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-xs text-slate-300">
                                @if($seat)
                                    {{ $seat->type === 'vip' ? 'VIP' : 'Thường' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-xs text-emerald-300">
                                {{ number_format($line->price, 0, ',', '.') }} đ
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Danh sách combo --}}
        <div class="bk-block">
            <div class="bk-label mb-2">Combo / Bắp nước</div>
            @if($comboLines->isEmpty())
                <div class="text-xs text-slate-400">
                    Không có combo đi kèm.
                </div>
            @else
                <table class="bk-table">
                    <thead>
                    <tr>
                        <th>Combo</th>
                        <th>SL</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comboLines as $line)
                        @php $combo = $line->combo; @endphp
                        <tr>
                            <td class="text-xs text-slate-200">
                                {{ $combo->name ?? '—' }}
                            </td>
                            <td class="text-xs text-slate-300">
                                {{ $line->quantity }}
                            </td>
                            <td class="text-xs text-slate-300">
                                {{ number_format($line->unit_price, 0, ',', '.') }} đ
                            </td>
                            <td class="text-xs text-emerald-300">
                                {{ number_format($line->total_price, 0, ',', '.') }} đ
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Footer action --}}
    <div class="mt-4 flex justify-between items-center">
        <a href="{{ route('admin.bookings.index') }}"
           class="text-xs text-slate-400 hover:text-slate-200">
            ← Quay lại danh sách đơn
        </a>
    
        @if($booking->status === 'pending')
            <form action="{{ route('admin.bookings.destroy', $booking) }}"
                  method="POST"
                  onsubmit="return confirm('Hủy đơn đang chờ này và trả ghế?');">
                @csrf
                @method('DELETE')
                <button
                    class="px-3 py-1.5 rounded-full text-xs font-medium
                           border border-rose-500/70 text-rose-300 hover:bg-rose-500/10">
                    Hủy đơn
                </button>
            </form>
        @endif
    </div>
    
</div>
@endsection
