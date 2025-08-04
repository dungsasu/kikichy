<?php

namespace App\Http\Controllers\client\Auth\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    public function login(Request $request)
    {
        $loginType = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginType => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('members')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('client.home')->with(['message' => 'Đăng nhập thành công', 'status' => 'success']);
        } else {
            return redirect()->route('client.home')->with(['message' => 'Thông tin đăng nhập không chính xác', 'status' => 'error']);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('members')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('client.home');
    }
}
