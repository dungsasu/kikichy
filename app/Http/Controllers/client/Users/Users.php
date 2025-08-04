<?php

namespace App\Http\Controllers\client\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\admin\Order\Order as OrderModel;
use App\Models\admin\Payoo\PayooIPN as PayooIPNModel;

class Users extends Controller
{
    public function index()
    {
        if(!Auth::guard('members')->check()) {
            return redirect()->route('client.home')->with(['message' => 'Bạn chưa đăng nhập', 'status' => 'error']);
        }
        return view('client.client_user.account');
    }
    public function orders()
    {
        $member = Auth::guard('members')->user();
        $orders = OrderModel::with('items.product')->where('member_id', $member->id)->orderBy('created_at', 'desc')->paginate(5);
        //dd($orders->items);
        
        return view('client.client_user.orders',[
            'orders' => $orders
        ]);
    }
    public function orders_detail($id)
    {
        $order_detail = OrderModel::with('items.product')->where('id', $id)->first();

        $payoo_info = new \stdClass();
        if ($order_detail->payment_method == 'payoo') {
            $payoo_info_raw = PayooIPNModel::where('order_code', $order_detail->order_code)->first();
            if (!is_null($payoo_info_raw)) {
                $payoo_info_decoded = json_decode($payoo_info_raw->response);
                if (!is_null($payoo_info_decoded) && property_exists($payoo_info_decoded, 'ResponseData')) {
                    $payoo_info = json_decode($payoo_info_decoded->ResponseData);
                }
            }
        }
        return view('client.client_user.orders_detail',[
            'id' => $id,
            'order_detail' => $order_detail,
            'payoo_info' => $payoo_info
        ]);
    }
    public function ranking()
    {
        return view('client.client_user.ranking');
    }
    public function address()
    {
        return view('client.client_user.address');
    }
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'old_password' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect(route('client.user_account'))->with('error', 'Yêu cầu nhập mật khẩu cũ');
        }
    
        try {
            $request->validate([
                'new_password' => 'required|min:8|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect(route('client.user_account'))->with('error', 'Yêu cầu nhập mật khẩu mới');
        }
    
        $member = Auth::guard('members')->user();
    
        if (!Hash::check($request->old_password, $member->password)) {
            return redirect(route('client.user_account'))->with('error', 'Mật khẩu cũ không đúng');
        }
    
        $member->password = Hash::make($request->new_password);
        $member->save();
    
        return redirect(route('client.user_account'))->with('success', 'Đổi mật khẩu thành công');
    }
}