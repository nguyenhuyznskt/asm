@extends('frontend.layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-lg">
    <h1 class="text-2xl font-bold mb-4 text-center">Đăng nhập</h1>

    {{-- Thông báo status (ví dụ reset mật khẩu xong) --}}
    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-400">
            {{ session('status') }}
        </div>
    @endif

    {{-- Lỗi validate --}}
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-400">
            @foreach ($errors->all() as $error)
                <div>- {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm
                          focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400">
        </div>

        <div>
            <label class="block text-sm mb-1">Mật khẩu</label>
            <input type="password" name="password" required
                   class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm
                          focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400">
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-slate-600 bg-slate-900">
                <span>Ghi nhớ đăng nhập</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-emerald-400 hover:underline">
                    Quên mật khẩu?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-2 rounded bg-emerald-500 hover:bg-emerald-600 font-semibold mt-2">
            Đăng nhập
        </button>
    </form>

    <p class="text-xs text-slate-400 mt-4 text-center">
        Chưa có tài khoản?
        <a href="{{ route('register') }}" class="text-emerald-400 hover:underline">
            Đăng ký ngay
        </a>
    </p>
</div>
@endsection
