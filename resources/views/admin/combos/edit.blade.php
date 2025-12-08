@extends('admin.layouts.app')

@section('title', 'Sửa combo')
@section('page_title', 'Sửa combo')
@section('page_subtitle', 'Chỉnh sửa combo: '.$combo->name)

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
    .combo-btn-outline {
        border: 1px solid #4b5563;
        background: transparent;
        color: #e5e7eb;
    }
    .combo-img-preview {
        width: 140px;
        height: 140px;
        border-radius: 16px;
        border: 1px solid #374151;
        object-fit: cover;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const priceInput = document.getElementById('combo-price');
    const priceFormatted = document.getElementById('combo-price-formatted');
    const fileInput = document.getElementById('combo-image-file');
    const imgPreview = document.getElementById('combo-image-preview');
    const switchEl = document.getElementById('combo-switch');
    const switchCheckbox = document.getElementById('combo-is-active');

    // format giá
    const updatePrice = () => {
        const value = priceInput.value.replace(/\D/g,'');
        priceFormatted.textContent = new Intl.NumberFormat('vi-VN').format(value) + ' đ';
    };
    updatePrice();
    priceInput.addEventListener('input', updatePrice);

    // preview ảnh
    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) imgPreview.src = URL.createObjectURL(file);
    });

    // switch
    const syncSwitch = () => {
        switchEl.classList.toggle('combo-switch-active', switchCheckbox.checked);
    };
    switchEl.addEventListener('click', () => {
        switchCheckbox.checked = !switchCheckbox.checked;
        syncSwitch();
    });
    syncSwitch();
});
</script>
@endsection

@section('content')
<div class="combo-form-card">

<form action="{{ route('admin.combos.update', $combo) }}"
      method="POST"
      enctype="multipart/form-data"
      class="grid md:grid-cols-2 gap-5">
    @csrf
    @method('PUT')

    <div class="space-y-4">
        <div>
            <label class="combo-label">Tên combo</label>
            <input type="text" name="name"
                   value="{{ old('name', $combo->name) }}"
                   class="combo-input">
        </div>

        <div>
            <label class="combo-label">Giá (VNĐ)</label>
            <input id="combo-price" type="text" name="price"
                   value="{{ old('price', $combo->price) }}"
                   class="combo-input">
            <div class="mt-1 text-[11px] text-slate-400">
                Hiển thị: <span id="combo-price-formatted" class="text-emerald-300"></span>
            </div>
        </div>

        <div>
            <label class="combo-label">Trạng thái</label>
            <div class="flex items-center gap-2">
                <div id="combo-switch" class="combo-switch"><span></span></div>
                <span class="text-xs text-slate-300">Đang bán</span>
                <input type="checkbox" id="combo-is-active" name="is_active" value="1"
                       class="hidden" {{ $combo->is_active ? 'checked' : '' }}>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="combo-label">Ảnh combo (Upload)</label>
            <input id="combo-image-file" type="file" name="image"
                   accept="image/*"
                   class="combo-input">
        </div>

        <div class="flex items-center gap-3">
            <img id="combo-image-preview"
                 src="{{ asset('storage/'.$combo->image_url) }}"
                 class="combo-img-preview">
        </div>

        <div>
            <label class="combo-label">Mô tả</label>
            <textarea name="description" rows="4"
                      class="combo-textarea">{{ old('description', $combo->description) }}</textarea>
        </div>
    </div>

    <div class="md:col-span-2 flex justify-end gap-2 pt-2">
        <a href="{{ route('admin.combos.index') }}" class="combo-btn combo-btn-outline">Hủy</a>
        <button type="submit" class="combo-btn combo-btn-primary">Cập nhật combo</button>
    </div>

</form>
</div>
@endsection
