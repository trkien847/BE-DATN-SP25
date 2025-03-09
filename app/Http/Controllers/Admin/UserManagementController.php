<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('admin.UserManagement.list', compact('users'));
    }

    public function create()
    {
        $roles = Role::all(); 
       return view('admin.UserManagement.create', compact('roles')); 
    }
    public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:users,email',
        // 'phone_number' => 'nullable|regex:/^(0[3|5|7|8|9])+([0-9]{8})$/',
        'fullname' => 'nullable|string|max:255',
        'role_id' => 'required|exists:roles,id', 
        'gender' => 'nullable|in:Nam,Nữ,Khác',
        'birthday' => 'nullable|date',
        'status' => 'nullable|in:Online,Offline',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password' => 'required|string|min:6',
    ]);

    $avatarPath = $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null;

    User::create([
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'fullname' => $request->fullname,
        'role_id' => $request->role_id,
        'gender' => $request->gender,
        'birthday' => $request->birthday,
        'status' => $request->status,
        'avatar' => $avatarPath,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('admin.users.list')->with('success', 'Thêm mới người dùng thành công!');
}

    public function edit($id)
    {
        $user = User::findOrFail($id);
    $roles = Role::all(); 
    return view('admin.UserManagement.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validation dữ liệu
    $request->validate([
        'email' => 'required|email|unique:users,email,'.$user->id,
        // 'phone_number' => 'nullable|regex:/^(0[3|5|7|8|9])+([0-9]{8})$/',
        'fullname' => 'nullable|string|max:255',
        'role_id' => 'required|exists:roles,id',
        'gender' => 'nullable|in:Nam,Nữ,Khác',
        'birthday' => 'nullable|date',
        'status' => 'nullable|in:Online,Offline',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password' => 'nullable|string|min:6',
        'address' => 'nullable|string|max:255',
    ]);

    // Cập nhật thông tin người dùng
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;
    $user->fullname = $request->fullname;
    $user->role_id = $request->role_id;
    $user->gender = $request->gender;
    $user->birthday = $request->birthday;
    $user->status = $request->status;

    // Xử lý ảnh đại diện
    if ($request->hasFile('avatar')) {
        // Xóa avatar cũ nếu có
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
    }

    // Cập nhật mật khẩu nếu có nhập mới
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();
    if ($request->filled('address')) {
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            ['address' => $request->address]
        );
    }

    return redirect()->route('admin.users.list')
                     ->with('success', 'Cập nhật người dùng thành công!');
}

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.users.list')->with('success', 'Người dùng đã được xóa!');
    }
    public function detail($id)
{
    $user = User::with('address')->findOrFail($id); 
    return view('admin.UserManagement.detail', compact('user'));
}
}
