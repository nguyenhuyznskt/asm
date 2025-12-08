@extends('admin.layouts.app')

@section('title', 'Thêm phim')
@section('page_title', 'Thêm phim')
@section('page_subtitle', 'Tạo mới phim trong hệ thống')

@section('styles')
<style>
    .movie-form-card {
        max-width: 980px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .movie-label {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9ca3af;
        margin-bottom: 4px;
    }
    .movie-input, .movie-textarea, .movie-select {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.95);
        padding: 8px 10px;
        font-size: 0.85rem;
        color: #e5e7eb;
        outline: none;
    }
    .movie-input:focus, .movie-textarea:focus, .movie-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .movie-switch {
        position: relative;
        width: 38px;
        height: 20px;
        border-radius: 999px;
        background: #4b5563;
        transition: background .2s;
        cursor: pointer;
    }
    .movie-switch span {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 16px;
        height: 16px;
        border-radius: 999px;
        background: #e5e7eb;
        transition: transform .2s;
    }
    .movie-switch-active { background: #22c55e; }
    .movie-switch-active span { transform: translateX(18px); }
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
    .movie-btn-outline {
        border: 1px solid #4b5563;
        background: transparent;
        color: #e5e7eb;
    }
    .movie-btn-outline:hover {
        background: rgba(31,41,55,0.9);
    }
    .movie-img-preview {
        border-radius: 16px;
        border: 1px solid #374151;
        object-fit: cover;
        background: radial-gradient(circle at top, #1f2937, #020617);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const titleInput   = document.getElementById('movie-title');
    const slugInput    = document.getElementById('movie-slug');
    const autoSlug     = document.getElementById('movie-auto-slug');
    const switchEl     = document.getElementById('movie-switch');
    const switchCheckbox = document.getElementById('movie-is-featured');
    const descInput    = document.getElementById('movie-description');
    const descCount    = document.getElementById('movie-description-count');

    const posterFile   = document.getElementById('movie-poster');
    const bannerFile   = document.getElementById('movie-banner');
    const posterImg    = document.getElementById('movie-poster-preview');
    const bannerImg    = document.getElementById('movie-banner-preview');

    const makeSlug = str => {
        return str
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    };

    // Auto slug
    if (titleInput && slugInput && autoSlug) {
        titleInput.addEventListener('input', () => {
            if (autoSlug.checked) {
                slugInput.value = makeSlug(titleInput.value);
            }
        });
        autoSlug.addEventListener('change', () => {
            if (autoSlug.checked) {
                slugInput.value = makeSlug(titleInput.value);
            }
        });
    }

    // Preview image từ file
    function bindFilePreview(input, imgEl, fallback) {
        if (!input || !imgEl) return;

        const updatePreview = () => {
            const file = input.files && input.files[0];
            if (!file) {
                imgEl.src = fallback;
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                imgEl.src = e.target.result;
            };
            reader.readAsDataURL(file);
        };

        input.addEventListener('change', updatePreview);
        // set fallback ban đầu
        imgEl.src = fallback;
    }

    bindFilePreview(
        posterFile,
        posterImg,
        'https://via.placeholder.com/300x450?text=Poster'
    );
    bindFilePreview(
        bannerFile,
        bannerImg,
        'https://via.placeholder.com/1200x400?text=Banner'
    );

    // Switch featured
    if (switchEl && switchCheckbox) {
        const syncSwitch = () => {
            switchEl.classList.toggle('movie-switch-active', switchCheckbox.checked);
        };
        switchEl.addEventListener('click', () => {
            switchCheckbox.checked = !switchCheckbox.checked;
            syncSwitch();
        });
        syncSwitch();
    }

    // Đếm ký tự mô tả
    if (descInput && descCount) {
        const updateCount = () => {
            descCount.textContent = descInput.value.length;
        };
        descInput.addEventListener('input', updateCount);
        updateCount();
    }
});
</script>
@endsection

@section('content')
<div class="movie-form-card">
    @if($errors->any())
        <div class="mb-4 text-xs text-rose-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.movies.store') }}" method="POST"
          enctype="multipart/form-data"
          class="grid md:grid-cols-2 gap-5">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="movie-label">Tên phim</label>
                <input id="movie-title" type="text" name="title"
                       value="{{ old('title') }}" class="movie-input">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="movie-label mb-0">Slug</label>
                    <label class="flex items-center gap-1 text-[11px] text-slate-400">
                        <input type="checkbox" id="movie-auto-slug"
                               class="rounded border-slate-600" checked>
                        <span>Tự sinh từ tên</span>
                    </label>
                </div>
                <input id="movie-slug" type="text" name="slug"
                       value="{{ old('slug') }}" class="movie-input"
                       placeholder="vd: avengers-endgame">
            </div>

            <div>
                <label class="movie-label">Thể loại</label>
                <select name="genre_id" class="movie-select">
                    <option value="">-- Chọn thể loại --</option>
                    @foreach($genres as $g)
                        <option value="{{ $g->id }}" @selected(old('genre_id') == $g->id)>
                            {{ $g->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="movie-label">Thời lượng (phút)</label>
                    <input type="number" name="duration_minutes"
                           value="{{ old('duration_minutes') }}" class="movie-input">
                </div>
                <div>
                    <label class="movie-label">Ngày khởi chiếu</label>
                    <input type="date" name="release_date"
       value="{{ old('release_date', now()->toDateString()) }}"
       min="{{ now()->toDateString() }}"
       class="movie-input">

                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="movie-label">Giới hạn tuổi</label>
                    <input type="text" name="age_rating"
                           value="{{ old('age_rating', '13+') }}" class="movie-input">
                </div>
                <div class="flex items-center gap-2 mt-6">
                    <div id="movie-switch" class="movie-switch">
                        <span></span>
                    </div>
                    <span class="text-xs text-slate-300">Đánh dấu là phim nổi bật</span>
                    <input type="checkbox" id="movie-is-featured"
                           name="is_featured" value="1" class="hidden"
                           {{ old('is_featured', 0) ? 'checked' : '' }}>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            {{-- Poster file --}}
            <div>
                <label class="movie-label">Poster (ảnh)</label>
                <input id="movie-poster" type="file" name="poster"
                       class="movie-input"
                       accept="image/*">
                <p class="mt-1 text-[11px] text-slate-500">
                    Khuyến nghị tỉ lệ ~300x450 trở lên, định dạng: jpg, jpeg, png, webp, gif.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <img id="movie-poster-preview" src="" alt="Poster preview"
                     class="movie-img-preview w-[80px] h-[120px]">
            </div>

            {{-- Banner file --}}
            <div>
                <label class="movie-label">Banner (ảnh ngang)</label>
                <input id="movie-banner" type="file" name="banner"
                       class="movie-input"
                       accept="image/*">
                <p class="mt-1 text-[11px] text-slate-500">
                    Khuyến nghị tỉ lệ ~1200x400 trở lên để hiển thị đẹp.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <img id="movie-banner-preview" src="" alt="Banner preview"
                     class="movie-img-preview w-full h-[80px]">
            </div>

            <div>
                <label class="movie-label">Mô tả</label>
                <textarea id="movie-description" name="description" rows="4"
                          class="movie-textarea"
                          placeholder="Tóm tắt nội dung chính của phim...">{{ old('description') }}</textarea>
                <div class="mt-1 text-[11px] text-slate-500">
                    Số ký tự: <span id="movie-description-count">0</span>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.movies.index') }}" class="movie-btn movie-btn-outline">
                Hủy
            </a>
            <button type="submit" class="movie-btn movie-btn-primary">
                Lưu phim
            </button>
        </div>
    </form>
</div>
@endsection
