<?php

namespace App\Http\Controllers\client\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\admin\Order\Order as OrderModel;
use App\Models\admin\Payoo\PayooIPN as PayooIPNModel;
use App\Models\admin\Member\Member as MemberModel;
use App\Models\admin\Member\MemberContactInfo as MemberContactInfoModel;
use App\Models\admin\Member\MemberOrder as MemberOrderModel;
use App\Models\admin\Country\Country as CountryModel;
use App\Models\admin\City\City as CityModel;


class Member extends Controller
{
    private $message = [
        'name.required' => 'Bạn chưa nhập tên',
        'email.required' => 'Bạn chưa nhập email',
        'email.email' => 'Email không hợp lệ',
        'phone.required' => 'Bạn chưa nhập số điện thoại',
        'address.required' => 'Bạn chưa nhập địa chỉ',
        'login.required' => 'Bạn cần đăng nhập để thanh toán đơn hàng',
        'login.error' => 'Đăng nhập không thành công',
        'cart.empty' => 'Giỏ hàng trống',
        'order.success' => 'Đặt hàng thành công',
        'order.error' => 'Đặt hàng không thành công'
    ];
    public function index()
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.home')->with(['message' => 'Bạn chưa đăng nhập', 'status' => 'error']);
        }
        return view('client.client_user.account');
    }
    public function orders()
    {
        $member = Auth::guard('members')->user();
        $orders = OrderModel::with('items.product')->where('member_id', $member->id)->orderBy('created_at', 'desc')->paginate(5);

        return view('client.client_user.orders', [
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
        return view('client.client_user.orders_detail', [
            'id' => $id,
            'order_detail' => $order_detail,
            'payoo_info' => $payoo_info
        ]);
    }
    public function ranking()
    {
        return view('client.client_user.ranking');
    }

    public function fetchData_address(Request $request)
    {
        $data = DB::table('members_address')->where('id', $request->id_item_to_fetch)->orderBy('updated_at', 'desc')->first();

        return response()->json([
            'success' => true,
            'message' => 'Địa chỉ được tim thấy.',
            'id' => $request->input('edit_id'),
            'data' => $data,
        ]);
    }
    public function address()
    {
        $member = Auth::guard('members')->user();
        $address_list = DB::table('members_address')->where('member_id', $member->id)->orderBy('updated_at', 'desc')->get();
        $cities =  DB::table('provinces')->get();

        foreach ($address_list as $item) {
            $item->city_name = DB::table('provinces')->where('code', $item->city)->first()->name;
            $item->district_name = DB::table('districts')->where('code', $item->district)->first()->name;
            $item->ward_name =  DB::table('wards')->where('code', $item->ward)->first()->name;
        }
        return view('client.client_user.address', [
            'address_list' => $address_list->toArray(),
            'cities' => $cities,
        ]);
    }
    public function save_address(Request $request)
    {
        $member = Auth::guard('members')->user();
        $cities = DB::table('provinces')->get();
        $province = DB::table('provinces')->where('code', $request->input('city'))->first();
        $district = DB::table('districts')->where('code', $request->input('district'))->first();
        $ward = DB::table('wards')->where('code', $request->input('ward'))->first();

        $request->validate([
            'name_member' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:10,11',
            'address' => 'required|string|max:255',
            'city' => 'required|exists:provinces,code',
            'district' => 'required|exists:districts,code',
            'ward' => 'required|exists:wards,code',
        ], [
            'name_member.required' => 'Bạn chưa nhập tên',
            'name_member.string' => 'Tên phải là chuỗi ký tự',
            'name_member.max' => 'Tên không được vượt quá 255 ký tự',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'phone.numeric' => 'Số điện thoại phải là số',
            'phone.digits_between' => 'Số điện thoại phải có từ 10 đến 11 chữ số',
            'address.required' => 'Bạn chưa nhập địa chỉ',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'city.required' => 'Bạn chưa chọn tỉnh/thành phố',
            'city.exists' => 'Tỉnh/thành phố không hợp lệ',
            'district.required' => 'Bạn chưa chọn quận/huyện',
            'district.exists' => 'Quận/huyện không hợp lệ',
            'ward.required' => 'Bạn chưa chọn phường/xã',
            'ward.exists' => 'Phường/xã không hợp lệ',
        ]);


        if ($request->has('set_default')) {
            DB::table('members_address')
                ->where('member_id', $member->id)
                ->update(['set_default' => 0]);
        }

        if (!$request->input('id')) {
            DB::table('members_address')->insert([
                'name' => $request->input('name_member'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'city' => $province->code,
                'district' => $district->code,
                'ward' => $ward->code,
                'created_at' => now(),
                'updated_at' => now(),
                'member_id' => $member->id,
                'set_default' => $request->has('set_default') ? 1 : 0,
            ]);
        } else {
            DB::table('members_address')->where('id', $request->input('id'))->update([
                'name' => $request->input('name_member'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'city' => $province->code,
                'district' => $district->code,
                'ward' => $ward->code,
                'set_default' => $request->has('set_default') ? 1 : 0,
                'updated_at' => now(),
            ]);
        }

        return view('client.client_user.address', [
            'cities' => $cities,
        ]);
    }
    public function edit_address(Request $request)
    {
        $member = Auth::guard('members')->user();

        if ($request->has('set_default')) {
            DB::table('members_address')
                ->where('member_id', $member->id)
                ->update(['set_default' => 0]);
        }
        DB::table('members_address')->where('id', $request->input('id_edit'))->update([
            'name' => $request->input('member_name'),
            'phone' => $request->input('member_phone'),
            'address' => $request->input('member_address'),
            'city' => $request->input('id_province'),
            'district' => $request->input('id_district'),
            'ward' => $request->input('id_ward'),
            'set_default' => $request->has('set_default') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
            //'member_id' => $request->input(''),
        ]);

        return response()->json(['success' => true, 'message' => 'Địa chỉ được chỉnh sửa thành công.', 'id' => $request->input('edit_id')]);
    }
    public function delete_address(Request $request)
    {
        DB::table('members_address')->where('id', $request->input('id_delete'))->delete();
        return response()->json(['success' => true, 'message' => 'Địa chỉ đã được xóa thành công.', 'id' => $request->input('id_delete')]);
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

    public function showRegisterBusiness()
    {
        return view('client.member_business.register_business');
    }

    public function showLoginBusiness()
    {
        return view('client.member_business.login_business');
    }

    public function loginBusiness(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Vui lòng nhập email hoặc tên đăng nhập',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Tìm member theo email hoặc username
        $member = MemberModel::where('email', $email)
            ->orWhere('username', $email)
            ->first();

        if ($member && Hash::check($password, $member->password)) {
            // Đăng nhập thành công
            Auth::guard('members')->login($member);
            
            // Thiết lập thời gian hoạt động ban đầu
            session(['lastActivityTime' => time()]);
            
            return redirect()->route('client.business.profile')->with('success', 'Đăng nhập thành công!');
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->only('email'));
    }

    public function logout()
    {
        Auth::guard('members')->logout();
        
        // Invalidate session to ensure complete logout
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('client.home.index')->with('warning', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
    }

    public function showProfile()
    {
        // Kiểm tra xem user đã đăng nhập chưa
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business')->with('error', 'Vui lòng đăng nhập để xem thông tin tài khoản.');
        }

        return view('client.member_business.profile');
    }

    public function updateInfo(Request $request)
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business')->with('error', 'Vui lòng đăng nhập để cập nhật thông tin.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'representative' => 'required|string|max:255',
            'username' => 'required|string|max:255',
        ], [
            'company_name.required' => 'Vui lòng nhập tên công ty',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'representative.required' => 'Vui lòng nhập tên người đại diện',
            'username.required' => 'Vui lòng nhập tên đăng nhập',
        ]);

        $member = Auth::guard('members')->user();
        
        // Cập nhật thông tin
        $updateData = [
            'name' => $request->company_name,
            'email' => $request->email,
            'representative_name' => $request->representative,
            'username' => $request->username,
        ];

        // Cập nhật mật khẩu nếu có
        if ($request->password) {
            $request->validate([
                'password' => 'required|min:8|confirmed',
            ], [
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            ]);
            
            $updateData['password'] = Hash::make($request->password);
        }

        MemberModel::where('id', $member->id)->update($updateData);

        return redirect()->route('client.business.profile')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function updateAvatar(Request $request)
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business')->with('error', 'Vui lòng đăng nhập để cập nhật avatar.');
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Vui lòng chọn ảnh',
            'avatar.image' => 'File phải là ảnh',
            'avatar.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif',
            'avatar.max' => 'Ảnh không được vượt quá 2MB',
        ]);

        $member = Auth::guard('members')->user();

        if ($request->hasFile('avatar')) {
            // Tạo thư mục nếu chưa tồn tại
            $avatarDir = public_path('img/avatars');
            if (!file_exists($avatarDir)) {
                mkdir($avatarDir, 0755, true);
            }

            // Xóa ảnh cũ nếu có
            if ($member->image && file_exists(public_path($member->image))) {
                unlink(public_path($member->image));
            }

            // Tạo tên file mới
            $avatarName = time() . '_' . $member->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $avatarPath = 'img/avatars/' . $avatarName;

            // Di chuyển file vào thư mục public/img/avatars
            $request->file('avatar')->move($avatarDir, $avatarName);

            // Cập nhật database với đường dẫn tương đối
            MemberModel::where('id', $member->id)->update([
                'image' => $avatarPath,
            ]);

            return back()->with('success', 'Cập nhật ảnh đại diện thành công!');
        }

        return back()->with('error', 'Có lỗi xảy ra khi tải ảnh lên.');
    }

    public function showInfo()
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business');
        }
        
        // Lấy danh sách quốc gia từ bảng country
        $countries = CountryModel::where('published', 1)
            ->orderBy('ordering', 'asc')
            ->get();
        
        // Lấy thông tin contact của user
        $member = Auth::guard('members')->user();
        $contactInfo = MemberContactInfoModel::where('user_id', $member->id)->first();
        
        $cities = [];
        if ($contactInfo && $contactInfo->country_id) {
            $cities = CityModel::where('country_id', $contactInfo->country_id)
                ->where('published', 1)
                ->orderBy('ordering', 'asc')
                ->get();
        }
        
        return view('client.member_business.info', [
            'countries' => $countries,
            'cities' => $cities,
            'contactInfo' => $contactInfo
        ]);
    }

    public function updateContactInfo(Request $request)
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business')->with('error', 'Vui lòng đăng nhập để cập nhật thông tin.');
        }

        $request->validate([
            'company_legal_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9]{10,11}$/',
            'country_id' => 'required|integer|exists:country,id',
            'city_id' => 'required|integer|exists:cities,id',
            'address' => 'required|string|max:500',
            'website' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
        ], [
            'company_legal_name.required' => 'Vui lòng nhập tên pháp lý của công ty',
            'company_legal_name.string' => 'Tên công ty phải là chuỗi ký tự',
            'company_legal_name.max' => 'Tên công ty không được vượt quá 255 ký tự',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại phải có 10-11 chữ số',
            'country_id.required' => 'Vui lòng chọn quốc gia',
            'country_id.integer' => 'Quốc gia không hợp lệ',
            'country_id.exists' => 'Quốc gia không tồn tại trong hệ thống',
            'city_id.required' => 'Vui lòng chọn thành phố',
            'city_id.integer' => 'Thành phố không hợp lệ',
            'city_id.exists' => 'Thành phố không tồn tại trong hệ thống',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự',
            'website.string' => 'Website phải là chuỗi ký tự',
            'website.max' => 'Website không được vượt quá 255 ký tự',
            'youtube.string' => 'Youtube phải là chuỗi ký tự',
            'youtube.max' => 'Youtube không được vượt quá 255 ký tự',
            'facebook.string' => 'Facebook phải là chuỗi ký tự',
            'facebook.max' => 'Facebook không được vượt quá 255 ký tự',
            'instagram.string' => 'Instagram phải là chuỗi ký tự',
            'instagram.max' => 'Instagram không được vượt quá 255 ký tự',
            'twitter.string' => 'Twitter phải là chuỗi ký tự',
            'twitter.max' => 'Twitter không được vượt quá 255 ký tự',
        ]);

        $member = Auth::guard('members')->user();
        
        // Cập nhật tên công ty trong bảng members
        MemberModel::where('id', $member->id)->update([
            'company_legal_name' => $request->company_legal_name,
        ]);
        
        // Cập nhật hoặc tạo mới thông tin liên hệ
        $contactData = [
            'user_id' => $member->id,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'website' => $request->website,
            'youtube' => $request->youtube,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
        ];

        MemberContactInfoModel::updateOrCreate(
            ['user_id' => $member->id],
            $contactData
        );

        return redirect()->route('client.business.info')->with('success', 'Cập nhật thông tin liên hệ thành công!');
    }

    public function showOrders()
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business');
        }
        
        $member = Auth::guard('members')->user();
        $memberOrder = MemberOrderModel::where('user_id', $member->id)->first();
        
        return view('client.member_business.orders', [
            'memberOrder' => $memberOrder
        ]);
    }

    public function saveOrders(Request $request)
    {
        // Debug: Log toàn bộ request data
        Log::info('saveOrders method called', [
            'user_id' => Auth::guard('members')->id(),
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'order_description_length' => strlen($request->order_description ?? ''),
            'order_summary_business_length' => strlen($request->order_summary_business ?? ''),
        ]);
        
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business')->with('error', 'Vui lòng đăng nhập để lưu thông tin.');
        }

        $request->validate([
            'order_description' => 'nullable|string',
            'order_summary_business' => 'nullable|string',
            'min_age' => 'required|integer|min:1|max:100',
            'max_age' => 'required|integer|min:1|max:100',
            'avg_age' => 'required|integer|min:1|max:100',
            'min_group_size' => 'required|integer|min:1|max:1000',
            'max_group_size' => 'required|integer|min:1|max:1000',
            'avg_group_size' => 'required|integer|min:1|max:1000',
            'private_min_size' => 'required|integer|min:1|max:1000',
            'company_logo' => 'nullable|string', // Thay đổi từ image thành string vì đây là URL
        ], [
            'min_age.required' => 'Vui lòng chọn độ tuổi tối thiểu',
            'max_age.required' => 'Vui lòng chọn độ tuổi tối đa',
            'avg_age.required' => 'Vui lòng chọn nhóm tuổi trung bình',
            'min_group_size.required' => 'Vui lòng chọn kích thước nhóm tối thiểu',
            'max_group_size.required' => 'Vui lòng chọn kích thước nhóm tối đa',
            'avg_group_size.required' => 'Vui lòng chọn kích thước nhóm trung bình',
            'private_min_size.required' => 'Vui lòng chọn kích thước nhóm tối thiểu cho chuyến đi riêng tư',
        ]);

        $member = Auth::guard('members')->user();

        // Xử lý logo - đây là URL từ CKFinder, không phải file upload
        $logoPath = $request->company_logo; // Lưu trực tiếp URL được chọn

        // Chuẩn bị dữ liệu để lưu
        $orderData = [
            'user_id' => $member->id,
            'order_description' => $request->order_description,
            'order_summary_business' => $request->order_summary_business,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
            'avg_age' => $request->avg_age,
            'min_group_size' => $request->min_group_size,
            'max_group_size' => $request->max_group_size,
            'avg_group_size' => $request->avg_group_size,
            'private_min_size' => $request->private_min_size,
        ];

        if ($logoPath) {
            $orderData['logo'] = $logoPath;
        }

        // Cập nhật hoặc tạo mới bản ghi trong bảng members_orders
        try {
            $result = MemberOrderModel::updateOrCreate(
                ['user_id' => $member->id],
                $orderData
            );
            
            // Debug: Log kết quả
            Log::info('MemberOrder saved successfully', [
                'user_id' => $member->id, 
                'result_id' => $result->id,
                'data' => $orderData
            ]);
            
        } catch (\Exception $e) {
            // Debug: Log lỗi nếu có
            Log::error('Error saving MemberOrder', [
                'user_id' => $member->id,
                'error' => $e->getMessage(),
                'data' => $orderData
            ]);
            
            return redirect()->route('client.business.orders')->with('error', 'Có lỗi xảy ra khi lưu thông tin: ' . $e->getMessage());
        }

        return redirect()->route('client.business.orders')->with('success', 'Lưu thông tin trang điều hành thành công!');
    }

    public function showCategories()
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business');
        }
        return view('client.member_business.categories');
    }

    public function showTourManagement()
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business');
        }
        
        // Khởi tạo dữ liệu mặc định để tránh lỗi undefined variable
        $tourTypes = collect();
        $instructions = collect();
        $experientialStyles = collect();
        
        try {
            // Lấy dữ liệu cho các select box
            $tourTypes = \App\Models\admin\Tour\TourTypes::where('published', 1)
                ->orderBy('ordering', 'asc')
                ->get();
                
            $instructions = \App\Models\admin\Tour\Instructions::where('published', 1)
                ->orderBy('ordering', 'asc')
                ->get();
                
            $experientialStyles = \App\Models\admin\Tour\Experiential_style::where('published', 1)
                ->orderBy('ordering', 'asc')
                ->get();
                
        } catch (\Exception $e) {
            // Nếu có lỗi, sử dụng dữ liệu rỗng
        }
        
        return view('client.member_business.tour_management', compact('tourTypes', 'instructions', 'experientialStyles'));
    }

    public function saveTour(Request $request)
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business');
        }

        $request->validate([
            'tour_name' => 'required|string|max:255',
            'tour_code' => 'required|string|max:50',
            'duration' => 'required|integer|min:1',
            'tour_type' => 'required|string',
            'guide_type' => 'required|string',
            'min_participants' => 'required|integer|min:1',
            'max_participants' => 'required|integer|min:1',
            'tour_style' => 'required|array|min:1',
            'tour_style.*' => 'exists:fs_tour_experiential_style,id',
            'departure_from' => 'required|string|max:255',
            'departure_to' => 'required|string|max:255',
        ], [
            'tour_name.required' => 'Vui lòng nhập tên tour',
            'tour_code.required' => 'Vui lòng nhập mã tour',
            'duration.required' => 'Vui lòng nhập số ngày',
            'tour_type.required' => 'Vui lòng chọn loại tour',
            'guide_type.required' => 'Vui lòng chọn loại hướng dẫn',
            'min_participants.required' => 'Vui lòng nhập số người tối thiểu',
            'max_participants.required' => 'Vui lòng nhập số người tối đa',
            'tour_style.required' => 'Vui lòng chọn ít nhất một phong cách tour',
            'tour_style.min' => 'Vui lòng chọn ít nhất một phong cách tour',
            'tour_style.*.exists' => 'Phong cách tour được chọn không hợp lệ',
            'departure_from.required' => 'Vui lòng nhập điểm khởi hành',
            'departure_to.required' => 'Vui lòng nhập điểm đến',
        ]);

        // TODO: Lưu thông tin tour vào database
        // Hiện tại chỉ redirect về với thông báo thành công
        
        return redirect()->route('client.business.tour_management')->with('success', 'Lưu thông tin tour thành công!');
    }

    public function showNotifications()
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business');
        }
        return view('client.member_business.notifications');
    }

    public function showSettings()
    {
        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.login_business');
        }
        return view('client.member_business.settings');
    }

    public function registerBusiness(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'representative_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'username' => 'required|string|max:255|unique:members,ma_kh',
            'password' => 'required|string|min:6|regex:/^[A-Z].*[0-9]/',
            'password_confirmation' => 'required|same:password',
        ], [
            'company_name.required' => 'Bạn chưa nhập tên công ty',
            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'representative_name.required' => 'Bạn chưa nhập tên người đại diện',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'username.required' => 'Bạn chưa nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập đã được sử dụng',
            'password.required' => 'Bạn chưa nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.regex' => 'Mật khẩu phải bắt đầu bằng chữ cái viết hoa và chứa ít nhất 1 số',
            'password_confirmation.required' => 'Bạn chưa nhập xác nhận mật khẩu',
            'password_confirmation.same' => 'Mật khẩu xác nhận không khớp',
        ]);

        try {
            $member = MemberModel::create([
                'name' => $request->representative_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'ma_kh' => $request->username,
                'password' => Hash::make($request->password),
                'name_company' => $request->company_name, // Lưu tên công ty vào name_company
                'type' => 2, // 2 = business member
                'published' => 1,
                'ordering' => 0,
            ]);

            if ($member) {
                // Tự động đăng nhập và chuyển hướng đến trang profile
                Auth::guard('members')->login($member);
                
                return redirect()->route('client.business.profile')->with([
                    'success' => 'Đăng ký tài khoản doanh nghiệp thành công! Chào mừng bạn đến với Kikichy.'
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.'])
                        ->withInput();
        }

        return back()->withErrors(['error' => 'Đăng ký không thành công. Vui lòng thử lại.'])
                    ->withInput();
    }

    public function checkDuplicate(Request $request)
    {
        $type = $request->input('type'); // email, username, phone
        $value = $request->input('value');
        
        $exists = false;
        $message = '';
        
        switch ($type) {
            case 'email':
                $exists = MemberModel::where('email', $value)->exists();
                $message = $exists ? 'Email này đã được sử dụng' : '';
                break;
                
            case 'username':
                $exists = MemberModel::where('ma_kh', $value)->exists();
                $message = $exists ? 'Tên đăng nhập này đã được sử dụng' : '';
                break;
                
            case 'phone':
                $exists = MemberModel::where('phone', $value)->exists();
                $message = $exists ? 'Số điện thoại này đã được sử dụng' : '';
                break;
        }
        
        return response()->json([
            'exists' => $exists,
            'message' => $message
        ]);
    }

    public function getCitiesByCountry(Request $request)
    {
        try {
            Log::info('getCitiesByCountry called with data: ', $request->all());
            
            $countryId = $request->input('country_id') ?? $request->query('country_id');
            
            if (!$countryId) {
                Log::warning('Country ID not provided');
                return response()->json([
                    'success' => false,
                    'message' => 'Country ID is required'
                ]);
            }
            
            Log::info('Searching cities for country_id: ' . $countryId);
            
            // Thử query trực tiếp để test
            $citiesRaw = DB::table('cities')
                ->where('country_id', $countryId)
                ->where('published', 1)
                ->orderBy('ordering', 'asc')
                ->get(['id', 'name']);
            
            Log::info('Raw query found ' . $citiesRaw->count() . ' cities');
            
            $cities = CityModel::where('country_id', $countryId)
                ->where('published', 1)
                ->orderBy('ordering', 'asc')
                ->get(['id', 'name']);
            
            Log::info('Model query found ' . $cities->count() . ' cities');
            
            return response()->json([
                'success' => true,
                'cities' => $cities,
                'debug' => [
                    'raw_count' => $citiesRaw->count(),
                    'model_count' => $cities->count(),
                    'country_id' => $countryId
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getCitiesByCountry: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateActivity(Request $request)
    {
        if (!Auth::guard('members')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Cập nhật thời gian hoạt động trong session
        session(['lastActivityTime' => time()]);

        return response()->json(['success' => true]);
    }
}
