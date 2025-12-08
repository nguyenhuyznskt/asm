<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComboController extends Controller
{
    public function index()
    {
        $combos = Combo::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.combos.index', compact('combos'));
    }

    public function create()
    {
        return view('admin.combos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'sometimes|boolean',
        ]);
    
        $data['is_active'] = $request->boolean('is_active');
    
        // Upload ảnh vào storage/app/public/combos
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('combos', 'public');
            // lưu path vào cột image_url (hoặc image_path tùy DB của mày)
            $data['image_url'] = $path; // ví dụ: combos/abc123.jpg
        }
    
        Combo::create($data);
    
        return redirect()->route('admin.combos.index')
            ->with('success', 'Thêm combo thành công');
    }
    

    public function edit(Combo $combo)
    {
        return view('admin.combos.edit', compact('combo'));
    }

    public function update(Request $request, Combo $combo)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'sometimes|boolean',
        ]);
    
        $data['is_active'] = $request->boolean('is_active');
    
        // ✅ nếu upload ảnh mới thì xóa ảnh cũ + lưu ảnh mới
        if ($request->hasFile('image')) {
    
            // xóa ảnh cũ
            if ($combo->image_url && Storage::disk('public')->exists($combo->image_url)) {
                Storage::disk('public')->delete($combo->image_url);
            }
    
            // lưu ảnh mới
            $path = $request->file('image')->store('combos', 'public');
            $data['image_url'] = $path;
        }
    
        $combo->update($data);
    
        return redirect()->route('admin.combos.index')
            ->with('success', 'Cập nhật combo thành công');
    }
    

    public function destroy(Combo $combo)
    {
        $combo->delete();

        return redirect()->route('admin.combos.index')
            ->with('success', 'Xóa combo thành công');
    }

    public function show(Combo $combo)
{
    return view('admin.combos.show', compact('combo'));
}

}
