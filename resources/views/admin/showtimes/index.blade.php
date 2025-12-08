@extends('admin.layouts.app')

@section('title', 'Suất chiếu')
@section('page_title', 'Suất chiếu')
@section('page_subtitle', 'Quản lý lịch chiếu cho từng phim, phòng, rạp')

@section('styles')
<style>
    .showtime-card {
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .showtime-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .showtime-table th {
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
    .showtime-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
        vertical-align: top;
    }
    .showtime-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }
    .showtime-search,
    .showtime-select {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .showtime-search:focus,
    .showtime-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .showtime-badge {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
    }
    .showtime-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .showtime-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .showtime-btn-primary:hover { filter: brightness(1.08); }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput   = document.getElementById('showtime-search');
    const filterForm    = document.getElementById('showtime-filter-form');
    const movieSelect   = document.getElementById('showtime-movie-filter');
    const roomSelect    = document.getElementById('showtime-room-filter');
    const dateInput     = document.getElementById('showtime-date-filter');
    const fromTimeInput = document.getElementById('showtime-from-time-filter');
    const toTimeInput   = document.getElementById('showtime-to-time-filter');

    function filterClient() {
        const keyword = (searchInput?.value || '').toLowerCase();
        document.querySelectorAll('[data-showtime-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = keyword && !text.includes(keyword) ? 'none' : '';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterClient);
    }

    function bindAutoSubmit(el) {
        if (!el || !filterForm) return;
        el.addEventListener('change', () => filterForm.submit());
    }

    bindAutoSubmit(movieSelect);
    bindAutoSubmit(roomSelect);
    bindAutoSubmit(dateInput);
    bindAutoSubmit(fromTimeInput);
    bindAutoSubmit(toTimeInput);
});
</script>
@endsection

@section('header_actions')
<a href="{{ route('admin.showtimes.create') }}"
   class="showtime-btn showtime-btn-primary inline-flex items-center gap-1">
    + Thêm suất chiếu
</a>
@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <div class="w-full md:w-64">
        <input id="showtime-search" type="text" class="showtime-search"
               placeholder="Tìm trên trang (phim, rạp, phòng, giờ chiếu)...">
    </div>

    <form id="showtime-filter-form" method="GET"
          class="flex flex-wrap items-center gap-2">

        <div class="w-44">
            <select id="showtime-movie-filter" name="movie_id" class="showtime-select text-xs">
                <option value="">Tất cả phim</option>
                @foreach($movies as $m)
                    <option value="{{ $m->id }}" @selected(request('movie_id') == $m->id)>
                        {{ $m->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-56">
            <select id="showtime-room-filter" name="room_id" class="showtime-select text-xs">
                <option value="">Tất cả phòng / rạp</option>
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}" @selected(request('room_id') == $r->id)>
                        Phòng {{ $r->name }} - {{ optional($r->cinema)->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-40">
            <input
                id="showtime-date-filter"
                type="date"
                name="date"
                value="{{ request('date') }}"
                class="showtime-search text-xs"
            >
        </div>

        <div class="w-32">
            <input
                id="showtime-from-time-filter"
                type="time"
                name="from_time"
                value="{{ request('from_time') }}"
                class="showtime-search text-xs"
                placeholder="Từ giờ"
            >
        </div>

        <div class="w-32">
            <input
                id="showtime-to-time-filter"
                type="time"
                name="to_time"
                value="{{ request('to_time') }}"
                class="showtime-search text-xs"
                placeholder="Đến giờ"
            >
        </div>
        <div>
            <a href="{{ route('admin.showtimes.index') }}"
               class="showtime-btn showtime-btn-primary text-xs"
               style="background: linear-gradient(to right, #f43f5e, #fb7185); color: #020617;">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="showtime-card overflow-x-auto">
    <table class="showtime-table">
        <thead>
        <tr>
            <th>Phim</th>
            <th>Rạp / Phòng</th>
            <th>Giờ bắt đầu</th>
            <th>Giờ kết thúc</th>
            <th>Giá vé</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($showtimes as $showtime)
            <tr data-showtime-row>
                <td>
                    <div class="font-semibold text-slate-50">
                        {{ optional($showtime->movie)->title ?? '—' }}
                    </div>
                    <div class="text-[11px] text-slate-400">
                        ID phim: {{ $showtime->movie_id }}
                    </div>
                </td>
                <td class="text-xs text-slate-300">
                    <div>
                        {{ optional(optional($showtime->room)->cinema)->name ?? '—' }}
                    </div>
                    <div class="text-[11px] text-slate-400">
                        Phòng: {{ optional($showtime->room)->name ?? '—' }}
                    </div>
                </td>
                <td class="text-xs">
                    {{ optional($showtime->start_time)->format('d/m/Y H:i') ?? '—' }}
                </td>
                <td class="text-xs">
                    {{ optional($showtime->end_time)->format('d/m/Y H:i') ?? '—' }}
                </td>
                <td class="text-xs text-emerald-300">
                    <span class="showtime-badge bg-emerald-500/15 border border-emerald-500/40">
                        {{ number_format($showtime->price, 0, ',', '.') }} đ
                    </span>
                </td>
                <td class="text-right text-xs">
                    <a href="{{ route('admin.showtimes.show', $showtime) }}"
                       class="text-sky-400 hover:text-sky-300 mr-2">
                        Xem
                    </a>
                    <a href="{{ route('admin.showtimes.edit', $showtime) }}"
                       class="text-emerald-400 hover:text-emerald-300 mr-2">
                        Sửa
                    </a>
                    <form action="{{ route('admin.showtimes.destroy', $showtime) }}"
                          method="POST" class="inline-block"
                          onsubmit="return confirm('Xóa suất chiếu này?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-rose-400 hover:text-rose-300">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-sm text-slate-400 py-4">
                    Chưa có suất chiếu nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $showtimes->links() }}
</div>
@endsection
