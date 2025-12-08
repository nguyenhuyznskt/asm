@extends('admin.layouts.app')

@section('title', 'Chi tiết suất chiếu')
@section('page_title', 'Chi tiết suất chiếu')
@section('page_subtitle', 'Suất chiếu ID: '.$showtime->id)

@section('styles')
<style>
    .detail-card {
        max-width: 720px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .detail-row { display:flex; justify-content:space-between; padding:6px 0; font-size:0.85rem; }
    .detail-label { color:#9ca3af; font-size:0.75rem; text-transform:uppercase; letter-spacing:.08em; }
    .detail-value { color:#e5e7eb; text-align:right; }
    .badge {
        border-radius:999px;
        padding:2px 8px;
        font-size:0.7rem;
    }
</style>
@endsection

@section('content')
<div class="detail-card">
    <div class="mb-4">
        <div class="text-sm text-slate-400 mb-1">ID: {{ $showtime->id }}</div>
        <div class="text-lg font-semibold">
            {{ optional($showtime->movie)->title ?? 'Không xác định' }}
        </div>
        <div class="text-xs text-slate-400 mt-1">
            Rạp: {{ optional(optional($showtime->room)->cinema)->name ?? '—' }} ·
            Phòng: {{ optional($showtime->room)->name ?? '—' }}
        </div>
    </div>

    <div class="space-y-2">
        <div class="detail-row">
            <span class="detail-label">Giờ bắt đầu</span>
            <span class="detail-value">
                {{ optional($showtime->start_time)->format('d/m/Y H:i') ?? '—' }}
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Giờ kết thúc</span>
            <span class="detail-value">
                {{ optional($showtime->end_time)->format('d/m/Y H:i') ?? '—' }}
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Giá vé</span>
            <span class="detail-value text-emerald-300">
                {{ number_format($showtime->price, 0, ',', '.') }} đ
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ngày tạo</span>
            <span class="detail-value text-xs text-slate-400">
                {{ optional($showtime->created_at)->format('d/m/Y H:i') ?? '—' }}
            </span>
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <a href="{{ route('admin.showtimes.index') }}" class="text-xs text-slate-400 hover:text-slate-200">
            ← Quay lại danh sách
        </a>
        <a href="{{ route('admin.showtimes.edit', $showtime) }}"
           class="px-3 py-1.5 rounded-full text-xs font-medium
                  bg-gradient-to-r from-emerald-500 to-sky-500 text-slate-900">
            Sửa suất chiếu
        </a>
    </div>
</div>
@endsection
