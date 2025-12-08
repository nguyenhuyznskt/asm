<?php

use Illuminate\Support\Facades\Route;

// =======================
// Client Controllers
// =======================
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\MovieController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Client\BookingFlowController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\SimpleRecommendController;

// =======================
// Admin Controllers
// =======================
use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\MovieController as MovieControllerAdmin;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\CommentController as CommentAdminController;
use App\Http\Controllers\Admin\BookingController as BookingAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ActivityLogController;


// =======================
// ADMIN ROUTES
// =======================
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // CRUD nội dung chính
        Route::resource('cinemas', CinemaController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('genres', GenreController::class);
        Route::resource('movies', MovieControllerAdmin::class);
        Route::resource('combos', ComboController::class);
          Route::get('showtimes/available-slots', [ShowtimeController::class, 'availableSlots'])
            ->name('showtimes.available-slots');
        Route::resource('showtimes', ShowtimeController::class);

        // Quản lý người dùng
       
        Route::get('users', [UserController::class, 'index'])->name('users.index');

        // Cấp / gỡ quyền admin
        Route::patch('users/{user}/make-admin', [UserController::class, 'makeAdmin'])->name('users.make_admin');
        Route::patch('users/{user}/remove-admin', [UserController::class, 'removeAdmin'])->name('users.remove_admin');
    
        // Khóa / mở khóa tài khoản
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle_active');

        // Quản lý ghế (chỉ xem & sửa)
        Route::resource('seats', SeatController::class)
            ->only(['index', 'edit', 'update']);

        // Quản lý bình luận
        Route::resource('comments', CommentAdminController::class)
            ->only(['index', 'edit', 'update', 'destroy']);

        // Quản lý đặt vé
        Route::resource('bookings', BookingAdminController::class)
            ->only(['index', 'show', 'destroy']);

            Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity_logs.index');
    });


// =======================
// DASHBOARD BREEZE → HOME
// =======================
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');


// =======================
// FLOW ĐẶT VÉ THEO RẠP (giống MoMo)
// =======================
Route::prefix('dat-ve')
    ->name('booking.flow.')
    ->group(function () {
        // B1: chọn rạp
        Route::get('/', [BookingFlowController::class, 'chooseCinema'])
            ->name('cinema');

        // B2: chọn phim + ngày + giờ trong rạp
        Route::get('/phim', [BookingFlowController::class, 'chooseMovie'])
            ->name('movie');
    });


// =======================
// TRANG CLIENT PUBLIC
// =======================

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Danh sách phim
Route::get('/movies', [MovieController::class, 'index'])
    ->name('movies.index');

// Chi tiết phim
Route::get('/movies/{slug}', [MovieController::class, 'show'])
    ->name('movies.show');

// Xem ghế theo suất chiếu (chưa đặt vé)
Route::get('/showtimes/{showtime}', [BookingController::class, 'selectSeats'])
    ->name('booking.select-seats');

// Trang giới thiệu / liên hệ
Route::view('/about', 'frontend.pages.about')->name('about');
Route::view('/contact', 'frontend.pages.contact')->name('contact');


// =======================
// CÁC ROUTE CẦN LOGIN
// =======================
Route::middleware('auth')->group(function () {

    // Đặt vé (lưu booking)
    Route::post('/showtimes/{showtime}/book', [BookingController::class, 'store'])
        ->name('booking.store');

    // Xem vé cụ thể (ticket sau thanh toán)
    Route::get('/ticket/{booking}', [BookingController::class, 'ticket'])
        ->name('ticket.show');

    // Lịch sử đặt vé của user
    Route::get('/my-bookings', [BookingController::class, 'history'])
        ->name('booking.history');

    // Bình luận phim
    Route::post('/movies/{movie}/comment', [CommentController::class, 'store'])
        ->name('movies.comment');

    // Like / dislike comment
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])
        ->name('comments.like');

    Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])
        ->name('comments.dislike');


     Route::get('/goi-y-don-gian', [SimpleRecommendController::class, 'index'])
        ->name('movies.simple_recommend');

        
        Route::get('dat-ve/thanh-toan/{booking}', [BookingController::class, 'payment'])
        ->name('booking.payment');

    // Xác nhận đã thanh toán (demo)
    Route::post('dat-ve/thanh-toan/{booking}/confirm', [BookingController::class, 'confirmPayment'])
        ->name('booking.payment.confirm');

    // Trang vé (hiện QR ticket)
    Route::get('ve/{booking}', [BookingController::class, 'ticket'])
        ->name('booking.ticket');
});


// =======================
// AUTH ROUTES CỦA BREEZE
// =======================
require __DIR__ . '/auth.php';
