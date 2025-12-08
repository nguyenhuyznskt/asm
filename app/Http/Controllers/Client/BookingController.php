<?php

namespace App\Http\Controllers\Client;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Combo;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function selectSeats(Showtime $showtime)
    {
        $showtime->load('movie', 'room.cinema', 'room.seats');

        // lấy ghế đã được đặt trong suất này
        $bookedSeatIds = BookingSeat::whereHas('booking', function ($query) use ($showtime) {
            $query->where('showtime_id', $showtime->id);
        })->pluck('seat_id')->toArray();
        $combos = Combo::where('is_active', true)->get();

        return view('frontend.booking.select-seats', [
            'showtime'      => $showtime,
            'bookedSeatIds' => $bookedSeatIds,
            'combos'        => $combos,
        ]);
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
    
        $seatIds = $data['seats'];
    
        $baseTotal = count($seatIds) * $showtime->price;
    
        $booking = Booking::create([
            'showtime_id'    => $showtime->id,
            'customer_name'  => $data['customer_name'],
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'total_price'    => 0,             // tạm
            'status'         => 'confirmed',
            'user_id'        => Auth::id(),
        ]);
    
        // Lưu ghế
        foreach ($seatIds as $seatId) {
            BookingSeat::create([
                'booking_id' => $booking->id,
                'seat_id'    => $seatId,
                'price'      => $showtime->price,
            ]);
        }
    
        // ====== Lưu combo ======
        $comboData = $request->input('combo', []); // [combo_id => quantity]
    
        $comboTotal = 0;
    
        foreach ($comboData as $comboId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) continue;
    
            $combo = Combo::find($comboId);
            if (!$combo) continue;
    
            $lineTotal = $combo->price * $qty;
            $comboTotal += $lineTotal;
    
            $booking->combos()->create([
                'combo_id'    => $combo->id,
                'quantity'    => $qty,
                'unit_price'  => $combo->price,
                'total_price' => $lineTotal,
            ]);
        }
    
        $totalPrice = $baseTotal + $comboTotal;
        $movie = $showtime->movie; // hoặc $booking->showtime->movie;

        if ($movie) {
            $movie->increment('total_bookings');
        }
        $booking->update([
            'total_price' => $totalPrice,
        ]);
    
        return redirect()->route('ticket.show', $booking->id)
            ->with('success', 'Đặt vé thành công!');
    }
    

    public function ticket(Booking $booking)
    {
        // nếu cần quan hệ:
        $booking->load([
            'showtime.movie',
            'showtime.room.cinema',
            'seats', // hoặc 'bookingSeats.seat' tùy mày define quan hệ
        ]);
    
        return view('frontend.booking.ticket', compact('booking'));
    }

    public function history()
{
    // Lấy user đang login
    $user = Auth::user();   // hoặc Auth::id() nếu chỉ cần id

    if (!$user) {
        return redirect()->route('login')
            ->with('error', 'Bạn cần đăng nhập để xem lịch sử vé.');
    }

    $bookings = Booking::with([
            'showtime.movie',
            'showtime.room.cinema',
            'seats',
        ])
        ->where(function ($q) use ($user) {
            $q->where('user_id', $user->id);

            if ($user->email) {
                $q->orWhere('customer_email', $user->email);
            }
        })
        ->latest()
        ->paginate(10);

    return view('frontend.booking.history', compact('bookings'));
}

    
    
    
}
