@extends('admin.layouts.app')

@section('title', 'Chi tiết tài khoản')
@section('page_title', 'Chi tiết tài khoản')
@section('page_subtitle', 'User: '.$user->email)

@section('styles')
<style>
    .detail-card {
        max-width: 640px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .avatar-chip {
        width: 42px;
        height: 42px;
        border-radius:999px;
        background: radial-gradient(circle at top, #22c55e, #0ea5e9);
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:600;
        color:#020617;
    }
    .detail-row { display:flex; justify-content:space-between; padding:6px 0; font-size:0.85rem; }
    .detail-label { color:#9ca3af; font-size:0.75rem; text-transform:uppercase; letter-spacing:.08em; }
    .detail-value { color:#e5e7eb; text-align:right; }
    .badge {
        border-radius:999px;
        padding:2px 8px;
        font-size:0.7rem;
        border-width:1px;
        border-style:solid;
    }
</style>
@endsection

@section('content')
<div class="detail-card">
    <div class="flex items-center gap-3 mb-4">
        <div class="avatar-chip">
            {{ mb_strtoupper(mb_substr($user->name ?? $user->email, 0, 1)) }}
        </div>
        <div>
            <div class="text-lg font-semibold">
                {{ $user->name ?? '—' }}
            </div>
            <div class="text-xs text-slate-400">
                {{ $user->email }}
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <div class="detail-row">
            <span class="detail-label">ID</span>
            <span class="detail-value">{{ $user->id }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Vai trò</span>
            <span class="detail-value">
                @if($user->role === 'admin')
                    <span class="badge bg-amber-500/20 text-amber-300 border-amber-500/60">
                        Admin
                    </span>
                @else
                    <span class="badge bg-slate-700/70 text-slate-100 border-slate-500/80">
                        User
                    </span>
                @endif
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Ngày tạo</span>
            <span class="detail-value text-xs text-slate-400">
                {{ optional($user->created_at)->format('d/m/Y H:i') ?? '—' }}
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Cập nhật</span>
            <span class="detail-value text-xs text-slate-400">
                {{ optional($user->updated_at)->format('d/m/Y H:i') ?? '—' }}
            </span>
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <a href="{{ route('admin.users.index') }}" class="text-xs text-slate-400 hover:text-slate-200">
            ← Quay lại danh sách
        </a>
        <a href="{{ route('admin.users.edit', $user) }}"
           class="px-3 py-1.5 rounded-full text-xs font-medium
                  bg-gradient-to-r from-emerald-500 to-sky-500 text-slate-900">
            Sửa tài khoản
        </a>
    </div>
</div>
@endsection
