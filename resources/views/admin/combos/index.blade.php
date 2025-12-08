@extends('admin.layouts.app')

@section('title', 'Combo bắp nước')
@section('page_title', 'Combo bắp nước')
@section('page_subtitle', 'Quản lý danh sách combo đồ ăn & thức uống')

@section('styles')
<style>
    .combo-card {
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .combo-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .combo-table th {
        text-align: left;
        padding: 8px 10px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: #020617;
        color: #9ca3af;
        border-bottom: 1px solid #1f2937;
    }
    .combo-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
    }
    .combo-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }
    .combo-badge {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
    }
    .combo-input {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .combo-input:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .combo-select {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .combo-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .combo-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .combo-btn-primary:hover { filter: brightness(1.08); }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('combo-search');
    const statusSelect = document.getElementById('combo-status-filter');

    function applyFilter() {
        const keyword = (searchInput?.value || '').toLowerCase();
        const status = statusSelect?.value || '';

        document.querySelectorAll('[data-combo-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            const active = row.getAttribute('data-combo-active');

            let ok = true;
            if (keyword && !text.includes(keyword)) ok = false;
            if (status !== '' && status !== active) ok = false;

            row.style.display = ok ? '' : 'none';
        });
    }

    if (searchInput)  searchInput.addEventListener('input', applyFilter);
    if (statusSelect) statusSelect.addEventListener('change', applyFilter);
});
</script>
@endsection

@section('header_actions')
<a href="{{ route('admin.combos.create') }}"
   class="combo-btn combo-btn-primary inline-flex items-center gap-1">
    + Thêm combo
</a>
@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <div class="w-full md:w-64">
        <input id="combo-search" type="text" class="combo-input"
               placeholder="Tìm theo tên / mô tả combo...">
    </div>

    <div class="w-40">
        <select id="combo-status-filter" class="combo-select text-xs">
            <option value="">Tất cả trạng thái</option>
            <option value="1">Đang bán</option>
            <option value="0">Ngừng bán</option>
        </select>
    </div>
</div>

<div class="combo-card overflow-x-auto">
    <table class="combo-table">
        <thead>
        <tr>
            <th>Ảnh</th>
            <th>Tên combo</th>
            <th>Giá</th>
            <th>Mô tả</th>
            <th>Trạng thái</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($combos as $combo)
            <tr data-combo-row data-combo-active="{{ $combo->is_active ? '1' : '0' }}">
                <td>
                    @if($combo->image_url)
                        <img src="{{ Storage::url($combo->image_url) }}"
                             class="h-14 w-14 rounded-xl object-cover border border-slate-700">
                    @else
                        <span class="text-[11px] text-slate-500">N/A</span>
                    @endif
                </td>
                <td class="align-top">
                    <div class="font-semibold text-slate-50">{{ $combo->name }}</div>
                </td>
                <td class="align-top text-xs text-emerald-300">
                    {{ number_format($combo->price, 0, ',', '.') }} đ
                </td>
                <td class="align-top text-xs text-slate-300">
                    {{ \Illuminate\Support\Str::limit($combo->description, 80) }}
                </td>
                <td class="align-top">
                    @if($combo->is_active)
                        <span class="combo-badge bg-emerald-500/20 text-emerald-300 border border-emerald-500/50">
                            Đang bán
                        </span>
                    @else
                        <span class="combo-badge bg-slate-700/60 text-slate-300 border border-slate-500/80">
                            Ngừng bán
                        </span>
                    @endif
                </td>
                <td class="align-top text-right">
                    <a href="{{ route('admin.combos.edit', $combo) }}"
                       class="text-xs text-sky-400 hover:text-sky-300 mr-3">Sửa</a>
                    <form action="{{ route('admin.combos.destroy', $combo) }}"
                          method="POST" class="inline-block"
                          onsubmit="return confirm('Xóa combo này?');">
                        @csrf @method('DELETE')
                        <button class="text-xs text-rose-400 hover:text-rose-300">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-sm text-slate-400 py-4">
                    Chưa có combo nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $combos->links() }}
</div>
@endsection
