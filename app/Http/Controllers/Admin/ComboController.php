<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use Illuminate\Http\Request;

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
            'image_url'   => 'nullable|url',
            'is_active'   => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

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
            'image_url'   => 'nullable|url',
            'is_active'   => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

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
