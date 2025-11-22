@extends('frontend.layouts.app')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="max-w-md mx-auto bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-lg">

    <h1 class="text-2xl font-bold mb-4 text-center">Đặt lại mật khẩu</h1>

    {{-- Lỗi validate --}}
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-400">
            @foreach ($errors->all() as $error)
                <div>- {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label class="block text-sm mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" required
                class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm">
        </div>

        <div>
            <label class="block text-sm mb-1">Mật khẩu mới</label>
            <input type="password" name="password" required
                class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm">
        </div>

        <div>
            <label class="block text-sm mb-1">Nhập lại mật khẩu mới</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-3 py-2 rounded bg-slate-950 border border-slate-700 text-sm">
        </div>

        <button type="submit"
                class="w-full py-2 rounded bg-emerald-500 hover:bg-emerald-600 font-semibold mt-2">
            Đổi mật khẩu
        </button>
    </form>

</div>
@endsection
