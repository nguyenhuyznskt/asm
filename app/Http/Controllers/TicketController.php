<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class TicketController extends Controller
{
    public function show($id)
    {
        $booking = Booking::with([
                'showtime.movie',
                'showtime.room.cinema',
                'seats',
            ])->findOrFail($id);

        return view('frontend.booking.ticket', compact('booking'));
    }
}