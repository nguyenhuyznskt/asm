@extends('admin.layouts.app')

@section('title', 'Quản lý phim')
@section('page_title', 'Phim')
@section('page_subtitle', 'Quản lý danh sách phim trong hệ thống')

@section('styles')
<style>
    .movie-card {
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .movie-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .movie-table th {
        text-align: left;
        padding: 8px 10px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: #020617;
        color: #9ca3af;
        border-bottom: 1px solid #1f2937;
    }
    .movie-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
    }
    .movie-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }
    .movie-search {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .movie-search:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .movie-select {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .movie-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .movie-badge {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
    }
    .movie-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .movie-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .movie-btn-primary:hover { filter: brightness(1.08); }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('movie-search');
    const genreSelect = document.getElementById('movie-genre-filter');

    function filterClientSide() {
        const keyword = (searchInput?.value || '').toLowerCase();
        document.querySelectorAll('[data-movie-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = keyword && !text.includes(keyword) ? 'none' : '';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterClientSide);
    }

    if (genreSelect) {
        genreSelect.addEventListener('change', () => {
            genreSelect.form && genreSelect.form.submit();
        });
    }
});
</script>
@endsection

@section('header_actions')
<a href="{{ route('admin.movies.create') }}"
   class="movie-btn movie-btn-primary inline-flex items-center gap-1">
    + Thêm phim
</a>
@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <div class="w-full md:w-72">
        <input id="movie-search" type="text" class="movie-search"
               placeholder="Tìm trong danh sách trên trang (tên, mô tả, thể loại)...">
    </div>

    <form method="GET" class="flex items-center gap-2">
        <div class="w-48">
            <select id="movie-genre-filter" name="genre_id" class="movie-select text-xs">
                <option value="">Tất cả thể loại (lọc server)</option>
                @foreach($genres as $g)
                    <option value="{{ $g->id }}" @selected(request('genre_id') == $g->id)>
                        {{ $g->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <a href="{{ route('admin.movies.index') }}"
               class="movie-btn movie-btn-primary text-xs"
               style="background: linear-gradient(to right, #f43f5e, #fb7185); color: #020617;">
                Reset
            </a>
        </div>
        
        
    </form>
</div>

<div class="movie-card overflow-x-auto">
    <table class="movie-table">
        <thead>
        <tr>
            <th>Poster</th>
            <th>Thông tin phim</th>
            <th>Thể loại</th>
            <th>Thời lượng</th>
            <th>Khởi chiếu</th>
            <th>Nổi bật</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($movies as $movie)
            <tr data-movie-row>
                <td>
                    @if($movie->poster_url)
    <img src="{{ Storage::url($movie->poster_url) }}"
         class="h-16 w-12 rounded-lg object-cover border border-slate-700">
@else
    <img src="{{ $movie->poster_url }}"
         class="h-16 w-12 rounded-lg object-cover border border-slate-700">
@endif
                </td>
                <td class="align-top">
                    <div class="font-semibold text-slate-50">{{ $movie->title }}</div>
                    <div class="text-[11px] text-slate-400 truncate max-w-[260px]">
                        {{ $movie->slug }}
                    </div>
                    <div class="mt-1 text-[11px] text-slate-400 line-clamp-2 max-w-xs">
                        {{ \Illuminate\Support\Str::limit($movie->description, 90) }}
                    </div>
                </td>
                <td class="align-top text-xs text-slate-300">
                    {{ optional($movie->genre)->name ?? '—' }}
                </td>
                <td class="align-top text-xs">
                    {{ $movie->duration_minutes }} phút
                </td>
                <td class="align-top text-xs">
                    {{ optional($movie->release_date)->format('d/m/Y') ?? '—' }}
                </td>
                <td class="align-top">
                    @if($movie->is_featured)
                        <span class="movie-badge bg-amber-500/20 text-amber-300 border border-amber-500/50">
                            Nổi bật
                        </span>
                    @else
                        <span class="movie-badge bg-slate-700/60 text-slate-300 border border-slate-500/80">
                            Thường
                        </span>
                    @endif
                </td>
                <td class="align-top text-right">
                    <a href="{{ route('admin.movies.edit', $movie) }}"
                       class="text-xs text-sky-400 hover:text-sky-300 mr-3">Sửa</a>
                    <form action="{{ route('admin.movies.destroy', $movie) }}"
                          method="POST" class="inline-block"
                          onsubmit="return confirm('Xóa phim này?');">
                        @csrf @method('DELETE')
                        <button class="text-xs text-rose-400 hover:text-rose-300">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-sm text-slate-400 py-4">
                    Chưa có phim nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $movies->links() }}
</div>
@endsection
