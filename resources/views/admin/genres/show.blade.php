@extends('admin.layouts.app')

@section('title', 'Chi tiết thể loại')
@section('page_title', 'Chi tiết thể loại')
@section('page_subtitle', 'Thể loại: '.$genre->name)

@section('styles')
<style>
    .detail-card {
        max-width: 640px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .detail-label { font-size:0.75rem; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; }
    .detail-text { font-size:0.85rem; color:#e5e7eb; }
</style>
@endsection

@section('content')
<div class="detail-card">
    <div class="mb-4">
        <div class="text-sm text-slate-400 mb-1">ID: {{ $genre->id }}</div>
        <div class="text-xl font-semibold">{{ $genre->name }}</div>
        <div class="text-xs text-slate-400 mt-1">
            Slug: {{ $genre->slug }}
        </div>
    </div>

    <div class="space-y-3">
        <div>
            <div class="detail-label mb-1">Mô tả</div>
            <div class="detail-text">
                {{ $genre->description ?: 'Không có mô tả.' }}
            </div>
        </div>

        <div>
            <div class="detail-label mb-1">Thời gian</div>
            <div class="detail-text text-xs text-slate-400">
                Tạo lúc: {{ optional($genre->created_at)->format('d/m/Y H:i') ?? '—' }}<br>
                Cập nhật: {{ optional($genre->updated_at)->format('d/m/Y H:i') ?? '—' }}
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <a href="{{ route('admin.genres.index') }}" class="text-xs text-slate-400 hover:text-slate-200">
            ← Quay lại danh sách
        </a>
        <a href="{{ route('admin.genres.edit', $genre) }}"
           class="px-3 py-1.5 rounded-full text-xs font-medium
                  bg-gradient-to-r from-emerald-500 to-sky-500 text-slate-900">
            Sửa thể loại
        </a>
    </div>
</div>
@endsection
