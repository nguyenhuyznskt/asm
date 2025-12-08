@extends('admin.layouts.app')

@section('title', 'Tài khoản')
@section('page_title', 'Tài khoản')
@section('page_subtitle', 'Quản lý tài khoản người dùng & admin')

@section('styles')
<style>
    .user-card {
        background: rgba(15,23,42,0.85);
        border-radius: 16px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .user-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .user-table th {
        text-align: left;
        padding: 8px 10px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: #020617;
        color: #9ca3af;
        border-bottom: 1px solid #1f2937;
        white-space: nowrap;
    }
    .user-table td {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(31,41,55,0.9);
        vertical-align: top;
    }
    .user-table tr:hover td {
        background: rgba(30,64,175,0.25);
    }
    .user-search,
    .user-select {
        width: 100%;
        border-radius: 999px;
        border: 1px solid #374151;
        background: rgba(15,23,42,0.9);
        padding: 6px 12px;
        font-size: 0.8rem;
        color: #e5e7eb;
        outline: none;
    }
    .user-search:focus,
    .user-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4);
    }
    .user-badge-role {
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
        border-width: 1px;
        border-style: solid;
    }
    .user-chip-avatar {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        background: radial-gradient(circle at top, #22c55e, #0ea5e9);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        color: #020617;
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
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('user-search');
    const filterForm  = document.getElementById('user-filter-form');
    const roleSelect  = document.getElementById('user-role-filter');

    function filterClient() {
        const keyword = (searchInput?.value || '').toLowerCase();
        document.querySelectorAll('[data-user-row]').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = keyword && !text.includes(keyword) ? 'none' : '';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterClient);
    }

    if (roleSelect && filterForm) {
        roleSelect.addEventListener('change', () => filterForm.submit());
    }
});
</script>
@endsection

@section('header_actions')

@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <div class="w-full md:w-72">
        <input id="user-search" type="text" class="user-search"
               placeholder="Tìm theo tên / email trên trang hiện tại...">
    </div>

    <form id="user-filter-form" method="GET" class="flex items-center gap-2">
        <div class="w-40">
            <select id="user-role-filter" name="role" class="user-select text-xs">
                <option value="">Tất cả vai trò</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                <option value="user" @selected(request('role') === 'user')>User</option>
            </select>
        </div>
    </form>
</div>

<div class="user-card overflow-x-auto">
    <table class="user-table">
        <thead>
        <tr>
            <th>Tài khoản</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
            <th class="text-right">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr data-user-row>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="user-chip-avatar">
                            {{ mb_strtoupper(mb_substr($user->name ?? $user->email, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-50">
                                {{ $user->name ?? '—' }}
                            </div>
                            <div class="text-[11px] text-slate-400">
                                ID: {{ $user->id }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="text-xs text-slate-300">
                    {{ $user->email }}
                </td>
                <td class="text-xs">
                    @if($user->role === 'admin')
                        <span class="user-badge-role bg-amber-500/20 text-amber-300 border-amber-500/60">
                            Admin
                        </span>
                    @else
                        <span class="user-badge-role bg-slate-700/70 text-slate-100 border-slate-500/80">
                            User
                        </span>
                    @endif
                </td>
                <td class="text-xs text-slate-400">
                    {{ optional($user->created_at)->format('d/m/Y H:i') ?? '—' }}
                </td>
                <td class="text-right">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="text-xs text-sky-400 hover:text-sky-300 mr-3">
                        Sửa
                    </a>
                    @if(auth()->id() !== $user->id)
                        <form action="{{ route('admin.users.destroy', $user) }}"
                              method="POST" class="inline-block"
                              onsubmit="return confirm('Xóa tài khoản này?');">
                            @csrf @method('DELETE')
                            <button class="text-xs text-rose-400 hover:text-rose-300">
                                Xóa
                            </button>
                        </form>
                    @else
                        <span class="text-[11px] text-slate-500">Không thể tự xóa</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-sm text-slate-400 py-4">
                    Chưa có tài khoản nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
