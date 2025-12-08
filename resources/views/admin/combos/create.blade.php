@extends('admin.layouts.app')

@section('title', 'Thêm combo')
@section('page_title', 'Thêm combo')
@section('page_subtitle', 'Tạo mới combo bắp nước')

@section('styles')
<style>
    .combo-form-card {
        max-width: 720px;
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .combo-label {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9ca3af;
        margin-bottom: 4px;
    }
    .combo-input, .combo-textarea {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.95);
        padding: 8px 10px;
        font-size: 0.85rem;
        color: #e5e7eb;
        outline: none;
    }
    .combo-input:focus, .combo-textarea:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .combo-switch {
        position: relative;
        width: 38px;
        height: 20px;
        border-radius: 999px;
        background: #4b5563;
        transition: background .2s;
        cursor: pointer;
    }
    .combo-switch span {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 16px;
        height: 16px;
        border-radius: 999px;
        background: #e5e7eb;
        transition: transform .2s;
    }
    .combo-switch-active { background: #22c55e; }
    .combo-switch-active span { transform: translateX(18px); }
    .combo-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .combo-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .combo-btn-primary:hover { filter: brightness(1.08); }
    .combo-btn-outline {
        border: 1px solid #4b5563;
        background: transparent;
        color: #e5e7eb;
    }
    .combo-btn-outline:hover {
        background: rgba(31,41,55,0.9);
    }
    .combo-img-preview {
        width: 140px;
        height: 140px;
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
    const priceInput = document.getElementById('combo-price');
    const priceFormatted = document.getElementById('combo-price-formatted');
    const urlInput = document.getElementById('combo-image-url');
    const imgPreview = document.getElementById('combo-image-preview');
    const switchEl = document.getElementById('combo-switch');
    const switchCheckbox = document.getElementById('combo-is-active');

    if (priceInput && priceFormatted) {
        const updatePrice = () => {
            const value = priceInput.value.replace(/\D/g,'');
            if (!value) {
                priceFormatted.textContent = '0 đ';
                return;
            }
            priceFormatted.textContent = new Intl.NumberFormat('vi-VN').format(value) + ' đ';
        };
        priceInput.addEventListener('input', updatePrice);
        updatePrice();
    }

    if (urlInput && imgPreview) {
        const updateImage = () => {
            const url = urlInput.value.trim();
            imgPreview.src = url || 'https://via.placeholder.com/300x300?text=Combo';
        };
        urlInput.addEventListener('input', updateImage);
        updateImage();
    }

    if (switchEl && switchCheckbox) {
        const syncSwitch = () => {
            if (switchCheckbox.checked) {
                switchEl.classList.add('combo-switch-active');
            } else {
                switchEl.classList.remove('combo-switch-active');
            }
        };
        switchEl.addEventListener('click', () => {
            switchCheckbox.checked = !switchCheckbox.checked;
            syncSwitch();
        });
        syncSwitch();
    }
});
</script>
@endsection

@section('content')
<div class="combo-form-card">
    @if($errors->any())
        <div class="mb-4 text-xs text-rose-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.combos.store') }}" method="POST" class="grid md:grid-cols-2 gap-5">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="combo-label">Tên combo</label>
                <input type="text" name="name" value="{{ old('name') }}" class="combo-input">
            </div>

            <div>
                <label class="combo-label">Giá (VNĐ)</label>
                <input id="combo-price" type="text" name="price"
                       value="{{ old('price') }}" class="combo-input" inputmode="numeric">
                <div class="mt-1 text-[11px] text-slate-400">
                    Hiển thị: <span id="combo-price-formatted" class="text-emerald-300 font-semibold"></span>
                </div>
            </div>

            <div>
                <label class="combo-label">Trạng thái</label>
                <div class="flex items-center gap-2">
                    <div id="combo-switch" class="combo-switch">
                        <span></span>
                    </div>
                    <span class="text-xs text-slate-300">Đang bán</span>
                    <input type="checkbox" id="combo-is-active" name="is_active" value="1"
                           class="hidden" {{ old('is_active', 1) ? 'checked' : '' }}>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="combo-label">Ảnh combo (URL)</label>
                <input id="combo-image-url" type="text" name="image_url"
                       value="{{ old('image_url') }}" class="combo-input"
                       placeholder="https://...">
            </div>

            <div class="flex items-center gap-3">
                <img id="combo-image-preview" src="" alt="Preview" class="combo-img-preview">
                <p class="text-[11px] text-slate-400">
                    Dán URL ảnh combo vào ô bên trên để xem trước.<br>
                    Nên dùng ảnh vuông 600x600 trở lên.
                </p>
            </div>

            <div>
                <label class="combo-label">Mô tả</label>
                <textarea name="description" rows="4" class="combo-textarea"
                          placeholder="VD: 1 bắp lớn + 2 nước ngọt...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="md:col-span-2 flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.combos.index') }}" class="combo-btn combo-btn-outline">
                Hủy
            </a>
            <button type="submit" class="combo-btn combo-btn-primary">
                Lưu combo
            </button>
        </div>
    </form>
</div>
@endsection
