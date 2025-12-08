@extends('admin.layouts.app')

@section('title', 'Thể loại phim')
@section('page_title', 'Thể loại phim')
@section('page_subtitle', 'Quản lý danh sách thể loại phim')

@section('styles')
<style>
    .genre-card {
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .genre-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .genre-table th {
        text-align: left;
        padding: 8px 10px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: #020617;
        color: #9ca3af;
        border-bottom: 1px solid #1f2937;
    }
    .genre-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
    }
    .genre-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }
    .genre-search {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .genre-search:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .genre-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .genre-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .genre-btn-primary:hover { filter: brightness(1.08); }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('genre-search');
    if (!searchInput) return;

    function applyFilter() {
        const keyword = searchInput.value.toLowerCase();
        document.querySelectorAll('[data-genre-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = keyword && !text.includes(keyword) ? 'none' : '';
        });
    }

    searchInput.addEventListener('input', applyFilter);
});
</script>
@endsection

@section('header_actions')
<a href="{{ route('admin.genres.create') }}"
   class="genre-btn genre-btn-primary inline-flex items-center gap-1">
    + Thêm thể loại
</a>
@endsection

@section('content')
<div class="mb-4 w-full md:w-72">
    <input id="genre-search" type="text" class="genre-search"
           placeholder="Tìm theo tên / slug / mô tả...">
</div>

<div class="genre-card overflow-x-auto">
    @php use Illuminate\Support\Str; @endphp
    <table class="genre-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên thể loại</th>
            <th>Slug</th>
            <th>Mô tả</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($genres as $genre)
            <tr data-genre-row>
                <td>{{ $genre->id }}</td>
                <td class="font-semibold text-slate-50">{{ $genre->name }}</td>
                <td class="text-xs text-slate-400">{{ $genre->slug }}</td>
                <td class="text-xs text-slate-300">
                    {{ Str::limit($genre->description, 80) }}
                </td>
                <td class="text-right">
                    <a href="{{ route('admin.genres.edit', $genre) }}"
                       class="text-xs text-sky-400 hover:text-sky-300 mr-3">Sửa</a>
                    <form action="{{ route('admin.genres.destroy', $genre) }}"
                          method="POST" class="inline-block"
                          onsubmit="return confirm('Xóa thể loại này?');">
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
                    Chưa có thể loại nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $genres->links() }}
</div>
@endsection
