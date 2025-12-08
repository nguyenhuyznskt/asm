<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;
use App\Models\Seat; 
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('cinema')->orderBy('cinema_id')->orderBy('name');

        if ($cinemaId = $request->input('cinema_id')) {
            $query->where('cinema_id', $cinemaId);
        }

        $rooms   = $query->paginate(15)->withQueryString();
        $cinemas = Cinema::orderBy('name')->get();

        return view('admin.rooms.index', compact('rooms', 'cinemas'));
    }

    public function create()
    {
        $cinemas = Cinema::orderBy('name')->get();
        return view('admin.rooms.create', compact('cinemas'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'cinema_id'   => 'required|exists:cinemas,id',
        'name'        => 'required|string|max:255',
        'total_seats' => 'required|integer|min:1',
    ]);

    DB::transaction(function () use ($data) {
        // 1. Tạo phòng
        $room = Room::create($data);

        // 2. Tạo ghế
        $seatsPerRow = 10; // 👈 tùy mày chỉnh
        for ($i = 1; $i <= $data['total_seats']; $i++) {
            $rowIndex = intdiv($i - 1, $seatsPerRow);             // 0,1,2...
            $rowLetter = chr(65 + $rowIndex);                     // 65 = 'A'
            $number    = (($i - 1) % $seatsPerRow) + 1;           // 1..10

            Seat::create([
                'room_id'   => $room->id,
                'row'       => $rowLetter,
                'number'    => $number,  // hoặc seat_number nếu DB mày dùng tên khác
                     // nếu có
                'type'      => 'normal',
                
            ]);
        }
    });

    return redirect()->route('admin.rooms.index')
        ->with('success', 'Thêm phòng chiếu và tạo ghế tự động thành công');
}


    public function edit(Room $room)
    {
        $cinemas = Cinema::orderBy('name')->get();
        return view('admin.rooms.edit', compact('room', 'cinemas'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'cinema_id'   => 'required|exists:cinemas,id',
            'name'        => 'required|string|max:255',
            'total_seats' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($data, $room) {
            // Đếm số ghế hiện có trong DB (an toàn hơn là tin vào total_seats cũ)
            $currentSeats = $room->seats()->count();

            // Cập nhật thông tin phòng
            $room->update($data);

            // Nếu tăng số ghế -> tạo thêm từ ghế (currentSeats + 1) đến total_seats mới
            if ($data['total_seats'] > $currentSeats) {
                $seatsPerRow = 10;

                for ($i = $currentSeats + 1; $i <= $data['total_seats']; $i++) {
                    $rowIndex  = intdiv($i - 1, $seatsPerRow);
                    $rowLetter = chr(65 + $rowIndex);
                    $number    = (($i - 1) % $seatsPerRow) + 1;

                    Seat::create([
                        'room_id' => $room->id,
                        'row'     => $rowLetter,
                        'number'  => $number,
                        'type'    => 'normal',
                    ]);
                }
            }

            // Nếu giảm total_seats: hiện tại không xoá ghế, tránh vỡ booking
            // Nếu sau này muốn xoá, phải check ghế chưa được đặt rồi mới xoá.
        });

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Cập nhật phòng chiếu thành công');
    }



    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Xóa phòng chiếu thành công');
    }
    public function show(Room $room)
    {
        // load luôn cinema để show
        $room->load('cinema', 'seats'); // 👈 nếu muốn show luôn danh sách ghế

        return view('admin.rooms.show', compact('room'));
    }

}
