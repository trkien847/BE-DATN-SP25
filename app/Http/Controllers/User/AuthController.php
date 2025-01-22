<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // form register
    public function formRegister()
    {
        return view('auth.register');
    }
    // register
    public function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:255',
            'birthday' => 'required|date',
            'password' => 'required|min:8',
            'gender' => 'required|in:Nam,Nữ',     
        ]);
       
        // Lưu dữ liệu vào database
        User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'birthday' => $request->birthday,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'avatar' => 'default_avatar.png',
            'role' => 'user',
            'status' => 'Offline',
            'email_verified_at' => null, // Email chưa được xác minh
            'verified_at' => null,      // Tài khoản chưa được phê duyệt
            'google_id' => 0, // Đảm bảo cung cấp giá trị cho google_id
            'loyalty_points' => 0,
        ]);
    
        return redirect()->route('register.form')->with('success', 'Đăng ký thành công!');
    }

    // form login
    public function formLogin(){
        return view('auth.login');
    }

    // login
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    
        // Lấy thông tin email và mật khẩu từ request
        $credentials = $request->only('email', 'password');
    
        // Kiểm tra email có tồn tại không
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return redirect()->back()->with('loginError', 'Email không tồn tại.');
        }
    
        // Kiểm tra mật khẩu (nếu cần debug)
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('loginError', 'Mật khẩu không đúng.');
        }
    
        // Thử đăng nhập
        if (Auth::attempt($credentials, $request->remember)) {
            $user->status = 'Online';
            $user->save();
            return redirect()->route('register.form')->with('success', 'Đăng nhập thành công!');
        }
    
        return redirect()->back()->with('loginError', 'Đăng nhập không thành công.');
     }
}
