<?php

namespace App\Http\Controllers\client\Promotion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\admin\Promotion\Voucher as VoucherModel;
use App\Services\CartService;

class Voucher extends Controller
{
    public $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function checkVoucher($voucherCode)
    {
        $voucher = VoucherModel::where('code', $voucherCode)
            ->where('published', 1)
            ->whereDate('date_start', '<=', Carbon::now())
            ->whereDate('date_end', '>=', Carbon::now())
            ->first();

        if (!$voucher) {
            return [
                'status' => 'error',
                'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn!',
                'data' => []
            ];
        }

        if ($voucher->quantity && $voucher->quantity == $voucher->used) {
            return [
                'status' => 'error',
                'message' => 'Mã giảm giá đã hết lượt sử dụng!',
                'data' => []
            ];
        }

        if ($voucher->min_price && $voucher->min_price > $this->cartService->getSubTotal()) {
            return [
                'status' => 'error',
                'message' => "Tổng giá trị đơn hàng không đủ để sử dụng mã giảm giá! Vui lòng tiêu thêm " . $this->cartService->format_money($voucher->min_price - $this->cartService->getSubTotal()) . " để sử dụng mã giảm giá!",
            ];
        }

        return $voucher;
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher' => 'required|string',
        ]);

        $this->cartService->clearVoucher();

        $voucher = $this->checkVoucher($request->voucher);
        
        if ($voucher['status'] == 'error') {
            return response()->json($voucher);
        }

        $voucherAmount = $this->cartService->applyVoucher($voucher);

        return response()->json([
            'status' => 'success',
            'message' => 'Mã giảm giá hợp lệ!',
            'data' => [
                'data' => $this->cartService->cart,
                'amount' => $voucherAmount,
                'name' => $voucher->name,
                'code' => $voucher->code,
            ],
        ]);
    }
}
