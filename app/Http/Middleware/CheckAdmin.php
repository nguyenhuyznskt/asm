<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // Có thể redirect về trang chủ kèm message
            // return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập');
            abort(403, 'Bạn không có quyền truy cập khu vực này.');
        }

        return $next($request);
    }
}
