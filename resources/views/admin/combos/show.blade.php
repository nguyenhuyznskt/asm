@extends('admin.layouts.app')

@section('title', 'Chi tiết combo')
@section('page_title', 'Chi tiết combo')
@section('page_subtitle', 'Combo: '.$combo->name)

@section('styles')
<style>
    .detail-card {
        max-width: 720px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
        display:grid;
        grid-template-columns: 140px 1fr;
        gap:16px;
    }
    .detail-label { font-size:0.75rem; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; }
    .detail-text { font-size:0.85rem; color:#e5e7eb; }
    .badge {
        border-radius:999px;
        padding:2px 8px;
        font-size:0.7rem;
    }
</style>
@endsection

@section('content')
<div class="detail-card">
    <div>
        @if($combo->image_url)
            <img src="{{ $combo->image_url }}"
                 class="w-[140px] h-[140px] rounded-2xl object-cover border border-slate-700">
        @else
            <div class="w-[140px] h-[140px] rounded-2xl border border-dashed border-slate-600
                        flex items-center justify-center text-[11px] text-slate-500">
                No image
            </div>
        @endif
        <div class="mt-3 text-xs text-slate-400">
            ID: {{ $combo->id }}
        </div>
    </div>

    <div class="space-y-3">
        <div>
            <div class="text-xl font-semibold">{{ $combo->name }}</div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <div class="detail-label mb-1">Giá</div>
                <div class="detail-text text-emerald-300">
                    {{ number_format($combo->price, 0, ',', '.') }} đ
                </div>
            </div>
            <div>
                <div class="detail-label mb-1">Trạng thái</div>
                <div class="detail-text">
                    @if($combo->is_active)
                        <span class="badge bg-emerald-500/15 text-emerald-300 border border-emerald-500/50">
                            Đang bán
                        </span>
                    @else
                        <span class="badge bg-slate-700/70 text-slate-100 border border-slate-500/80">
                            Ngừng bán
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="detail-label mb-1">Mô tả</div>
            <div class="detail-text">
                {{ $combo->description ?: 'Chưa có mô tả.' }}
            </div>
        </div>

        <div class="text-xs text-slate-500 pt-2">
            Tạo lúc: {{ optional($combo->created_at)->format('d/m/Y H:i') ?? '—' }}<br>
            Cập nhật: {{ optional($combo->updated_at)->format('d/m/Y H:i') ?? '—' }}
        </div>

        <div class="flex justify-between pt-2">
            <a href="{{ route('admin.combos.index') }}" class="text-xs text-slate-400 hover:text-slate-200">
                ← Quay lại danh sách
            </a>
            <a href="{{ route('admin.combos.edit', $combo) }}"
               class="px-3 py-1.5 rounded-full text-xs font-medium
                      bg-gradient-to-r from-emerald-500 to-sky-500 text-slate-900">
                Sửa combo
            </a>
        </div>
    </div>
</div>
@endsection
