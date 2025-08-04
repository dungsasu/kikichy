<?php

namespace App\Http\Controllers\client\Cart;

use App\Http\Controllers\Controller;
use App\Models\admin\Product\Product;
use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;
use App\Traits\CommonFunctionTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Services\Facebook\FacebookPixel;
use Illuminate\Support\Facades\Auth;

class CartClient extends Controller
{
    protected $cartService;
    use CommonFunctionTrait;


    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function add_cart()
    {
        $data = request()->all();
        $item = [
            'id' => $data['id'],
            'title' => $data['title'],
            'price' => (int)$this->remove_fomart_money($data['price']),
            'quantity' => (int)$data['quantity'],
            'options' => isset($data['options']) ? $data['options'] : []
        ];
        $this->cartService->addCartItem($item);

        $data_prd = Product::where('id', $data['id'])->first();
        $html = '';

        $category = $data_prd->category->alias;
        $facebookPixel = new FacebookPixel(Auth::guard('members')->user());
        $facebookPixel->addToCartProduct($data_prd, $category, (int)$data['quantity']);


        return response()->json(['status' => 200, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng', 'data' => $data_prd]);
    }

    public function get_cart()
    {
        $cartItems = $this->cartService->getCartItems();
        $product_detail = $this->cartService->getCartProductDetails($cartItems);
        $html = view('client.partials.cart_items', [
            'cart_items' => $cartItems,
            'product_detail' => $product_detail
        ])->render();
        return response()->json(['status' => 200, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng', 'html' => $html, 'quantity' => $this->cartService->getQuantity()]);
    }

    public function update_cart_item()
    {
        $data = request()->all();
        $item = [
            'options' => isset($data['options']) ? $data['options'] : []
        ];
        if (isset($data['quantity'])) {
            $item['quantity'] = $data['quantity'];
        }
        $update_cart = $this->cartService->updateCartItems($data['hash'], $item);
        $product_detail = $this->cartService->getCartProductDetails($update_cart);
        $html = view('client.partials.cart_items', [
            'cart_items' => $update_cart,
            'product_detail' => $product_detail
        ])->render();

        return response()->json([
            'status' => 200,
            'message' => 'Thành công',
            'html' => $html,
            'quantity' => $this->cartService->getQuantity(),
            'total' => $this->cartService->getTotal(),
            'sumAmount' => $this->format_money((int)$this->cartService->sumActionsAmount()),
            'subTotal' => $this->format_money((int)$this->cartService->getSubtotal())
        ]);
    }


    public function clear_cart()
    {
        $this->cartService->clearCart();
        return response()->json(['status' => 200, 'message' => 'Giỏ hàng trống']);
    }

    public function remove_cart_item(Request $request)
    {
        $hash = $request->input('hash');
        $this->cartService->removeCartItem($hash);
        $cartItems = $this->cartService->getCartItems();

        $product_detail = $this->cartService->getCartProductDetails($cartItems);
        $html = view('client.partials.cart_items', [
            'cart_items' => $cartItems,
            'product_detail' => $product_detail
        ])->render();

        return response()->json(['status' => 200, 'message' => 'Thành công', 'html' => $html, 'quantity' => $this->cartService->getQuantity()]);
    }

    public function applyVoucher(Request $request)
    {
        $code = $request->input('voucher');
        $year = Carbon::now()->year;
        $voucher = DB::table("vouchers_" . $year)->where('code', $code)->first();
        if(!$voucher) {
            return response()->json([
                'status' => 400,
                'message' => 'Mã giảm giá không tồn tại'
            ]);
        }
        Session::put('voucher', $voucher);
        $this->cartService->applyVoucher($voucher);

        return response()->json([
            'status' => 200,
            'message' => 'Thành công',
            'data' => [
                'voucher' => $this->cartService->getVoucher($voucher),
                'code' => $voucher->code,
                'shipping' => $this->cartService->getShipping(),
                'total' => $this->cartService->getTotal(),
                'subTotal' => $this->format_money((int)$this->cartService->getSubtotal())
            ]
        ]);
    }

    public function deleteVoucher(Request $request)
    {
        Session::forget('voucher');
        $this->cartService->clearActions();
        return response()->json([
            'status' => 200,
            'message' => 'Thành công',
            'data' => [
                'shipping' => $this->cartService->getShipping(),
                'total' => $this->cartService->getTotal(),
                'subTotal' => $this->format_money((int)$this->cartService->getSubtotal())
            ]
        ]);
    }
}
