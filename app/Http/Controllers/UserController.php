<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderOrderStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

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
            $user = auth()->user();
            // Kiểm tra trạng thái banned
            if ($user->status === 'banned' && $user->banned_until && now()->lt($user->banned_until)) {
                $remaining = now()->diffInDays($user->banned_until);
                auth()->logout();
                return back()->withErrors([
                    'error' => 'Tài khoản của bạn đã bị tạm khóa. Vui lòng thử lại sau ' . $remaining . ' ngày.'
                ])->withInput();
            }
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
            'avatar' => '/admin/images/users/dummy-avatar.png',
            'password' => Hash::make($request->password),
            'role_id' => 1,
        ]);
        auth()->login($user);
        return redirect()->route('index');
    }
    public function showProfile()
    {
        $user = auth()->user();
        $address = $user->address ? $user->address->address : null;

        $carts = Cart::where('user_id', $user->id)->get();

        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->sell_price;
            return $cart->quantity * $price;
        });

        $orders = Order::where('user_id', $user->id)
            ->with([
                'latestOrderStatus',
                'items.product' => function ($query) {
                    $query->select('id', 'name', 'thumbnail'); 
                },
                'items.product.importProducts' => function ($query) {
                    $query->latest();
                }
            ])
            ->latest()
            ->get();
        $today = Carbon::today();
        $cancelCountToday = OrderOrderStatus::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereIn('order_status_id', [5, 7])
            ->whereDate('created_at', $today)
            ->count();

        return view('client.auth.profile', compact('carts', 'subtotal', 'user', 'address', 'orders', 'cancelCountToday'));
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $updateData = [];

        // Collect validated profile data
        if ($request->filled('fullname')) {
            $updateData['fullname'] = $request->validated('fullname');
        }

        if ($request->filled('email') && $request->email !== $user->email) {
            $updateData['email'] = $request->validated('email');
        }

        if ($request->filled('phone_number')) {
            $updateData['phone_number'] = $request->validated('phone_number');
        }
        if ($request->filled('birthday')) {
            $updateData['birthday'] = $request->birthday;
        }

        if ($request->filled('gender')) {
            $updateData['gender'] = $request->gender;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            try {
                $avatar = $request->file('avatar');
                if ($avatar->isValid()) {
                    // Delete old avatar if it exists
                    if ($user->avatar && file_exists(public_path($user->avatar))) {
                        unlink(public_path($user->avatar));
                    }

                    $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
                    $avatar->move(public_path('upload'), $avatarName);
                    $avatarPath = 'upload/' . $avatarName;

                    // Verify the file was stored
                    if (file_exists(public_path($avatarPath))) {
                        $updateData['avatar'] = $avatarPath;
                    } else {
                        return back()->withErrors(['avatar' => 'Không thể lưu ảnh đại diện.']);
                    }
                } else {
                    return back()->withErrors(['avatar' => 'Vui lòng chọn ảnh hợp lệ.']);
                }
            } catch (\Exception $e) {
                Log::error('Avatar upload failed: ' . $e->getMessage());
                return back()->withErrors(['avatar' => 'Đã xảy ra lỗi khi tải ảnh lên.']);
            }
        }

        // Update password if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
            }
            $updateData['password'] = Hash::make($request->validated('new_password'));
        }

        // Apply updates if there are any
        if (!empty($updateData)) {
            try {
                $user->update($updateData);
            } catch (\Exception $e) {
                Log::error('Profile update failed: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Không thể cập nhật thông tin. Vui lòng thử lại.']);
            }
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
