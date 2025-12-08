@extends('admin.layouts.app')

@section('title', 'Bình luận')
@section('page_title', 'Bình luận')
@section('page_subtitle', 'Quản lý bình luận phim của người dùng')

@section('styles')
<style>
    .cmt-card {
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .cmt-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .cmt-table th {
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
    .cmt-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
        vertical-align: top;
    }
    .cmt-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }
    .cmt-search,
    .cmt-select {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .cmt-search:focus,
    .cmt-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .cmt-rating {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
    }
    .cmt-chip {
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
    const searchInput = document.getElementById('cmt-search');
    const form        = document.getElementById('cmt-filter-form');
    const movieSelect = document.getElementById('cmt-movie-filter');

    function filterClient() {
        const keyword = (searchInput?.value || '').toLowerCase();
        document.querySelectorAll('[data-cmt-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = keyword && !text.includes(keyword) ? 'none' : '';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterClient);
    }

    if (movieSelect && form) {
        movieSelect.addEventListener('change', () => form.submit());
    }
});
</script>
@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <div class="w-full md:w-72">
        <input id="cmt-search" type="text" class="cmt-search"
               placeholder="Tìm trên trang (tên, nội dung, IP)...">
    </div>

    <form id="cmt-filter-form" method="GET" class="flex items-center gap-2">
        <div class="w-56">
            <select id="cmt-movie-filter" name="movie_id" class="cmt-select text-xs">
                <option value="">Tất cả phim</option>
                @foreach($movies as $m)
                    <option value="{{ $m->id }}" @selected(request('movie_id') == $m->id)>
                        {{ $m->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="cmt-card overflow-x-auto">
    <table class="cmt-table">
        <thead>
        <tr>
            <th>Phim</th>
            <th>Người bình luận</th>
            <th>Nội dung</th>
            <th>Rating</th>
            <th>IP / Thời gian</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($comments as $comment)
            <tr data-cmt-row>
                <td class="text-xs text-slate-300 max-w-[180px]">
                    <div class="font-semibold text-slate-50 text-sm">
                        {{ optional($comment->movie)->title ?? '—' }}
                    </div>
                    <div class="text-[11px] text-slate-500">
                        ID: {{ $comment->movie_id }}
                    </div>
                </td>

                <td class="text-xs text-slate-300">
                    <div class="font-medium">{{ $comment->name }}</div>
                    <div class="flex items-center gap-1 mt-0.5 text-[11px] text-slate-500">
                        <span class="cmt-chip">
                            👍 {{ $comment->likes }}
                        </span>
                        <span class="cmt-chip">
                            👎 {{ $comment->dislikes }}
                        </span>
                    </div>
                </td>

                <td class="text-xs text-slate-200 max-w-[280px]">
                    {{ \Illuminate\Support\Str::limit($comment->content, 120) }}
                </td>

                <td class="text-xs">
                    <span class="cmt-rating bg-amber-500/20 text-amber-300 border border-amber-500/50">
                        {{ $comment->rating }} ★
                    </span>
                </td>

                <td class="text-[11px] text-slate-400">
                    IP: {{ $comment->ip_address ?? '—' }}<br>
                    {{ optional($comment->created_at)->format('d/m/Y H:i') ?? '—' }}
                </td>

                <td class="text-right text-xs">
                    {{-- <a href="{{ route('admin.comments.show', $comment) }}"
                       class="text-sky-400 hover:text-sky-300 mr-2">
                        Xem
                    </a> --}}
                    {{-- <a href="{{ route('admin.comments.edit', $comment) }}"
                       class="text-emerald-400 hover:text-emerald-300 mr-2">
                        Sửa
                    </a> --}}
                    <form action="{{ route('admin.comments.destroy', $comment) }}"
                          method="POST" class="inline-block"
                          onsubmit="return confirm('Xóa bình luận này?');">
                        @csrf @method('DELETE')
                        <button class="text-rose-400 hover:text-rose-300">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-sm text-slate-400 py-4">
                    Chưa có bình luận nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $comments->links() }}
</div>
@endsection
