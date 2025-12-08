<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index(Request $request)
    {
        $query = Seat::with(['room.cinema'])
            ->orderBy('room_id')
            ->orderBy('row')
            ->orderBy('number');

        // filter theo rạp
        if ($cinemaId = $request->input('cinema_id')) {
            $query->whereHas('room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            });
        }

        // filter theo phòng
        if ($roomId = $request->input('room_id')) {
            $query->where('room_id', $roomId);
        }

        $seats   = $query->paginate(100)->withQueryString();
        $cinemas = Cinema::orderBy('name')->get();
        $rooms   = Room::with('cinema')->orderBy('cinema_id')->orderBy('name')->get();

        return view('admin.seats.index', compact('seats', 'cinemas', 'rooms'));
    }

    public function edit(Seat $seat)
    {
        $rooms = Room::with('cinema')->orderBy('cinema_id')->orderBy('name')->get();

        return view('admin.seats.edit', compact('seat', 'rooms'));
    }

    public function update(Request $request, Seat $seat)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'row'     => 'required|string|max:255',
            'number'  => 'required|integer|min:1',
            'type'    => 'required|in:normal,vip',
        ]);

        $seat->update($data);

        return redirect()->route('admin.seats.index')
            ->with('success', 'Cập nhật ghế thành công');
    }

    // nếu route only(['index','edit','update']) thì mấy hàm dưới không cần
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function show(Seat $seat) { abort(404); }
    public function destroy(Seat $seat) { abort(404); }
}
