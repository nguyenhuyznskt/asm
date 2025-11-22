@extends('frontend.layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-lg">

    <h1 class="text-2xl font-bold mb-4 text-center">Quên mật khẩu</h1>

    {{-- Thông báo gửi mail thành công --}}
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

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm mb-1">Nhập email đã đăng ký</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm
                       focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400">
        </div>

        <button type="submit"
                class="w-full py-2 rounded bg-emerald-500 hover:bg-emerald-600 font-semibold mt-2">
            Gửi link khôi phục
        </button>
    </form>

    <p class="text-xs text-slate-400 mt-4 text-center">
        Nhớ mật khẩu rồi?
        <a href="{{ route('login') }}" class="text-emerald-400 hover:underline">
            Đăng nhập
        </a>
    </p>

</div>
@endsection
