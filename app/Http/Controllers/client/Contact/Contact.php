<?php

namespace App\Http\Controllers\client\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Contact\Contact as ContactModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Contact extends Controller
{
    public function index()
    {
        return view('client.contact.index');
    }
    public function submit_form_contact(Request $request)
    {
        $regexPhone = "/^(\+\d{1,3}[- ]?)?\d{10}$/";
        $regexEmail = "/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/";
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15', "regex:$regexPhone"],
            'email' => ['required', 'email', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);
        //dd($request->input("phone"));

        DB::beginTransaction();
        try {
            ContactModel::create([
                'name' => $request->input('fullname'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'title' => $request->input('title'),
                'content' => $request->input('content'),
            ]);
            DB::commit();

            Log::info('đã gửi liên hệ thành công', [
                'name' => $request->input('fullname'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'title' => $request->input('title'),
                'content' => $request->input('content'),
            ]);

        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['message' => 'Có lỗi xảy ra, vui lòng thử lại!', 'status' => 'error']);
        }


        return redirect()->back()->with(['message' => 'Cảm ơn bạn đã gửi liên hệ!', 'status' => 'success']);
    }

}