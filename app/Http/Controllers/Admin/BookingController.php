<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\BookingCombo;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Cinema;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with([
            'showtime.movie',
            'showtime.room.cinema',
            'user',
        ])->orderBy('created_at', 'desc');

        // lọc theo trạng thái
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // lọc theo phim
        if ($movieId = $request->input('movie_id')) {
            $query->whereHas('showtime', function ($q) use ($movieId) {
                $q->where('movie_id', $movieId);
            });
        }

        // lọc theo rạp
        if ($cinemaId = $request->input('cinema_id')) {
            $query->whereHas('showtime.room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            });
        }

        // search theo tên / email / phone
        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('customer_name', 'like', '%' . $q . '%')
                    ->orWhere('customer_email', 'like', '%' . $q . '%')
                    ->orWhere('customer_phone', 'like', '%' . $q . '%');
            });
        }

        $bookings = $query->paginate(20)->withQueryString();

        $movies  = Movie::orderBy('title')->get();
        $cinemas = Cinema::orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'movies', 'cinemas'));
    }

    public function show(Booking $booking)
    {
        // load thông tin showtime, phim, rạp, user
        $booking->load([
            'showtime.movie',
            'showtime.room.cinema',
            'user',
        ]);

        // chi tiết ghế
        $seatLines = BookingSeat::with(['seat.room.cinema'])
            ->where('booking_id', $booking->id)
            ->get();

        // chi tiết combo
        $comboLines = BookingCombo::with('combo')
            ->where('booking_id', $booking->id)
            ->get();

        return view('admin.bookings.show', compact('booking', 'seatLines', 'comboLines'));
    }

    public function destroy(Booking $booking)
    {
        // Chỉ cho hủy khi đơn đang chờ
        if ($booking->status !== 'pending') {
            return redirect()
                ->route('admin.bookings.index')
                ->with('error', 'Chỉ những đơn đang chờ mới được hủy.');
        }
    
        DB::transaction(function () use ($booking) {
            BookingSeat::where('booking_id', $booking->id)->delete();
            BookingCombo::where('booking_id', $booking->id)->delete();
    
            // Không xóa record, chỉ set trạng thái ĐÃ HỦY
            $booking->status = 'cancelled';
            $booking->save();
        });
    
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Đơn đặt vé đã được hủy và trả ghế thành công.');
    }
    
    

    // admin không tự tạo / sửa booking bằng tay
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(Booking $booking) { abort(404); }
    public function update(Request $request, Booking $booking) { abort(404); }
}
