@extends('admin.layouts.app')

@section('title', 'Rạp chiếu')
@section('page_title', 'Rạp chiếu')
@section('page_subtitle', 'Quản lý danh sách rạp chiếu phim')

@section('styles')
<style>
    .cinema-card {
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .cinema-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .cinema-table th {
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
    .cinema-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
        vertical-align: top;
    }
    .cinema-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }
    .cinema-search {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .cinema-search:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .cinema-city-pill {
        display: inline-block;
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
        background: rgba(37,99,235,0.2);
        color: #bfdbfe;
        border: 1px solid rgba(59,130,246,0.6);
    }
    .cinema-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .cinema-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .cinema-btn-primary:hover { filter: brightness(1.08); }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('cinema-search');

    function filterClient() {
        const keyword = (searchInput?.value || '').toLowerCase();
        document.querySelectorAll('[data-cinema-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = keyword && !text.includes(keyword) ? 'none' : '';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterClient);
    }
});
</script>
@endsection

@section('header_actions')
<a href="{{ route('admin.cinemas.create') }}"
   class="cinema-btn cinema-btn-primary inline-flex items-center gap-1">
    + Thêm rạp
</a>
@endsection

@section('content')
<div class="mb-4 w-full md:w-72">
    <input id="cinema-search" type="text" class="cinema-search"
           placeholder="Tìm theo tên, địa chỉ, thành phố...">
</div>

<div class="cinema-card overflow-x-auto">
    <table class="cinema-table">
        <thead>
        <tr>
            <th>Rạp</th>
            <th>Địa chỉ</th>
            <th>Thành phố</th>
            <th>Ngày tạo</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($cinemas as $cinema)
            <tr data-cinema-row>
                <td class="text-xs text-slate-200 max-w-[200px]">
                    <div class="font-semibold text-slate-50">
                        {{ $cinema->name }}
                    </div>
                    <div class="text-[11px] text-slate-500 mt-0.5">
                        ID: {{ $cinema->id }}
                    </div>
                </td>
                <td class="text-xs text-slate-300 max-w-[260px]">
                    {{ $cinema->address ?? '—' }}
                </td>
                <td class="text-xs">
                    @if($cinema->city)
                        <span class="cinema-city-pill">
                            {{ $cinema->city }}
                        </span>
                    @else
                        <span class="text-[11px] text-slate-500">—</span>
                    @endif
                </td>
                <td class="text-xs text-slate-400">
                    {{ optional($cinema->created_at)->format('d/m/Y H:i') ?? '—' }}
                </td>
                <td class="text-right text-xs">
                    <a href="{{ route('admin.cinemas.show', $cinema) }}"
                       class="text-sky-400 hover:text-sky-300 mr-2">
                        Xem
                    </a>
                    <a href="{{ route('admin.cinemas.edit', $cinema) }}"
                       class="text-emerald-400 hover:text-emerald-300 mr-2">
                        Sửa
                    </a>
                    <form action="{{ route('admin.cinemas.destroy', $cinema) }}"
                          method="POST" class="inline-block"
                          onsubmit="return confirm('Xóa rạp này? Hãy chắc chắn không còn phòng / suất chiếu liên quan.');">
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
                <td colspan="5" class="text-center text-sm text-slate-400 py-4">
                    Chưa có rạp nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $cinemas->links() }}
</div>
@endsection
