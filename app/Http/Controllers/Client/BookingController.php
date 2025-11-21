<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Showtime;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function selectSeats(Showtime $showtime)
    {
        $showtime->load('movie', 'room.cinema', 'room.seats');

        // lấy ghế đã được đặt trong suất này
        $bookedSeatIds = BookingSeat::whereHas('booking', function ($query) use ($showtime) {
            $query->where('showtime_id', $showtime->id);
        })->pluck('seat_id')->toArray();

        return view('frontend.booking.select-seats', compact('showtime', 'bookedSeatIds'));
    }

    public function store(Request $request, Showtime $showtime)
    {
        $data = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string|max:20',
            'seats'          => 'required|array|min:1',
            'seats.*'        => 'exists:seats,id',
        ]);

        $total = count($data['seats']) * $showtime->price;

        $booking = Booking::create([
            'showtime_id'    => $showtime->id,
            'customer_name'  => $data['customer_name'],
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'total_price'    => $total,
            'status'         => 'confirmed',
        ]);

        foreach ($data['seats'] as $seatId) {
            BookingSeat::create([
                'booking_id' => $booking->id,
                'seat_id'    => $seatId,
                'price'      => $showtime->price,
            ]);
        }

        return redirect()->route('home')
            ->with('success', 'Đặt vé thành công! Mã đơn #' . $booking->id);
    }
}
