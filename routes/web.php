<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\MovieController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Client\BookingFlowController;
// use App\Http\Controllers\Client\TicketController; // nếu mày tách controller riêng

// ========== CLIENT ==========


Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Danh sách phim + search + filter (đang chiếu, sắp chiếu, nổi bật)
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');

// Chi tiết phim
Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');

// Đặt vé theo flow "chọn phim -> suất -> ghế"
Route::get('/showtimes/{showtime}', [BookingController::class, 'selectSeats'])->name('booking.select-seats');
Route::post('/showtimes/{showtime}/book', [BookingController::class, 'store'])->name('booking.store');

// Flow "Đặt vé theo rạp" giống MoMo: rạp -> phim -> suất
Route::prefix('dat-ve')->name('booking.flow.')->group(function () {
    Route::get('/', [BookingFlowController::class, 'chooseCinema'])->name('cinema');
    Route::get('/phim', [BookingFlowController::class, 'chooseMovie'])->name('movie');
    Route::get('/suat', [BookingFlowController::class, 'chooseShowtime'])->name('showtime');
});

// Trang tĩnh
Route::view('/about', 'frontend.pages.about')->name('about');
Route::view('/contact', 'frontend.pages.contact')->name('contact');

// Xem vé (ticket) sau khi đặt xong
Route::get('/ticket/{booking}', [BookingController::class, 'showTicket'])->name('ticket.show');
// hoặc nếu mày có TicketController riêng thì sửa lại controller cho đúng

// ========== KHU VỰC CẦN LOGIN ==========

Route::middleware('auth')->group(function () {
    // Lịch sử đặt vé
    Route::get('/my-bookings', [BookingController::class, 'history'])->name('booking.history');

    // ... sau này mày thêm profile, v.v.
});

// ========== AUTH ROUTES CỦA BREEZE ==========
require __DIR__.'/auth.php';
