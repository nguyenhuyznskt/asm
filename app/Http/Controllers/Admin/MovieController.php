<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\ActivityLogger;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('genre')->orderBy('created_at', 'desc');

        if ($search = $request->input('q')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($genreId = $request->input('genre_id')) {
            $query->where('genre_id', $genreId);
        }

        $movies = $query->paginate(10)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return view('admin.movies.index', compact('movies', 'genres'));
    }

    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.movies.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:movies,slug',
            'description'      => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
    
            // nhận file ảnh
            'poster'           => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:8192',
    
            'release_date'     => 'required|date|after_or_equal:today',
            'age_rating'       => 'nullable|string|max:255',
            'is_featured'      => 'sometimes|boolean',
            'genre_id'         => 'nullable|exists:genres,id',
        ]);
    
        // Nếu slug trống thì tự sinh
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title'] . '-' . uniqid());
        }
    
        // checkbox nổi bật
        $data['is_featured'] = $request->boolean('is_featured');
    
        // Lưu poster vào storage/app/public/movies/posters
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('movies/posters', 'public');
            // cột poster_url trong DB lưu đường dẫn tương đối
            $data['poster_url'] = $posterPath;
        }
    
        // Lưu banner vào storage/app/public/movies/banners
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('movies/banners', 'public');
            $data['banner_url'] = $bannerPath;
        }
    
        $movie = Movie::create($data);

    // GHI LOG
    ActivityLogger::log(
        event: 'Thêm phim',
        description: 'Admin tạo phim ' . $movie->title,
        subject: $movie, // 🔥 Quan trọng: để có subject_type + subject_id
        properties: [
            // mày muốn lưu id hay tên thể loại tuỳ:
            // 'genre' => $movie->genre->name ?? null,
            'genre_id'     => $movie->genre_id,
            'release_date' => $movie->release_date?->toDateString(), // lúc này đã là Carbon (nếu cast)
            'is_featured'  => (bool) $movie->is_featured,
        ]
    );
       
    
        return redirect()->route('admin.movies.index')
            ->with('success', 'Thêm phim thành công');
    }
    

    public function edit(Movie $movie)
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.movies.edit', compact('movie', 'genres'));
    }

    public function update(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:movies,slug,' . $movie->id,
            'description'      => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
    
            // file ảnh mới (nếu có)
            'poster'           => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:8192',
    
            'release_date'     => 'required|date', // nếu muốn vẫn >= hôm nay thì thêm after_or_equal:today
            'age_rating'       => 'nullable|string|max:255',
            'is_featured'      => 'sometimes|boolean',
            'genre_id'         => 'nullable|exists:genres,id',
        ]);
    
        // Nếu slug trống thì tự sinh lại
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title'] . '-' . $movie->id);
        }
    
        // checkbox nổi bật
        $data['is_featured'] = $request->boolean('is_featured');
    
        // Nếu upload poster mới => xóa poster cũ (nếu có) + lưu mới
        if ($request->hasFile('poster')) {
            if ($movie->poster_url && Storage::disk('public')->exists($movie->poster_url)) {
                Storage::disk('public')->delete($movie->poster_url);
            }
    
            $posterPath = $request->file('poster')->store('movies/posters', 'public');
            $data['poster_url'] = $posterPath;
        }
    
        // Nếu upload banner mới => xóa banner cũ (nếu có) + lưu mới
        if ($request->hasFile('banner')) {
            if ($movie->banner_url && Storage::disk('public')->exists($movie->banner_url)) {
                Storage::disk('public')->delete($movie->banner_url);
            }
    
            $bannerPath = $request->file('banner')->store('movies/banners', 'public');
            $data['banner_url'] = $bannerPath;
        }
    
        $movie->update($data);
        ActivityLogger::log(
            event: 'Thêm phim',
            description: 'Admin cập nhật phim ' . $movie->title,
            subject: $movie, // 🔥 Quan trọng: để có subject_type + subject_id
            properties: [
                // mày muốn lưu id hay tên thể loại tuỳ:
                // 'genre' => $movie->genre->name ?? null,
                'genre_id'     => $movie->genre_id,
                'release_date' => $movie->release_date?->toDateString(), // lúc này đã là Carbon (nếu cast)
                'is_featured'  => (bool) $movie->is_featured,
            ]
        );
    
        return redirect()->route('admin.movies.index')
            ->with('success', 'Cập nhật phim thành công');
    }
    

    public function destroy(Movie $movie)
    {
        $movie->delete();
        ActivityLogger::log(
            event: 'Thêm phim',
            description: 'Admin xóa phim ' . $movie->title,
            subject: $movie, // 🔥 Quan trọng: để có subject_type + subject_id
            properties: [
                // mày muốn lưu id hay tên thể loại tuỳ:
                // 'genre' => $movie->genre->name ?? null,
                'genre_id'     => $movie->genre_id,
                'release_date' => $movie->release_date?->toDateString(), // lúc này đã là Carbon (nếu cast)
                'is_featured'  => (bool) $movie->is_featured,
            ]
        );

        return redirect()->route('admin.movies.index')
            ->with('success', 'Xóa phim thành công');
    }
    public function show(Movie $movie)
{
    $movie->load('genre');
    return view('admin.movies.show', compact('movie'));
}

}

