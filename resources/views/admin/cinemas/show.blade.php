@extends('admin.layouts.app')

@section('title', 'Chi tiết rạp')
@section('page_title', 'Chi tiết rạp')
@section('page_subtitle', 'Thông tin rạp: '.$cinema->name)

@section('styles')
<style>
    .detail-card {
        max-width: 720px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .detail-row {
        display:flex;
        justify-content:space-between;
        padding:6px 0;
        font-size:0.85rem;
        gap:12px;
    }
    .detail-label {
        color:#9ca3af;
        font-size:0.75rem;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    .detail-value {
        color:#e5e7eb;
        text-align:right;
    }
</style>
@endsection

@section('content')
<div class="detail-card">
    <div class="mb-4">
        <div class="text-sm text-slate-400 mb-1">ID: {{ $cinema->id }}</div>
        <div class="text-xl font-semibold">{{ $cinema->name }}</div>
    </div>

    <div class="space-y-2">
        <div class="detail-row">
            <span class="detail-label">Địa chỉ</span>
            <span class="detail-value max-w-[360px]">
                {{ $cinema->address ?? '—' }}
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Thành phố</span>
            <span class="detail-value">
                {{ $cinema->city ?? '—' }}
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ngày tạo</span>
            <span class="detail-value">
                {{ optional($cinema->created_at)->format('d/m/Y H:i') ?? '—' }}
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Cập nhật</span>
            <span class="detail-value">
                {{ optional($cinema->updated_at)->format('d/m/Y H:i') ?? '—' }}
            </span>
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <a href="{{ route('admin.cinemas.index') }}"
           class="text-xs text-slate-400 hover:text-slate-200">
            ← Quay lại danh sách
        </a>
        <a href="{{ route('admin.cinemas.edit', $cinema) }}"
           class="px-3 py-1.5 rounded-full text-xs font-medium
                  bg-gradient-to-r from-emerald-500 to-sky-500 text-slate-900">
            Sửa rạp
        </a>
    </div>
</div>
@endsection
