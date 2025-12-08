@extends('admin.layouts.app')

@section('title', 'Sửa tài khoản')
@section('page_title', 'Sửa tài khoản')
@section('page_subtitle', 'Chỉnh sửa: '.$user->email)

@section('styles')
<style>
    .user-form-card {
        max-width: 640px;
        background: rgba(15,23,42,0.9);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .user-label {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9ca3af;
        margin-bottom: 4px;
    }
    .user-input,
    .user-select {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.95);
        padding: 8px 10px;
        font-size: 0.85rem;
        color: #e5e7eb;
        outline: none;
    }
    .user-input:focus,
    .user-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .user-hint {
        font-size: 0.7rem;
        color: #6b7280;
    }
    .user-btn {
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    .user-btn-primary {
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        color: #020617;
    }
    .user-btn-primary:hover { filter: brightness(1.08); }
    .user-btn-outline {
        border: 1px solid #4b5563;
        background: transparent;
        color: #e5e7eb;
    }
    .user-btn-outline:hover {
        background: rgba(31,41,55,0.9);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const pwd = document.getElementById('user-password');
    const toggle = document.getElementById('user-password-toggle');

    if (pwd && toggle) {
        toggle.addEventListener('click', () => {
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
            toggle.textContent = pwd.type === 'password' ? 'Hiện' : 'Ẩn';
        });
    }
});
</script>
@endsection

@section('content')
<div class="user-form-card">
    @if($errors->any())
        <div class="mb-4 text-xs text-rose-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="user-label">Tên hiển thị</label>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}" class="user-input">
        </div>

        <div>
            <label class="user-label">Email đăng nhập</label>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}" class="user-input">
        </div>

        <div>
            <label class="user-label">Mật khẩu (để trống nếu không đổi)</label>
            <div class="flex items-center gap-2">
                <input id="user-password" type="password" name="password"
                       class="user-input" placeholder="Nhập để đổi mật khẩu mới">
                <button type="button" id="user-password-toggle"
                        class="px-3 py-2 rounded-xl text-[11px] border border-slate-600 text-slate-300">
                    Hiện
                </button>
            </div>
            <p class="user-hint mt-1">
                Nếu để trống, mật khẩu hiện tại sẽ được giữ nguyên.
            </p>
        </div>

        <div>
            <label class="user-label">Vai trò</label>
            <select name="role" class="user-select"
                {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                <option value="user"  @selected(old('role', $user->role) === 'user')>User</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
            </select>
            @if(auth()->id() === $user->id)
                <input type="hidden" name="role" value="{{ $user->role }}">
                <p class="user-hint mt-1 text-amber-300">
                    Không thể tự hạ / đổi vai trò chính mình trong admin để tránh khóa quyền.
                </p>
            @else
                <p class="user-hint mt-1">
                    Đổi giữa admin / user. Hệ thống phân quyền ở middleware & policy.
                </p>
            @endif
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.users.index') }}"
               class="user-btn user-btn-outline">
                Hủy
            </a>
            <button type="submit" class="user-btn user-btn-primary">
                Cập nhật tài khoản
            </button>
        </div>
    </form>
</div>
@endsection
