<?php

namespace App\Http\Controllers\admin\Authentications;

use App\Http\Controllers\Controller;
use App\Models\admin\Users\Users as UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors([
                0 => 'Tên đăng nhập không được bỏ trống',
                1 => 'Mật khẩu không được bỏ trống',
            ]);
        }
            // dd($credentials);
        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            if ($user->published == 1) {

                $request->session()->regenerate();
                $user->load(['rolePermission' => function ($query) {
                    $query->where('permission', 1);
                }]);

                // $request->session()->put('user', $user);

                // dd(session()->all());

                return redirect()->intended(config('variables.admin'));
            } else {

                Auth::logout();
                return back()->withErrors([
                    'email' => 'Thông tin đăng nhập không chính xác',
                ]);
            }
        }

        return back()->withErrors([
            'error' => 'Tên đăng nhập hoặc mật khẩu không trùng khớp',
        ]);
    }

    public function add_user(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'unique:users'],
                'password' => ['required'],
            ]);
            $user = new UsersModel;
            $user->password = Hash::make($request->input('password'));
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->save();
            return redirect('login')->with('success', 'Tài khoản đã được tạo thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors([
                0 => 'Tên tài khoản hoặc email đã tồn tại.',
            ]);
        }
    }

    public function register(Request $request)
    {
        return view('admin.auth.register');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
