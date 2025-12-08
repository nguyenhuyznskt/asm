<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\MovieController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Client\BookingFlowController;
use App\Http\Controllers\Client\CommentController;




// Route::prefix('admin')
//     ->name('admin.')
//     ->middleware(['auth', 'admin']) // bắt buộc đăng nhập + là admin
//     ->group(function () {

//         Route::get('/', function () {
//             return view('admin.dashboard');
//         })->name('dashboard');

//         // CRUD các bảng
//         Route::resource('categories', CategoryController::class);
//         Route::resource('products', ProductController::class);

//         // Admin quản lý user (thêm/sửa/xóa, đổi role)
//         Route::resource('users', UserController::class);
//     });






// dashboard của Breeze → redirect về home
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// =======================
// Flow đặt vé theo rạp
// =======================
Route::prefix('dat-ve')->name('booking.flow.')->group(function () {
    // B1: chọn rạp
    Route::get('/', [BookingFlowController::class, 'chooseCinema'])
        ->name('cinema');

    // B2: chọn phim + ngày + giờ trong rạp
    Route::get('/phim', [BookingFlowController::class, 'chooseMovie'])
        ->name('movie');
});

// =======================
// Trang client public
// =======================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/movies', [MovieController::class, 'index'])
    ->name('movies.index');

Route::get('/movies/{slug}', [MovieController::class, 'show'])
    ->name('movies.show');

// Chỉ chọn ghế (xem suất chiếu), chưa đặt vé → cho public
Route::get('/showtimes/{showtime}', [BookingController::class, 'selectSeats'])
    ->name('booking.select-seats');

Route::view('/about', 'frontend.pages.about')->name('about');
Route::view('/contact', 'frontend.pages.contact')->name('contact');

// =======================
// Các route cần login
// =======================
Route::middleware('auth')->group(function () {

    // Đặt vé (lưu booking)
    Route::post('/showtimes/{showtime}/book', [BookingController::class, 'store'])
        ->name('booking.store');

    // Xem vé cụ thể
    Route::get('/ticket/{booking}', [BookingController::class, 'ticket'])
        ->name('ticket.show');

    // Lịch sử đặt vé
    Route::get('/my-bookings', [BookingController::class, 'history'])
        ->name('booking.history');

    // Comment phim
    Route::post('/movies/{movie}/comment', [CommentController::class, 'store'])
        ->name('movies.comment');

    // Like / dislike comment
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])
        ->name('comments.like');

    Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])
        ->name('comments.dislike');
});

// Auth routes của Breeze
require __DIR__ . '/auth.php';
