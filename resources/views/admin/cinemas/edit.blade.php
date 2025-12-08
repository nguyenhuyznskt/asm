@extends('admin.layouts.app')

@section('title', 'Sửa rạp')
@section('page_title', 'Sửa rạp chiếu')
@section('page_subtitle', 'Chỉnh sửa: '.$cinema->name)

@section('styles')
<style>
    .cinema-form-card {
        max-width: 640px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .cinema-label {
        display:block;
        font-size:0.7rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        color:#9ca3af;
        margin-bottom:4px;
    }
    .cinema-input {
        width:100%;
        border-radius:12px;
        border:1px solid #374151;
        background:rgba(15,23,42,0.95);
        padding:8px 10px;
        font-size:0.85rem;
        color:#e5e7eb;
        outline:none;
    }
    .cinema-input:focus {
        border-color:#22c55e;
        box-shadow:0 0 0 1px rgba(34,197,94,0.4);
    }
    .cinema-hint {
        font-size:0.7rem;
        color:#6b7280;
    }
    .cinema-btn {
        border-radius:999px;
        font-size:0.75rem;
        padding:6px 12px;
        font-weight:500;
        border:none;
        cursor:pointer;
    }
    .cinema-btn-primary {
        background:linear-gradient(to right, #22c55e, #0ea5e9);
        color:#020617;
    }
    .cinema-btn-primary:hover { filter:brightness(1.08); }
    .cinema-btn-outline {
        border:1px solid #4b5563;
        background:transparent;
        color:#e5e7eb;
    }
    .cinema-btn-outline:hover {
        background:rgba(31,41,55,0.9);
    }
</style>
@endsection

@section('content')
<div class="cinema-form-card">
    @if($errors->any())
        <div class="mb-4 text-xs text-rose-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.cinemas.update', $cinema) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="cinema-label">Tên rạp</label>
            <input type="text" name="name"
                   value="{{ old('name', $cinema->name) }}"
                   class="cinema-input">
        </div>

        <div>
            <label class="cinema-label">Địa chỉ</label>
            <input type="text" name="address"
                   value="{{ old('address', $cinema->address) }}"
                   class="cinema-input">
        </div>

        <div>
            <label class="cinema-label">Thành phố</label>
            <input type="text" name="city"
                   value="{{ old('city', $cinema->city) }}"
                   class="cinema-input">
        </div>

        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('admin.cinemas.index') }}"
               class="cinema-btn cinema-btn-outline">
                Hủy
            </a>
            <button type="submit" class="cinema-btn cinema-btn-primary">
                Lưu thay đổi
            </button>
        </div>
    </form>
</div>
@endsection
