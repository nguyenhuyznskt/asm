<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\ActivityLogger;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->orderBy('created_at', 'desc');

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if (!is_null($request->input('active'))) {
            $active = $request->input('active') === '1';
            $query->where('is_active', $active);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Cấp quyền admin
    public function makeAdmin(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể tự chỉnh vai trò của mình.');
        }

        $oldRole = $user->role;

        $user->update(['role' => 'admin']);
        $user->refresh();

        // ✅ Ghi Activity Log
        ActivityLogger::log(
            event: 'user_make_admin',
            description: 'Admin ' . Auth::user()->email . ' cấp quyền admin cho user #' . $user->name,
            subject: $user,
            properties: [
                'target_user_id' => $user->id,
                'target_email'   => $user->email,
                'old_role'       => $oldRole,
                'new_role'       => $user->role,
            ]
        );

        return back()->with('success', 'Đã cấp quyền admin cho tài khoản #' . $user->name);
    }

    // Gỡ quyền admin -> trả về user
    public function removeAdmin(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể tự gỡ quyền admin của mình.');
        }

        $oldRole = $user->role;

        $user->update(['role' => 'user']);
        $user->refresh();

        // ✅ Ghi Activity Log
        ActivityLogger::log(
            event: 'user_remove_admin',
            description: 'Admin ' . Auth::user()->email . ' gỡ quyền admin khỏi user #' . $user->name,
            subject: $user,
            properties: [
                'target_user_id' => $user->id,
                'target_email'   => $user->email,
                'old_role'       => $oldRole,
                'new_role'       => $user->role,
            ]
        );

        return back()->with('success', 'Đã gỡ quyền admin khỏi tài khoản #' . $user->name);
    }

    // Khóa / mở khóa tài khoản
    public function toggleActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể tự khóa tài khoản của mình.');
        }

        $oldActive = $user->is_active;

        $user->update([
            'is_active' => ! $user->is_active,
        ]);
        $user->refresh();

        $action = $user->is_active ? 'mở khóa' : 'khóa';

        // ✅ Ghi Activity Log
        ActivityLogger::log(
            event: 'user_toggle_active',
            description: 'Admin ' . Auth::user()->email . " {$action} tài khoản #" . $user->name,
            subject: $user,
            properties: [
                'target_user_id' => $user->id,
                'target_email'   => $user->email,
                'old_is_active'  => $oldActive,
                'new_is_active'  => $user->is_active,
            ]
        );

        return back()->with('success', $user->is_active
            ? 'Đã mở khóa tài khoản #' . $user->name
            : 'Đã khóa tài khoản #' . $user->name
        );
    }
}
