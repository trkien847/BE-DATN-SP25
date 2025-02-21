<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            return redirect()->route('home');
        }
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
}
