<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use Illuminate\Http\Request;
use App\ActivityLogger;

class CinemaController extends Controller
{
    public function index()
    {
        $cinemas = Cinema::orderBy('name')->paginate(10);
        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function create()
    {
        return view('admin.cinemas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city'    => 'nullable|string|max:255',
        ]);

        $cinema = Cinema::create($data);
        ActivityLogger::log(
            event: 'Thêm Rạp',
            description: 'Admin thêm rạp ' . $cinema->name,
            subject: $cinema, // 🔥 Quan trọng: để có subject_type + subject_id
            properties: [
                // mày muốn lưu id hay tên thể loại tuỳ:
                // 'genre' => $movie->genre->name ?? null,
                'name'     => $cinema->name,
                'city' => $cinema->city, // lúc này đã là Carbon (nếu cast)
            ]
        );

        return redirect()->route('admin.cinemas.index')
            ->with('success', 'Thêm rạp thành công');
    }

    public function edit(Cinema $cinema)
    {
        return view('admin.cinemas.edit', compact('cinema'));
    }

    public function update(Request $request, Cinema $cinema)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city'    => 'nullable|string|max:255',
        ]);

        $cinema->update($data);
        ActivityLogger::log(
            event: 'Thêm Rạp',
            description: 'Admin sửa rạp ' . $cinema->name,
            subject: $cinema, // 🔥 Quan trọng: để có subject_type + subject_id
            properties: [
                // mày muốn lưu id hay tên thể loại tuỳ:
                // 'genre' => $movie->genre->name ?? null,
                'name'     => $cinema->name,
                'city' => $cinema->city, // lúc này đã là Carbon (nếu cast)
            ]
        );
        return redirect()->route('admin.cinemas.index')
            ->with('success', 'Cập nhật rạp thành công');
    }

    public function destroy(Cinema $cinema)
    {
        $cinema->delete();
        ActivityLogger::log(
            event: 'Thêm Rạp',
            description: 'Admin loại bỏ rạp ' . $cinema->name,
            subject: $cinema, // 🔥 Quan trọng: để có subject_type + subject_id
            properties: [
                // mày muốn lưu id hay tên thể loại tuỳ:
                // 'genre' => $movie->genre->name ?? null,
                'name'     => $cinema->name,
                'city' => $cinema->city, // lúc này đã là Carbon (nếu cast)
            ]
        );
        return redirect()->route('admin.cinemas.index')
            ->with('success', 'Xóa rạp thành công');
    }
    public function show(Cinema $cinema)
{
    return view('admin.cinemas.show', compact('cinema'));
}

    
}

