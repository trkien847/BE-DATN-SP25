<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function showLogin()
    {
        return view('client.auth.login');
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->route('index');
        }
        return back()->withErrors(['email' => 'Tài khoản hoặc mật khẩu không chính xác.'])->withInput();
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('index');
    }
    public function showRegister()
    {
        return view('client.auth.register');
    }
    public function register(RegisterRequest $request)
    {
        $fullName = $request->firstname . ' ' . $request->lastname;
        $user = User::create([
            'fullname' => $fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
        ]);
        auth()->login($user);
        return redirect()->route('index');
    }
    public function showProfile()
    {
        $user = auth()->user();
        $address = $user->address->pluck('address');
        $carts = Cart::where('user_id', auth()->id())->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->sell_price;
            return $cart->quantity * $price;
        });
        return view('client.auth.profile', compact('carts', 'subtotal', 'user', 'address'));
    }
    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = auth()->user();

        $updateData = [];

        if ($request->filled('fullname')) {
            $updateData['fullname'] = $request->fullname;
        }

        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('phone_number')) {
            $updateData['phone_number'] = $request->phone_number;
        }
        $user->update($updateData);

        // Cập nhật mật khẩu nếu có nhập
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
            }

            $user->update(['password' => Hash::make($request->new_password)]);
        }

        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật.');
    }

    public function showForgotForm()
    {
        return view('client.auth.forgotPassword');
    }
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Gửi email đặt lại mật khẩu
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => 'Link đặt lại mật khẩu đã được gửi.'])
            : back()->withErrors(['email' => 'Không thể gửi email đặt lại mật khẩu.']);
    }

    /**
     * Hiển thị form đặt lại mật khẩu mới.
     */
    public function showResetForm($token)
    {
        return view('client.auth.reset-password', ['token' => $token]);
    }

    /**
     * Xử lý đặt lại mật khẩu mới.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Mật khẩu đã được cập nhật.')
            : back()->withErrors(['email' => 'Không thể đặt lại mật khẩu.']);
    }
    public function storeAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ]);
        $user = auth()->user();
        $address = $user->address()->create([
            'address' => $request->address
        ]);

        return response()->json([
            'success' => true,
            'id' => $address->id,
            'address' => $address->address,
            'fullname' => $user->fullname, // ✅ Đảm bảo trả về fullname
            'phone_number' => $user->phone_number ?? 'Chưa có' // ✅ Đảm bảo trả về phone_number
        ]);
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $address = $user->address()->findOrFail($id);
        $address->update([
            'address' => $request->address
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyAddress($id)
    {
        $user = auth()->user();
        $address = $user->address()->findOrFail($id);
        $address->delete();

        return response()->json(['success' => true]);
    }
}
