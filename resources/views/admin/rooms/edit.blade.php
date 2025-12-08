@extends('admin.layouts.app')

@section('title', 'Sửa phòng chiếu')
@section('page_title', 'Sửa phòng chiếu')
@section('page_subtitle', 'Chỉnh sửa: '.$room->name)

@section('styles')
<style>
    .room-form-card {
        max-width: 640px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .room-label {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9ca3af;
        margin-bottom: 4px;
    }
    .room-input,
    .room-select {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.95);
        padding: 8px 10px;
        font-size: 0.85rem;
        color: #e5e7eb;
        outline: none;
    }
    .room-input:focus,
    .room-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .room-hint {
        font-size: 0.7rem;
        color: #6b7280;
    }
    .room-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .room-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .room-btn-primary:hover { filter: brightness(1.08); }
    .room-btn-outline {
        border: 1px solid #4b5563;
        background: transparent;
        color: #e5e7eb;
    }
    .room-btn-outline:hover {
        background: rgba(31,41,55,0.9);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const totalInput = document.getElementById('room-total-seats');
    const totalHint  = document.getElementById('room-total-seats-hint');

    if (totalInput && totalHint) {
        const updateHint = () => {
            const value = parseInt(totalInput.value || 0, 10);
            if (!value) {
                totalHint.textContent = 'Nhập tổng số ghế hiện có trong phòng.';
            } else {
                totalHint.textContent = `Phòng này sẽ hiển thị ${value} ghế trong sơ đồ (dữ liệu ghế chi tiết lấy từ bảng seats).`;
            }
        };
        totalInput.addEventListener('input', updateHint);
        updateHint();
    }
});
</script>
@endsection

@section('content')
<div class="room-form-card">
    @if($errors->any())
        <div class="mb-4 text-xs text-rose-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="room-label">Thuộc rạp</label>
            <select name="cinema_id" class="room-select">
                <option value="">-- Chọn rạp chiếu --</option>
                @foreach($cinemas as $c)
                    <option value="{{ $c->id }}"
                        @selected(old('cinema_id', $room->cinema_id) == $c->id)>
                        {{ $c->name }} ({{ $c->city }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="room-label">Tên phòng</label>
            <input type="text" name="name"
                   value="{{ old('name', $room->name) }}" class="room-input"
                   placeholder="VD: Phòng 1, Phòng VIP 2">
        </div>

        <div>
            <label class="room-label">Tổng số ghế</label>
            <input id="room-total-seats" type="number" name="total_seats"
                   value="{{ old('total_seats', $room->total_seats) }}"
                   class="room-input" min="0">
            <p id="room-total-seats-hint" class="room-hint mt-1">
                Nhập tổng số ghế hiện có trong phòng.
            </p>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.rooms.index') }}"
               class="room-btn room-btn-outline">
                Hủy
            </a>
            <button type="submit" class="room-btn room-btn-primary">
                Cập nhật phòng
            </button>
        </div>
    </form>
</div>
@endsection
