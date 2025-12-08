<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Showtime::with(['movie', 'room.cinema'])
            ->orderBy('start_time', 'desc');
    
        // lọc theo phim
        if ($movieId = $request->input('movie_id')) {
            $query->where('movie_id', $movieId);
        }
    
        // lọc theo rạp
        if ($cinemaId = $request->input('cinema_id')) {
            $query->whereHas('room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            });
        }
    
        // lọc theo phòng chiếu
        if ($roomId = $request->input('room_id')) {
            $query->where('room_id', $roomId);
        }
    
        // lọc theo ngày
        if ($date = $request->input('date')) {
            $query->whereDate('start_time', $date);
        }
    
        // ===== LỌC THEO KHUNG GIỜ CHIẾU (GIỜ BẮT ĐẦU) =====
        // from_time & to_time dạng HH:MM (ví dụ 09:00, 18:30)
        if ($fromTime = $request->input('from_time')) {
            $query->whereTime('start_time', '>=', $fromTime);
        }
    
        if ($toTime = $request->input('to_time')) {
            $query->whereTime('start_time', '<=', $toTime);
        }
    
        $showtimes = $query->paginate(20)->withQueryString();
    
        $movies  = Movie::orderBy('title')->get();
        $cinemas = Cinema::orderBy('name')->get();
        $rooms   = Room::with('cinema')->get();
    
        return view('admin.showtimes.index', compact('showtimes', 'movies', 'cinemas', 'rooms'));
    }
    
    

    public function create()
    {
        $movies  = Movie::orderBy('title')->get();
        $rooms   = Room::with('cinema')->orderBy('cinema_id')->orderBy('name')->get();
        $cinemas = Cinema::orderBy('name')->get();

        return view('admin.showtimes.create', compact('movies', 'rooms', 'cinemas'));
    }

    public function store(Request $request)
    {
        // validate input tách ngày / giờ, chỉ chọn giờ bắt đầu
        $data = $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'show_date'  => 'required|date',
            'start_hour' => 'required|date_format:H:i',
            'price'      => 'required|integer|min:0',
        ], [
            'show_date.required'  => 'Vui lòng chọn ngày chiếu.',
            'start_hour.required' => 'Vui lòng chọn giờ bắt đầu.',
            'start_hour.date_format' => 'Giờ bắt đầu không hợp lệ.',
        ]);

        $movie = Movie::findOrFail($data['movie_id']);
        $duration = $movie->duration_minutes ?? 120;

        // Ghép ngày + giờ thành datetime
        $start = Carbon::createFromFormat('Y-m-d H:i', $data['show_date'].' '.$data['start_hour']);
        $end   = (clone $start)->addMinutes($duration);

        // Check trùng lặp suất chiếu trong cùng phòng
        $conflict = $this->hasTimeConflict(
            roomId: $data['room_id'],
            date: $data['show_date'],
            start: $start,
            end: $end
        );

        if ($conflict) {
            return back()
                ->withErrors([
                    'start_hour' => 'Đã có suất chiếu khác trong phòng này trùng/đè lên khoảng thời gian đã chọn.',
                ])
                ->withInput();
        }

        Showtime::create([
            'movie_id'   => $data['movie_id'],
            'room_id'    => $data['room_id'],
            'start_time' => $start,
            'end_time'   => $end,
            'price'      => $data['price'],
        ]);

        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Thêm suất chiếu thành công');
    }

    public function show(Showtime $showtime)
    {
        $showtime->load('movie', 'room.cinema');

        return view('admin.showtimes.show', compact('showtime'));
    }

    public function edit(Showtime $showtime)
    {
        $movies  = Movie::orderBy('title')->get();
        $rooms   = Room::with('cinema')->orderBy('cinema_id')->orderBy('name')->get();
        $cinemas = Cinema::orderBy('name')->get();

        // Tách datetime thành ngày + giờ để fill vào form
        $show_date  = optional($showtime->start_time)->format('Y-m-d');
        $start_hour = optional($showtime->start_time)->format('H:i');

        return view('admin.showtimes.edit', compact(
            'showtime',
            'movies',
            'rooms',
            'cinemas',
            'show_date',
            'start_hour'
        ));
    }

    public function update(Request $request, Showtime $showtime)
    {
        $data = $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'show_date'  => 'required|date',
            'start_hour' => 'required|date_format:H:i',
            'price'      => 'required|integer|min:0',
        ], [
            'show_date.required'  => 'Vui lòng chọn ngày chiếu.',
            'start_hour.required' => 'Vui lòng chọn giờ bắt đầu.',
            'start_hour.date_format' => 'Giờ bắt đầu không hợp lệ.',
        ]);

        $movie = Movie::findOrFail($data['movie_id']);
        $duration = $movie->duration_minutes ?? 120;

        $start = Carbon::createFromFormat('Y-m-d H:i', $data['show_date'].' '.$data['start_hour']);
        $end   = (clone $start)->addMinutes($duration);

        // check trùng lặp (bỏ qua suất hiện tại)
        $conflict = $this->hasTimeConflict(
            roomId: $data['room_id'],
            date: $data['show_date'],
            start: $start,
            end: $end,
            ignoreShowtimeId: $showtime->id
        );

        if ($conflict) {
            return back()
                ->withErrors([
                    'start_hour' => 'Đã có suất chiếu khác trong phòng này trùng/đè lên khoảng thời gian đã chọn.',
                ])
                ->withInput();
        }

        $showtime->update([
            'movie_id'   => $data['movie_id'],
            'room_id'    => $data['room_id'],
            'start_time' => $start,
            'end_time'   => $end,
            'price'      => $data['price'],
        ]);

        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Cập nhật suất chiếu thành công');
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();

        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Xóa suất chiếu thành công');
    }

    /**
     * API trả về các khung giờ trống (dropdown) theo movie / room / date
     */
    public function availableSlots(Request $request)
    {
        $data = $request->validate([
            'movie_id'     => 'required|exists:movies,id',
            'room_id'      => 'required|exists:rooms,id',
            'show_date'    => 'required|date',
            'showtime_id'  => 'nullable|exists:showtimes,id',
        ]);

        $slots = $this->getAvailableSlots(
            movieId: $data['movie_id'],
            roomId: $data['room_id'],
            date: $data['show_date'],
            ignoreShowtimeId: $data['showtime_id'] ?? null
        );

        return response()->json([
            'slots' => $slots,
        ]);
    }

    /**
     * Helper: tính các khung giờ trống (array string 'H:i')
     */
    protected function getAvailableSlots(int $movieId, int $roomId, string $date, int $ignoreShowtimeId = null): array
    {
        $movie    = Movie::findOrFail($movieId);
        $duration = $movie->duration_minutes ?? 120;

        $day        = Carbon::parse($date)->startOfDay();
        $startOfDay = $day->copy()->setTime(8, 0);   // 08:00
        $endOfDay   = $day->copy()->setTime(23, 0);  // 23:00 (giờ bắt đầu tối đa)

        $existingQuery = Showtime::where('room_id', $roomId)
            ->whereDate('start_time', $date);

        if ($ignoreShowtimeId) {
            $existingQuery->where('id', '!=', $ignoreShowtimeId);
        }

        $existing = $existingQuery->get(['start_time', 'end_time']);

        $slots  = [];
        $cursor = $startOfDay->copy();

        // duyệt các slot cách nhau 30 phút
        while ($cursor->copy()->addMinutes($duration) <= $endOfDay) {
            $slotStart = $cursor->copy();
            $slotEnd   = $cursor->copy()->addMinutes($duration);

            $conflict = $existing->first(function (Showtime $st) use ($slotStart, $slotEnd) {
                return $slotStart < $st->end_time && $slotEnd > $st->start_time;
            });

            if (!$conflict) {
                $slots[] = $slotStart->format('H:i');
            }

            $cursor->addMinutes(30);
        }

        return $slots;
    }

    /**
     * Helper: check một khoảng thời gian có conflict với suất chiếu khác không
     */
    protected function hasTimeConflict(int $roomId, string $date, Carbon $start, Carbon $end, int $ignoreShowtimeId = null): bool
    {
        $query = Showtime::where('room_id', $roomId)
            ->whereDate('start_time', $date);

        if ($ignoreShowtimeId) {
            $query->where('id', '!=', $ignoreShowtimeId);
        }

        return $query->where(function ($q) use ($start, $end) {
            $q->where('start_time', '<', $end)
              ->where('end_time', '>', $start);
        })->exists();
    }
}
