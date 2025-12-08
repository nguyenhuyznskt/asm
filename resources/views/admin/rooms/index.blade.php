@extends('admin.layouts.app')

@section('title', 'Phòng chiếu')
@section('page_title', 'Phòng chiếu')
@section('page_subtitle', 'Quản lý danh sách phòng chiếu theo từng rạp')

@section('styles')
<style>
    .room-card {
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .room-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .room-table th {
        text-align: left;
        padding: 8px 10px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: #020617;
        color: #9ca3af;
        border-bottom: 1px solid #1f2937;
    }
    .room-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
    }
    .room-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }
    .room-search,
    .room-select {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .room-search:focus,
    .room-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .room-badge {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
    }
    .room-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .room-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .room-btn-primary:hover { filter: brightness(1.08); }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('room-search');
    const cinemaSelect = document.getElementById('room-cinema-filter');

    function applyFilter() {
        const keyword = (searchInput?.value || '').toLowerCase();
        document.querySelectorAll('[data-room-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = keyword && !text.includes(keyword) ? 'none' : '';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
    }

    if (cinemaSelect) {
        cinemaSelect.addEventListener('change', () => {
            cinemaSelect.form && cinemaSelect.form.submit();
        });
    }
});
</script>
@endsection

@section('header_actions')
<a href="{{ route('admin.rooms.create') }}"
   class="room-btn room-btn-primary inline-flex items-center gap-1">
    + Thêm phòng
</a>
@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <div class="w-full md:w-72">
        <input id="room-search" type="text" class="room-search"
               placeholder="Tìm theo tên phòng / rạp trên trang hiện tại...">
    </div>

    <form method="GET" class="flex items-center gap-2">
        <div class="w-52">
            <select id="room-cinema-filter" name="cinema_id" class="room-select text-xs">
                <option value="">Tất cả rạp (lọc server)</option>
                @foreach($cinemas as $c)
                    <option value="{{ $c->id }}" @selected(request('cinema_id') == $c->id)>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="room-card overflow-x-auto">
    <table class="room-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Phòng</th>
            <th>Rạp</th>
            <th>Tổng ghế</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($rooms as $room)
            <tr data-room-row>
                <td>{{ $room->id }}</td>
                <td class="font-semibold text-slate-50">
                    {{ $room->name }}
                </td>
                <td class="text-xs text-slate-300">
                    {{ optional($room->cinema)->name ?? '—' }}
                </td>
                <td class="text-xs">
                    <span class="room-badge bg-slate-800/80 text-slate-100 border border-slate-600">
                        {{ $room->total_seats }} ghế
                    </span>
                </td>
                <td class="text-right">
                    <a href="{{ route('admin.rooms.edit', $room) }}"
                       class="text-xs text-sky-400 hover:text-sky-300 mr-3">
                        Sửa
                    </a>
                    <form action="{{ route('admin.rooms.destroy', $room) }}"
                          method="POST" class="inline-block"
                          onsubmit="return confirm('Xóa phòng này?');">
                        @csrf @method('DELETE')
                        <button class="text-xs text-rose-400 hover:text-rose-300">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-sm text-slate-400 py-4">
                    Chưa có phòng chiếu nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $rooms->links() }}
</div>
@endsection
