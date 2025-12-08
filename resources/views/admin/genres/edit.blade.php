@extends('admin.layouts.app')

@section('title', 'Sửa thể loại')
@section('page_title', 'Sửa thể loại phim')
@section('page_subtitle', 'Chỉnh sửa: '.$genre->name)

@section('styles')
<style>
    .genre-form-card {
        max-width: 640px;
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .genre-label {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9ca3af;
        margin-bottom: 4px;
    }
    .genre-input, .genre-textarea {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.95);
        padding: 8px 10px;
        font-size: 0.85rem;
        color: #e5e7eb;
        outline: none;
    }
    .genre-input:focus, .genre-textarea:focus {
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
    .genre-btn-outline {
        border: 1px solid #4b5563;
        background: transparent;
        color: #e5e7eb;
    }
    .genre-btn-outline:hover {
        background: rgba(31,41,55,0.9);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const nameInput = document.getElementById('genre-name');
    const slugInput = document.getElementById('genre-slug');
    const autoSlug = document.getElementById('genre-auto-slug');

    if (nameInput && slugInput && autoSlug) {
        const makeSlug = str => {
            return str
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
        };

        autoSlug.addEventListener('change', () => {
            if (autoSlug.checked) {
                slugInput.value = makeSlug(nameInput.value);
            }
        });

        nameInput.addEventListener('input', () => {
            if (autoSlug.checked) {
                slugInput.value = makeSlug(nameInput.value);
            }
        });
    }
});
</script>
@endsection

@section('content')
<div class="genre-form-card">
    @if($errors->any())
        <div class="mb-4 text-xs text-rose-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.genres.update', $genre) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="genre-label">Tên thể loại</label>
            <input id="genre-name" type="text" name="name"
                   value="{{ old('name', $genre->name) }}" class="genre-input">
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="genre-label mb-0">Slug</label>
                <label class="flex items-center gap-1 text-[11px] text-slate-400">
                    <input type="checkbox" id="genre-auto-slug" class="rounded border-slate-600">
                    <span>Cập nhật từ tên</span>
                </label>
            </div>
            <input id="genre-slug" type="text" name="slug"
                   value="{{ old('slug', $genre->slug) }}" class="genre-input">
        </div>

        <div>
            <label class="genre-label">Mô tả</label>
            <textarea name="description" rows="4"
                      class="genre-textarea"
                      placeholder="Mô tả ngắn về thể loại này...">{{ old('description', $genre->description) }}</textarea>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.genres.index') }}"
               class="genre-btn genre-btn-outline">
                Hủy
            </a>
            <button type="submit" class="genre-btn genre-btn-primary">
                Cập nhật thể loại
            </button>
        </div>
    </form>
</div>
@endsection
