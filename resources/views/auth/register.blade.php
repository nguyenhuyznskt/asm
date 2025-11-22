@extends('frontend.layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-lg">

    <h1 class="text-2xl font-bold mb-4 text-center">Đăng ký tài khoản</h1>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-400">
            @foreach ($errors->all() as $error)
                <div>- {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- HỌ TÊN --}}
        <div>
            <label class="block text-sm mb-1">Họ tên</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm
                    focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400">
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="block text-sm mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm
                    focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400">
        </div>

        {{-- PASSWORD --}}
        <div>
            <label class="block text-sm mb-1">Mật khẩu</label>
            <input type="password" name="password" required
                class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm
                    focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400">
        </div>

        {{-- PASSWORD CONFIRM --}}
        <div>
            <label class="block text-sm mb-1">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm
                    focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400">
        </div>

        {{-- NÚT ĐĂNG KÝ --}}
        <button type="submit"
            class="w-full py-2 rounded bg-emerald-500 hover:bg-emerald-600 font-semibold mt-2">
            Đăng ký
        </button>
    </form>

    <p class="text-xs text-slate-400 mt-4 text-center">
        Đã có tài khoản?
        <a href="{{ route('login') }}" class="text-emerald-400 hover:underline">
            Đăng nhập
        </a>
    </p>
</div>
@endsection
