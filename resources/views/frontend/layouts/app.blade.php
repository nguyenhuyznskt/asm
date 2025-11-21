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
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            {{-- Logo / tên rạp --}}
            <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-400">
                🎬 Laravel Cinema
            </a>

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

                <a href="{{ route('about') }}"
                   class="{{ request()->routeIs('about') ? 'text-emerald-400 font-semibold' : 'text-slate-200 hover:text-emerald-300' }}">
                    Giới thiệu
                </a>

                <a href="{{ route('contact') }}"
                   class="{{ request()->routeIs('contact') ? 'text-emerald-400 font-semibold' : 'text-slate-200 hover:text-emerald-300' }}">
                    Liên hệ
                </a>
            </nav>
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
