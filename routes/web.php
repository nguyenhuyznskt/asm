<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\MovieController;
use App\Http\Controllers\Client\BookingController;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Danh sách phim
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');

// Chi tiết phim
Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');

// Chọn ghế
Route::get('/showtimes/{showtime}', [BookingController::class, 'selectSeats'])
    ->name('booking.select-seats');

// Đặt vé
Route::post('/showtimes/{showtime}/book', [BookingController::class, 'store'])
    ->name('booking.store');

// Các trang đơn
Route::view('/about', 'frontend.pages.about')->name('about');
Route::view('/contact', 'frontend.pages.contact')->name('contact');
