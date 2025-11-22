<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\MovieController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Client\BookingFlowController;
use App\Http\Controllers\Client\CommentController;

// dashboard của Breeze → redirect về home
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// Flow đặt vé theo rạp
Route::prefix('dat-ve')->name('booking.flow.')->group(function () {
    // B1: chọn rạp
    Route::get('/', [BookingFlowController::class, 'chooseCinema'])->name('cinema');

    // B2: chọn phim trong rạp
    Route::get('/phim', [BookingFlowController::class, 'chooseMovie'])->name('movie');

    // B3: chọn ngày + suất chiếu
    Route::get('/suat', [BookingFlowController::class, 'chooseShowtime'])->name('showtime');
});

// Trang client
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');

Route::get('/showtimes/{showtime}', [BookingController::class, 'selectSeats'])->name('booking.select-seats');
Route::post('/showtimes/{showtime}/book', [BookingController::class, 'store'])->name('booking.store');

Route::view('/about', 'frontend.pages.about')->name('about');
Route::view('/contact', 'frontend.pages.contact')->name('contact');

// Xem vé
Route::get('/ticket/{booking}', [BookingController::class, 'ticket'])
    ->name('ticket.show');

// Comment phim
Route::post('/movies/{movie}/comment', [CommentController::class, 'store'])
    ->name('movies.comment');

// Like / dislike comment
Route::post('/comments/{comment}/like', [CommentController::class, 'like'])
    ->name('comments.like');

Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])
    ->name('comments.dislike');

// Cần login
Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'history'])->name('booking.history');
});

// Auth routes của Breeze
require __DIR__.'/auth.php';
