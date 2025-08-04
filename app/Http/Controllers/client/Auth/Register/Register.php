<?php

namespace App\Http\Controllers\client\Auth\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Member\Member as Member;
use App\Services\Fast\FastService;
use Illuminate\Support\Facades\Hash;

class Register extends Controller
{
    protected $Fast;
    public function __construct(FastService $Fast)
    {
        $this->Fast = $Fast;
    }
    public function register(Request $request)
    {
        $member = new Member();

        $member->email = $request->input('email');
        $password = $request->input('password');
        $member->name = $request->input('name');
        $member->phone = $request->input('phone');
        $member->dob = $request->input('dob');
        $member->published = 1;

        $hashedPassword = Hash::make($password);
        $member->password = $hashedPassword;
        $member->save();

        $rs = $this->Fast->createMember($member);

        if ($rs->status() == 400) {
            $member->delete();
            return $rs;
        }

        return response()->json(['success' => true, 'message' => 'Đăng ký thành công']);
    }

    public function changePassword()
    {
        $request = request();
        $request->validate([
            'email' => 'required|email|exists:members,email',
            'new_password' => 'required',
        ]);

        $member = Member::where('email', $request->input('email'))->first();

        $newPassword = Hash::make($request->input('new_password'));
        $member->password = $newPassword;

        $member->save();

        return response()->json(['success' => true, 'message' => 'Đổi mật khẩu thành công']);
    }
}
