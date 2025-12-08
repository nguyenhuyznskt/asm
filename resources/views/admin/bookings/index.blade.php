@extends('admin.layouts.app')

@section('title', 'Đơn đặt vé')
@section('page_title', 'Đơn đặt vé')
@section('page_subtitle', 'Quản lý tất cả đơn đặt vé của khách hàng')

@section('styles')
<style>
    .booking-card {
        background: rgba(15,23,42,0.9);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .booking-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .booking-table th {
        text-align: left;
        padding: 8px 10px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: #020617;
        color: #9ca3af;
        border-bottom: 1px solid #1f2937;
        white-space: nowrap;
    }
    .booking-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
        vertical-align: top;
    }
    .booking-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }

    .booking-search,
    .booking-select {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .booking-search:focus,
    .booking-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }

    .badge-status {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
        border-width: 1px;
        border-style: solid;
    }
    .badge-status-confirmed {
        background: rgba(16,185,129,0.15);
        color: #6ee7b7;
        border-color: rgba(16,185,129,0.6);
    }
    .badge-status-pending {
        background: rgba(245,158,11,0.15);
        color: #fcd34d;
        border-color: rgba(245,158,11,0.6);
    }
    .badge-status-cancelled {
        background: rgba(248,113,113,0.15);
        color: #fecaca;
        border-color: rgba(248,113,113,0.7);
    }
    .badge-status-default {
        background: rgba(55,65,81,0.8);
        color: #e5e7eb;
        border-color: rgba(75,85,99,1);
    }

    .badge-chip {
        border-radius: 999px;
        padding: 1px 6px;
        font-size: 0.7rem;
        border: 1px solid #4b5563;
        color: #9ca3af;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('booking-search');
    const filterForm  = document.getElementById('booking-filter-form');
    const movieSelect = document.getElementById('booking-movie-filter');
    const cinemaSelect = document.getElementById('booking-cinema-filter');
    const statusSelect = document.getElementById('booking-status-filter');

    function filterClient() {
        const keyword = (searchInput?.value || '').toLowerCase();
        document.querySelectorAll('[data-booking-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = keyword && !text.includes(keyword) ? 'none' : '';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterClient);
    }

    [movieSelect, cinemaSelect, statusSelect].forEach(el => {
        if (!el || !filterForm) return;
        el.addEventListener('change', () => filterForm.submit());
    });
});
</script>
@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <div class="w-full md:w-72">
        <input id="booking-search" type="text" class="booking-search"
               placeholder="Tìm trên trang (tên, email, sđt, phim, rạp)...">
    </div>

    <form id="booking-filter-form" method="GET" class="flex flex-wrap items-center gap-2">
        <div class="w-48">
            <select id="booking-status-filter" name="status" class="booking-select text-xs">
                <option value="">Tất cả trạng thái</option>
                <option value="confirmed" @selected(request('status') === 'confirmed')>Đã xác nhận</option>
                <option value="pending" @selected(request('status') === 'pending')>Đang chờ</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Đã hủy</option>
            </select>
        </div>

        <div class="w-52">
            <select id="booking-movie-filter" name="movie_id" class="booking-select text-xs">
                <option value="">Tất cả phim</option>
                @foreach($movies as $m)
                    <option value="{{ $m->id }}" @selected(request('movie_id') == $m->id)>
                        {{ $m->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-52">
            <select id="booking-cinema-filter" name="cinema_id" class="booking-select text-xs">
                <option value="">Tất cả rạp</option>
                @foreach($cinemas as $c)
                    <option value="{{ $c->id }}" @selected(request('cinema_id') == $c->id)>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="booking-card overflow-x-auto">
    <table class="booking-table">
        <thead>
        <tr>
            <th>Khách hàng</th>
            <th>Phim / Suất chiếu</th>
            <th>Rạp / Phòng</th>
            <th>Thành tiền</th>
            <th>Trạng thái</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($bookings as $booking)
        @php
        $status = $booking->status;
        $statusClass = 'badge-status-default';
        $statusLabel = 'Không rõ';
    
        if ($status === 'confirmed') {
            $statusClass = 'badge-status-confirmed';
            $statusLabel = 'Đã xác nhận';
        } elseif ($status === 'pending') {
            $statusClass = 'badge-status-pending';
            $statusLabel = 'Đang chờ';
        } elseif (in_array($status, ['cancelled','canceled'])) {
            $statusClass = 'badge-status-cancelled';
            $statusLabel = 'Đã hủy';
        }
    @endphp
    
            <tr data-booking-row>
                <td class="text-xs text-slate-200 max-w-[200px]">
                    <div class="font-semibold text-slate-50">
                        {{ $booking->customer_name }}
                    </div>
                    <div class="text-[11px] text-slate-400">
                        {{ $booking->customer_email ?? '—' }}
                    </div>
                    <div class="text-[11px] text-slate-500 mt-0.5">
                        {{ $booking->customer_phone ?? '—' }}
                    </div>
                    @if($booking->user)
                        <div class="mt-1 text-[11px] text-emerald-300">
                            User: {{ $booking->user->email }}
                        </div>
                    @endif
                </td>

                <td class="text-xs text-slate-300 max-w-[220px]">
                    <div class="font-medium text-slate-50">
                        {{ optional($booking->showtime->movie ?? null)->title ?? '—' }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        Suất: 
                        @if($booking->showtime && $booking->showtime->start_time)
                            {{ $booking->showtime->start_time->format('d/m/Y H:i') }}
                        @else
                            —
                        @endif
                    </div>
                    <div class="text-[11px] text-slate-500">
                        Mã đơn: #{{ $booking->id }}
                    </div>
                </td>

                <td class="text-xs text-slate-300 max-w-[220px]">
                    <div>
                        {{ optional(optional($booking->showtime->room ?? null)->cinema)->name ?? '—' }}
                    </div>
                    <div class="text-[11px] text-slate-400">
                        Phòng: {{ optional($booking->showtime->room ?? null)->name ?? '—' }}
                    </div>
                </td>

                <td class="text-xs text-emerald-300">
                    <span class="badge-chip bg-emerald-500/15 border border-emerald-500/40">
                        {{ number_format($booking->total_price, 0, ',', '.') }} đ
                    </span>
                    <div class="text-[11px] text-slate-500 mt-1">
                        {{ optional($booking->created_at)->format('d/m/Y H:i') ?? '—' }}
                    </div>
                </td>

                <td class="text-xs">
                    <span class="badge-status {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </td>
                

                <td class="text-right text-xs">
                    <a href="{{ route('admin.bookings.show', $booking) }}"
                       class="text-sky-400 hover:text-sky-300 mr-2">
                        Chi tiết
                    </a>
                
                    @if($booking->status === 'pending')
                        <form action="{{ route('admin.bookings.destroy', $booking) }}"
                              method="POST" class="inline-block"
                              onsubmit="return confirm('Hủy đơn đang chờ này và trả ghế?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-rose-400 hover:text-rose-300">
                                Hủy đơn
                            </button>
                        </form>
                    @endif
                </td>
                
                
                
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-sm text-slate-400 py-4">
                    Chưa có đơn đặt vé nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $bookings->links() }}
</div>
@endsection
