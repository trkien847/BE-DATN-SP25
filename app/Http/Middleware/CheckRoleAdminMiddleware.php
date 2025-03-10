<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // Kiểm tra role_id của user (chỉ cho phép role_id = 2 hoặc 3)
        if (!in_array(Auth::user()->role_id, [2, 3])) {
            return redirect('/')->with('no_access', 'Bạn không có quyền truy cập vào trang này!');
        }

        return $next($request);
    }
}
