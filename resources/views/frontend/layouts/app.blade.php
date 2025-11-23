{{-- resources/views/frontend/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Rạp chiếu phim Laravel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Dùng Tailwind CDN cho nhanh bài tập --}}
    <script src="https://cdn.tailwindcss.com"></script>

    @stack('styles')
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col">

    {{-- HEADER + MENU ĐIỀU HƯỚNG --}}
    <header class="bg-slate-900/90 backdrop-blur border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            {{-- Logo / tên rạp --}}
            <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-400">
                🎬 Yuhn Cinema
            </a>
    
            {{-- Menu + search --}}
            <div class="flex-1 flex items-center justify-end gap-4">
                {{-- Menu điều hướng --}}
                <nav class="flex items-center gap-4 text-sm">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? 'text-emerald-400 font-semibold' : 'text-slate-200 hover:text-emerald-300' }}">
                        Trang chủ
                    </a>
                
                    <a href="{{ route('movies.index') }}"
                       class="{{ request()->routeIs('movies.*') ? 'text-emerald-400 font-semibold' : 'text-slate-200 hover:text-emerald-300' }}">
                        Phim
                    </a>
                
                    {{-- ⭐ Nút Đặt vé mới thêm vào --}}
                    <a href="{{ route('booking.flow.cinema') }}"
                       class="{{ request()->routeIs('booking.flow.*') ? 'text-emerald-400 font-semibold' : 'text-slate-200 hover:text-emerald-300' }}">
                        Đặt vé
                    </a>
                
                    <a href="{{ route('about') }}"
                       class="{{ request()->routeIs('about') ? 'text-emerald-400 font-semibold' : 'text-slate-200 hover:text-emerald-300' }}">
                        Giới thiệu
                    </a>
                
                    <a href="{{ route('contact') }}"
                       class="{{ request()->routeIs('contact') ? 'text-emerald-400 font-semibold' : 'text-slate-200 hover:text-emerald-300' }}">
                        Liên hệ
                    </a>
                </nav>
    
                {{-- Thanh tìm kiếm phim --}}
                <form action="{{ route('movies.index') }}" method="GET" class="hidden sm:flex items-center">
                    <div class="relative">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Tìm kiếm phim..."
                            class="pl-8 pr-3 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-sm
                                   placeholder:text-slate-500 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400"
                        >
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-slate-500 text-xs">
                            🔍
                        </span>
                    </div>
                </form>
                @auth
                    <div class="flex items-center gap-3 text-sm">
                        <a href="{{ route('booking.history') }}"
                        class="text-slate-200 hover:text-emerald-400">
                         Vé của tôi
                     </a>
                        <span class="text-slate-300">
                            Xin chào, {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="px-3 py-1 rounded-full bg-slate-800 hover:bg-slate-700 text-xs">
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-sm">
                        <a href="{{ route('login') }}"
                           class="px-3 py-1 rounded-full border border-slate-700 hover:border-emerald-400">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-3 py-1 rounded-full bg-emerald-500 hover:bg-emerald-600 text-slate-900 font-semibold">
                            Đăng ký
                        </a>
                    </div>
                @endauth
            </div>







        </div>
    </header>
    

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="bg-emerald-500 text-white text-center py-2 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- NỘI DUNG TRANG CON --}}
    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 py-6">
            @yield('content')
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 border-t border-slate-800">
        <div class="max-w-6xl mx-auto px-4 py-4 text-xs text-slate-400 flex justify-between">
            <span>© {{ date('Y') }} Laravel Cinema</span>
            <span>Demo bài tập Laravel 12</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
