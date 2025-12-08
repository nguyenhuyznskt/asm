<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
       // Breeze sẽ check email + password, rate limit... như cũ
    $request->authenticate();

    // 👉 SAU KHI AUTH ĐÚNG, KIỂM TRA TÀI KHOẢN CÓ BỊ KHÓA KHÔNG
    if (! Auth::user()->is_active) {
        Auth::logout();

        return back()->withErrors([
            'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.',
        ])->onlyInput('email');
    }

    // Nếu active bình thường thì cho vào
    $request->session()->regenerate();

    return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
