<?php

namespace App\Http\Controllers\admin\Member;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\admin\Member\Member as MemberModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\admin\Members\GalleryMembers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class Member extends BaseController
{
    public function __construct()
    {
        $model = MemberModel::class;
        $view = 'admin.member';
        $prefix = 'member';
        $this->searchField = 'phone,name,email';
        parent::__construct($model, $view, $prefix);
    }

    public function edit($id)
    {
        // $data = MembersModel::orderBy('id', 'desc')->get();
        $data = MemberModel::where('id', $id)->first();
        $districts = DB::table('districts')->get();
        $provinces = DB::table('provinces')->get();
        $wards = DB::table('wards')->get();
        parent::setData([
            'districts' => $districts,
            'provinces' => $provinces,
            'wards' => $wards,
            'data' => $data
        ]);
        return parent::create();
    }


    public function member_change_password(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới của bạn.',
        ]);
        $id = $request->input('id');

        $member = MemberModel::where('id', $id)->first();

        $member->password = Hash::make($request->get('password'));
        $member->save();

        return redirect()->route($this->view . '.index')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function getVouchers(Request $request)
    {
        try {
            $customer = $request->input('customer');
            
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã khách hàng không hợp lệ'
                ]);
            }

            $vouchers = collect();
            $startYear = 2024;
            $currentYear = now()->year;

            // Tìm voucher từ các bảng vouchers_{{year}}
            for ($year = $startYear; $year <= $currentYear; $year++) {
                $tableName = 'vouchers_' . $year;
                
                // Kiểm tra bảng có tồn tại không
                if (Schema::hasTable($tableName)) {
                    $yearVouchers = DB::table($tableName)
                        ->where('customer', $customer)
                        ->where('customer', '!=', '')
                        ->orderBy('created_time', 'desc')
                        ->get();
                    
                    // Thêm thông tin năm vào mỗi voucher
                    $yearVouchers->transform(function ($voucher) use ($year) {
                        $voucher->year = $year;
                        return $voucher;
                    });
                    
                    $vouchers = $vouchers->merge($yearVouchers);
                }
            }

            // Sắp xếp theo thời gian tạo mới nhất
            $vouchers = $vouchers->sortByDesc('created_time')->values();

            // Kiểm tra thành viên có đủ điều kiện tạo welcome voucher không
            $member = MembersModel::where(function($query) use ($customer) {
                $query->where('ma_kh', $customer)
                      ->orWhere('ma_the', $customer);
            })->first();

            $canCreateWelcome = false;
            if ($member) {
                $eligibleDate = Carbon::create(2025, 6, 6);
                $memberCreatedAt = Carbon::parse($member->created_at);
                
                $canCreateWelcome = $member->status_welcome_voucher != 1 && 
                                  $memberCreatedAt->greaterThanOrEqualTo($eligibleDate) &&
                                  ($member->ma_kh || $member->ma_the);
            }

            return response()->json([
                'success' => true,
                'vouchers' => $vouchers,
                'total' => $vouchers->count(),
                'canCreateWelcome' => $canCreateWelcome,
                'member' => $member ? [
                    'id' => $member->id,
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'ma_kh' => $member->ma_kh,
                    'status_welcome_voucher' => $member->status_welcome_voucher,
                    'created_at' => $member->created_at
                ] : null,
                'message' => $vouchers->count() > 0 
                    ? "Tìm thấy {$vouchers->count()} voucher" 
                    : 'Không tìm thấy voucher nào'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }

    public function createWelcomeVoucher(Request $request)
    {
        try {
            $memberId = $request->input('member_id');
            
            if (!$memberId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID thành viên không hợp lệ'
                ]);
            }

            $member = MemberModel::findOrFail($memberId);
            
            // Kiểm tra điều kiện
            if ($member->status_welcome_voucher == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thành viên đã nhận voucher chào mừng'
                ]);
            }

            if (!$member->ma_kh && !$member->ma_the) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thành viên chưa có mã khách hàng'
                ]);
            }

            $eligibleDate = Carbon::create(2025, 6, 6);
            $memberCreatedAt = Carbon::parse($member->created_at);

            if (!$memberCreatedAt->greaterThanOrEqualTo($eligibleDate)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thành viên không đủ điều kiện nhận voucher (đăng ký trước ngày 5/6/2025)'
                ]);
            }

            // Sử dụng FastService để tạo voucher
            $fastService = app(\App\Services\Fast\FastService::class);
            
            $year = now()->year;
            $tableName = 'vouchers_' . $year;

            // Kiểm tra xem đã có voucher chào mừng chưa
            $ma_kh = $member->ma_kh ? $member->ma_kh : $member->ma_the;
            $existingVoucher = DB::table($tableName)
                ->where('customer', $ma_kh)
                ->where('type', '5')
                ->where('name', 'like', '%Chào mừng thành viên mới%')
                ->where('status', 1)
                ->first();

            if ($existingVoucher) {
                $member->status_welcome_voucher = 1;
                $member->save();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Thành viên đã có voucher chào mừng'
                ]);
            }

            // Tạo voucher mới
            $voucherCode = $fastService->createVoucherCode('WELCOME');

            $voucherData = [
                'code' => $voucherCode,
                'name' => 'Chào mừng thành viên mới - Giảm 200.000đ cho đơn hàng tại cửa hàng',
                'type' => '5',
                'discount' => 200000,
                'date_start' => now(),
                'date_expiration' => now()->addMonths(1),
                'status' => 1,
                'itemgroup1' => 'HH',
                'customer' => $member->ma_kh ? $member->ma_kh : $member->ma_the,
                'bill_from' => 0,
                'offline' => 0,
                'ad_ckc_yn' => 1,
                'ad_ckvip_yn' => 1,
                'ad_cktang_yn' => 1,
                'ad_ckcombo_yn' => 1,
                'ad_trungloai_yn' => 1
            ];

            $voucher = \App\Models\admin\Voucher\Voucher::create($voucherData);

            $payload = [
                'cpid' => time(),
                'cpcode' => $voucherCode,
                'cpname' => $voucherData['name'],
                'cptype' => 5,
                'cpvalue' => 200000,
                'cpdiscountrate' => 0,
                'maximumdiscount' => 0,
                'itemgroup1' => 'HH',
                'timeofuse' => 1,
                'datefrom' => $voucherData['date_start']->format('Y-m-d'),
                'dateto' => $voucherData['date_expiration']->format('Y-m-d'),
                'customer' => $member->ma_kh ? $member->ma_kh : $member->ma_the,
                'valuefrom' => 0,
                'ad_ckc_yn' => 1,
                'ad_ckvip_yn' => 1,
                'ad_cktang_yn' => 1,
                'ad_ckcombo_yn' => 1,
                'ad_trungloai_yn' => 1
            ];

            $link = $fastService->getLink();
            $type = $fastService->getType();

            $response = $fastService->getHttpClient()->post($link . $type['create_voucher'], $payload);

            DB::table('log_fast')->insert([
                'request' => json_encode($payload),
                'response' => json_encode($response->json()),
                'created_at' => now(),
                'url' => $link . $type['create_voucher'],
                'success' => $response->json()['isSuccess'] ? $response->json()['isSuccess'] : 0,
            ]);

            if ($response->json()['isSuccess']) {
                $member->status_welcome_voucher = 1;
                $member->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Tạo voucher chào mừng thành công',
                    'voucher' => [
                        'code' => $voucherCode,
                        'name' => $voucherData['name'],
                        'discount' => $voucherData['discount']
                    ]
                ]);
            } else {
                $voucher->delete();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Tạo voucher thất bại trên hệ thống Fast'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }

}
