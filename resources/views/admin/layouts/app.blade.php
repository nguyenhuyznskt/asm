{{-- resources/views/admin/layouts/app.blade.php --}}
@php
    use Illuminate\Support\Facades\Route;
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text",
                         "Inter", ui-sans-serif, sans-serif;
            background:
                radial-gradient(circle at top, #1d2538 0, #020617 45%, #000 100%);
            color: #e5e7eb;
        }

        .admin-glass {
            background: rgba(15,23,42,0.92);
            backdrop-filter: blur(20px);
        }

        .admin-sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            color: #9ca3af;
        }
        .admin-sidebar-link:hover {
            background: rgba(15,23,42,0.9);
            color: #e5e7eb;
        }
        .admin-sidebar-link-active {
            background: linear-gradient(to right, #22c55e, #0ea5e9);
            color: #020617 !important;
            font-weight: 600;
        }

        .admin-badge-pill {
            border-radius: 999px;
            padding: 0.1rem 0.45rem;
            font-size: 0.65rem;
        }

        /* thin scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #1f2937;
            border-radius: 999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }
    </style>

    {{-- CSS riêng của từng page --}}
    @yield('styles')
</head>
<body class="text-sm">

<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside id="admin-sidebar"
           class="admin-glass w-64 border-r border-slate-800 hidden md:flex flex-col">
        <div class="px-5 py-4 border-b border-slate-800">
            <div class="text-[11px] uppercase tracking-[0.2em] text-slate-500 mb-1">
                Yuhn Cinema
            </div>
            <div class="text-xl font-bold bg-gradient-to-r from-emerald-400 to-sky-400
                        bg-clip-text text-transparent">
                Admin 
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-1">
            <div class="text-[10px] uppercase tracking-[0.2em] text-slate-500 px-2 mb-1">
                Tổng quan
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.dashboard') ? 'admin-sidebar-link-active' : '' }}">
                <span>Dashboard</span>
            </a>

            <div class="mt-4 text-[10px] uppercase tracking-[0.2em] text-slate-500 px-2 mb-1">
                Nội dung
            </div>

            <a href="{{ route('admin.movies.index') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.movies.*') ? 'admin-sidebar-link-active' : '' }}">
                <span>Phim</span>
            </a>

            <a href="{{ route('admin.genres.index') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.genres.*') ? 'admin-sidebar-link-active' : '' }}">
                <span>Thể loại</span>
            </a>

            <a href="{{ route('admin.cinemas.index') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.cinemas.*') ? 'admin-sidebar-link-active' : '' }}">
                <span>Rạp chiếu</span>
            </a>

            <a href="{{ route('admin.rooms.index') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.rooms.*') ? 'admin-sidebar-link-active' : '' }}">
                <span>Phòng chiếu</span>
            </a>

            <a href="{{ route('admin.showtimes.index') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.showtimes.*') ? 'admin-sidebar-link-active' : '' }}">
                <span>Suất chiếu</span>
            </a>

            <a href="{{ route('admin.combos.index') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.combos.*') ? 'admin-sidebar-link-active' : '' }}">
                <span>Combo bắp nước</span>
            </a>

            <div class="mt-4 text-[10px] uppercase tracking-[0.2em] text-slate-500 px-2 mb-1">
                Hệ thống
            </div>

            <a href="{{ route('admin.users.index') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.users.*') ? 'admin-sidebar-link-active' : '' }}">
                <span>Tài khoản</span>
            </a>

            @if(Route::has('admin.comments.index'))
                <a href="{{ route('admin.comments.index') }}"
                   class="admin-sidebar-link
                       {{ request()->routeIs('admin.comments.*') ? 'admin-sidebar-link-active' : '' }}">
                    <span>Bình luận</span>
                </a>
            @endif

           
            @if(Route::has('admin.bookings.index'))
                <a href="{{ route('admin.bookings.index') }}"
                   class="admin-sidebar-link
                       {{ request()->routeIs('admin.bookings.*') ? 'admin-sidebar-link-active' : '' }}">
                    <span>Đặt vé</span>
                </a>
            @endif

            @if(Route::has('admin.activity_logs.index'))
            <a href="{{ route('admin.activity_logs.index') }}"
               class="admin-sidebar-link
                   {{ request()->routeIs('admin.activity_logs.index.*') ? 'admin-sidebar-link-active' : '' }}">
                <span>Admin Log</span>
            </a>
        @endif
        </nav>

        <div class="px-4 py-3 border-t border-slate-800 text-[11px] text-slate-400">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="text-[10px] uppercase tracking-[0.15em] text-slate-500">
                        Đang đăng nhập
                    </div>
                    <div class="font-medium">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </div>
                </div>
                <span class="admin-badge-pill bg-emerald-500/15 text-emerald-300 border border-emerald-500/40">
                    {{ auth()->user()->role ?? 'admin' }}
                </span>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit"
                        class="text-rose-400 hover:text-rose-300">
                    Đăng xuất
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col">

        {{-- TOP BAR --}}
        <header class="admin-glass border-b border-slate-800 px-4 md:px-6 py-3
                       flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                {{-- mobile toggle --}}
                <button id="admin-sidebar-toggle"
                        class="md:hidden inline-flex items-center justify-center
                               w-8 h-8 rounded-full border border-slate-700 text-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="1.5"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <h1 class="text-base md:text-lg font-semibold">
                        @yield('page_title', 'Admin')
                    </h1>
                    @hasSection('page_subtitle')
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            @yield('page_subtitle')
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                @yield('header_actions')
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 px-4 md:px-6 py-5">
            {{-- flash messages --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-2 rounded-xl border border-emerald-500/40
                            bg-emerald-500/10 text-emerald-200 text-xs">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-2 rounded-xl border border-rose-500/40
                            bg-rose-500/10 text-rose-200 text-xs">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="px-4 md:px-6 py-3 text-[10px] text-slate-500 border-t border-slate-900">
            © {{ date('Y') }} Laravel Cinema Admin.
        </footer>
    </div>
</div>

{{-- JS chung cho layout + JS từng page --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('admin-sidebar-toggle');
    const sidebar   = document.getElementById('admin-sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            const isHidden = sidebar.classList.contains('hidden');
            if (isHidden) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('fixed', 'z-40', 'top-0', 'left-0', 'h-full');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('fixed', 'z-40', 'top-0', 'left-0', 'h-full');
            }
        });
    }
});
</script>

@yield('scripts')
</body>
</html>
