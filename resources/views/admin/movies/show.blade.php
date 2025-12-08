@extends('admin.layouts.app')

@section('title', 'Chi tiết phim')
@section('page_title', 'Chi tiết phim')
@section('page_subtitle', 'Phim: '.$movie->title)

@section('styles')
<style>
    .movie-detail-card {
        background: rgba(15,23,42,0.92);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 16px;
        max-width: 960px;
    }
    .detail-label { font-size:0.75rem; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; }
    .detail-text { font-size:0.85rem; color:#e5e7eb; }
    .movie-badge {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
    }
</style>
@endsection

@section('content')
<div class="movie-detail-card">
    <div>
        @if($movie->poster_url)
            <img src="{{ $movie->poster_url }}"
                 class="w-[120px] h-[180px] rounded-xl object-cover border border-slate-700">
        @else
            <div class="w-[120px] h-[180px] rounded-xl border border-dashed border-slate-600
                        flex items-center justify-center text-[11px] text-slate-500">
                No poster
            </div>
        @endif
        <div class="mt-3 text-xs text-slate-400">
            ID: {{ $movie->id }}<br>
            Slug: {{ $movie->slug }}
        </div>
    </div>

    <div class="space-y-3">
        <div>
            <div class="text-xl font-semibold">{{ $movie->title }}</div>
            <div class="mt-1 text-xs text-slate-400">
                Thể loại: {{ optional($movie->genre)->name ?? '—' }}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <div class="detail-label mb-1">Thời lượng</div>
                <div class="detail-text">
                    {{ $movie->duration_minutes }} phút
                </div>
            </div>
            <div>
                <div class="detail-label mb-1">Ngày khởi chiếu</div>
                <div class="detail-text">
                    {{ optional($movie->release_date)->format('d/m/Y') ?? '—' }}
                </div>
            </div>
            <div>
                <div class="detail-label mb-1">Giới hạn tuổi</div>
                <div class="detail-text">
                    {{ $movie->age_rating ?? '—' }}
                </div>
            </div>
            <div>
                <div class="detail-label mb-1">Trạng thái</div>
                <div class="detail-text">
                    @if($movie->is_featured)
                        <span class="movie-badge bg-amber-500/20 text-amber-300 border border-amber-500/60">
                            Phim nổi bật
                        </span>
                    @else
                        <span class="movie-badge bg-slate-700/70 text-slate-100 border border-slate-500/80">
                            Phim thường
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="detail-label mb-1">Mô tả</div>
            <div class="detail-text text-sm leading-relaxed">
                {{ $movie->description ?: 'Chưa có mô tả.' }}
            </div>
        </div>

        @if($movie->banner_url)
            <div>
                <div class="detail-label mb-1">Banner</div>
                <img src="{{ $movie->banner_url }}"
                     class="w-full h-[120px] object-cover rounded-xl border border-slate-700">
            </div>
        @endif

        <div class="text-xs text-slate-500 pt-2">
            Tạo lúc: {{ optional($movie->created_at)->format('d/m/Y H:i') ?? '—' }}<br>
            Cập nhật: {{ optional($movie->updated_at)->format('d/m/Y H:i') ?? '—' }}
        </div>

        <div class="flex justify-between pt-2">
            <a href="{{ route('admin.movies.index') }}" class="text-xs text-slate-400 hover:text-slate-200">
                ← Quay lại danh sách
            </a>
            <a href="{{ route('admin.movies.edit', $movie) }}"
               class="px-3 py-1.5 rounded-full text-xs font-medium
                      bg-gradient-to-r from-emerald-500 to-sky-500 text-slate-900">
                Sửa phim
            </a>
        </div>
    </div>
</div>
@endsection
