<?php

namespace App\Http\Controllers\client\Auth\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Mail\VerificationCodeMail;
use App\Models\admin\Member\Member;

class Verification extends Controller
{
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $type = $request->input('type');
        if($type == 'register') {
            if (Member::where('email', $email)->exists()) {
                return response()->json(['status' => 'error', 'message' => 'Email đã tồn tại']);
            }
        }
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= random_int(0, 9);
        }
        Session::put('verification_code', $code);
        Mail::to($email)->send(new VerificationCodeMail($code));

        return response()->json(['status' => 'success', 'message' => 'Verification code sent.', 'code' => $code]);
    }

    public function checkMail(Request $request)
    {
        $customMessages = [
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
        ];
    
        try {
            $request->validate([
                'email' => 'required|email',
            ], $customMessages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->errors()
            ], 200);
        }
        
        $email = $request->input('email');
        if (Member::where('email', $email)->exists()) {
            return response()->json(['status' => 'error', 'message' => ['email' => 'Email đã tồn tại']]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'OK']);
        }
    }

    public function verifyOtp(Request $request)
    {
        $otp = $request->input('otp');
        if(!$otp) {
            $otp = $request->input('otp-forgot');
        }
        $storedOtp = $request->session()->get('verification_code');

        if ($otp == $storedOtp) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Mã xác minh không hợp lệ.']);
        }
    }
}
