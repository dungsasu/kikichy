<?php

namespace App\Http\Controllers\client\Pay;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\admin\Mail\MailController;
use App\Models\admin\Voucher\Voucher;
use App\Services\CartService;
use App\Models\admin\Order\Order;
use App\Models\admin\Order\OrderItem;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use App\Models\admin\Config\Config as ConfigModel;
use App\Models\admin\VnUnits\District;
use App\Models\admin\VnUnits\Province;
use App\Models\admin\VnUnits\Ward;
use App\Services\Payment\PaymentContext;
use App\Services\Payment\PaymentStrategy;
use App\Services\Fast\FastService;
use App\Traits\CommonFunctionTrait;
use App\Models\admin\Payoo\PayooIPN;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\admin\Product\Product as ProductModel;
use App\Services\Facebook\FacebookPixel;

class Pay
{
    protected $dataOrder;
    public $cartService;
    private $paymentStrategy;
    private $fast;
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
    private $send_mail = 1;
    private $send_fast;


    use CommonFunctionTrait;

    public function __construct(CartService $cartService, PaymentStrategy $paymentStrategy, FastService $fast)
    {
        $this->cartService = $cartService;
        $this->paymentStrategy = $paymentStrategy;
        $this->fast = $fast;
        $this->send_fast = env('SEND_FAST', 1);
    }
    public function index()
    {
        if (!Auth::guard('members')->check()) {
            return redirect(route('client.home'))->with([
                'message' => $this->message['login.required'],
                'status' => 'error',
                'script' => "$('#modalToggle').modal('show')"
            ]);
        }
        if ($this->cartService->getQuantity() == 0) {
            return redirect(route("client.home"))->with(['message' => $this->message['cart.empty'], 'status' => 'error']);
        }
        $cities =  DB::table('provinces')->get();
        $member = Auth::guard('members')->user();
        $address_list = DB::table('members_address')->where('member_id', $member->id)->orderBy('updated_at', 'desc')->get();
        $get_default_adress = DB::table('members_address')->where('set_default', 1)->first();
        foreach ($address_list as $item) {
            $item->city_name = DB::table('provinces')->where('code', $item->city)->first()->name;
            $item->district_name = DB::table('districts')->where('code', $item->district)->first()->name;
            $item->ward_name =  DB::table('wards')->where('code', $item->ward)->first()->name;
        }

        $cartItems = $this->cartService->getCartItems();
        foreach ($cartItems as $key => $item) {
            $product = ProductModel::find($item['id']);
            if ($product) {
                $cartItems[$key]['id'] = $product->id;
                $cartItems[$key]['price'] = $product->price_campain ? (int)str_replace(['.', ' ₫'], '', $product->price_campain) : $product->price;
                $cartItems[$key]['price_old'] = $product->price_old_campain ? (int)str_replace(['.', ' ₫'], '', $product->price_old_campain) : $product->price_old;
            }
        }

        $cartProductDetail = $this->cartService->getCartProductDetails($cartItems);

        $facebookPixel = new FacebookPixel(Auth::guard('members')->user());
        $facebookPixel->trackAddPaymentInfo($cartProductDetail);

        return view('client.pay.purchase_form', [
            'cities' => $cities,
            'member' => $member,
            'address_list' => $address_list->toArray(),
            'get_default_adress' => $get_default_adress,
        ]);
    }

    public function submit(Request $request)
    {
        $data = $request->all();
        $paymentStrategy = $this->paymentStrategy;
        $fast = $this->fast;

        // if (empty($cartItems)) {
        //     return redirect(route("client.home"))->with('message', $this->message['order.error']);
        // }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => $this->message['name.required'],
            'email.required' => $this->message['email.required'],
            'email.email' => $this->message['email.email'],
            'phone.required' => $this->message['phone.required'],
            'address.required' =>   $this->message['address.required'],
        ]);

        $member = Auth::guard('members')->user();
        if (!$member->name) {
            $member->name = $data['name'];
        }
        if (!$member->phone) {
            $member->phone = $data['phone'];
        }
        if (!$member->province_id) {
            $member->province_id = $data['province_id'];
        }
        if (!$member->district_id) {
            $member->district_id = $data['district_id'];
        }
        if (!$member->ward_id) {
            $member->ward_id = $data['ward_id'];
        }
        if (session()->has('voucher')) {
            $voucher = $this->cartService->applyVoucher(session('voucher'));
        }
        $member->save();
        $order = new Order();
        $order->name = $data['name'];
        $order->province_id = $data['province_id'];
        $order->district_id = $data['district_id'];
        $order->ward_id = $data['ward_id'];
        $order->email = $data['email'];
        $order->phone = $data['phone'];
        $order->address = $data['address'];
        $order->note = $data['note'];
        $order->total_shipping = $this->remove_fomart_money($this->cartService->getShipping());
        $order->voucher_code = session('voucher') ? session('voucher')->name : '';
        $order->total_discount = session('voucher') ? $this->remove_fomart_money($this->cartService->getVoucher($voucher)) : 0;
        $order->total = $this->remove_fomart_money($this->cartService->getTotal());
        $order->total_price = $this->remove_fomart_money($this->cartService->getSubTotal());
        $paymentMethod = $request->input('payment_method');
        $order->payment_method = $paymentMethod;
        $order->payment_status = 0; // Thanh toán không thành công
        $order->order_status = 0; // Đặt hàng không thành công

        if ($paymentMethod == 'cod') {
            $order->order_status = 1; //Đặt hàng thành công
        }
        $order->member_id = $member->id;
        $order->new = 1;
        $order->save();
        $prefix = env('APP_NAME', 'ORD');
        $code = date('dmy');
        $order->order_code = "{$prefix}{$code}{$order->id}";
        $order->save();
        $this->save_extend($order->id);
        $order->validity_time = date('YmdHis', time() + 30 * 60);

        $this->useVoucher();

        if ($paymentMethod == 'cod') {
            unset($order->validity_time);
            $order->save();

            if ($this->send_fast) {
                $payload_order = $fast->createPayloadOrder($order);
                $fast->createOrder($payload_order);
            }

            if ($this->send_mail) {
                $this->sendMail($order);
            }

            return redirect(route('client.pay_success', ['data' => $order->id]));
        } else {
            $paymentContext = new PaymentContext($paymentStrategy);
            $paymentContext->executePayment($order);
        }
    }

    public function useVoucher() {
        if (session()->has('voucher')) {
            Voucher::where('id', session('voucher')->id)->update(['status' => 2, 'used' => 1]);
        }
    }

    public function save_extend($id)
    {
        $cartItems = $this->cartService->getCartItems();
        foreach ($cartItems as $item) {
            $order_item = new OrderItem();
            $order_item->order_id = $id;
            $order_item->product_id = $item->id;
            if($item->id) {
                $data_product = DB::table('products')->where('id', $item->id)->first();
                $order_item->price_old = $data_product->price;
            }
            $order_item->quantity = $item->quantity;
            $order_item->price = $item->price;
            $order_item->total = $item->quantity * $this->remove_fomart_money($item->price);
            $order_item->options = json_encode($item->options);
            $order_item->save();
        }
    }

    public function order_success()
    {
        $member = Auth::guard('members')->user();
        if (!$member) {
            return redirect(route('client.home'))->with([
                'message' => $this->message['login.required'],
                'status' => 'error',
                'script' => "$('#modalToggle').modal('show')"
            ]);
        }
        $data = Order::where('id', request()->data)->where('member_id', $member->id)->first();
        $cart_temp = $this->cartService->getDataCart();
        $cartItems = $this->cartService->getCartItems();
        $cartProductDetail2 = $this->cartService->getCartProductDetails($cartItems);

        if ($this->cartService->getQuantity() == 0) {
            return redirect(route("client.home"))->with('message', $this->message['cart.empty']);
        }
        $this->cartService->clearCart();
        $this->cartService->clearActions();

        if (session()->has('voucher')) {
            session()->forget('voucher');
        }

        $facebookPixel = new FacebookPixel(Auth::guard('members')->user());
        $facebookPixel->trackPurchase($cartItems, $this->cartService->getSubTotal());
        
        return view(
            'client.pay.purchase_successful',
            [
                'data' => $data,
                'cart2' => $cart_temp,
                'cartProductDetail2' => $cartProductDetail2
            ]
        );
    }
    public function sendMail($dataOrder)
    {
        $cartService = app(CartService::class);

        $order_items = OrderItem::where('order_id', $dataOrder['id'])->with('product')->get();
        $configs = ConfigModel::all();

        $temp = [];
        foreach ($configs as $config) {
            $temp[$config['alias']] = $config['value'];
        }

        $province = Province::where('code', $dataOrder['province_id'])->first();
        $district = District::where('code', $dataOrder['district_id'])->first();
        $ward = Ward::where('code', $dataOrder['ward_id'])->first();

        $data = [
            'config' => $temp,
            'order' => $dataOrder['order_code'],
            'name' => $dataOrder['name'],
            'title' => $this->message['order.success'],
            'cart' =>  $cartService->getDataCart(),
            'order_items' => $order_items,
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
            'dataOrder' => $dataOrder
        ];

        // $emailContent = (new Email($data, $this->message['order.success']))->render();
        // print_r($emailContent);die;

        Mail::to($dataOrder['email'])->send(new Email($data, $this->message['order.success']));

        return response()->json([
            'status' => 200,
            'message' => 'Thành công'
        ], 200);
    }

    public function payoo_redirect(Request $request)
    {
        $payooConfig = config('payoo.' . env('PAYOO_ENV'));
        $queryParams = $request->all();

        $status = $request->query('status');
        $order_no = $request->query('order_no');
        $rq_checksum = $request->query('checksum');

        Log::info('Payoo redirect: ' . json_encode($queryParams));
        if ($request->has(['order_no', 'status', 'checksum'])) {
            $checksum = hash("sha512", $payooConfig['CHECKSUMKEY'] . '.' . $order_no . '.' . $status);
            if ($rq_checksum == $checksum) {
                $lastUnderscorePosition = strrpos($order_no, "_");
                $orderNo = substr($order_no, $lastUnderscorePosition + 1);

                $order = Order::where('order_code', $order_no)->first();

                Order::where('order_code', $order_no)->update([
                    "payment_status" => $status,
                    "payment_info" => json_encode($queryParams),
                    "order_code" => $order_no,
                    "order_status" => $status
                ]);
                if ($status == '1') {
                    return redirect()->route('client.pay_success', ['data' => $order]);
                } else {
                    return redirect()->route('client.home')->with(['message' => $this->message['order.error'], 'status' => 'error']);
                }
            }
        }
    }

    public function payoo_redirect_app(Request $request)
    {
        $fast = $this->fast;

        $payooConfig = config('payoo.' . env('PAYOO_APP_ENV'));
        $queryParams = $request->all();

        $status = $request->query('status');
        $order_no = $request->query('order_no');
        $rq_checksum = $request->query('checksum');
    
        Log::info('Payoo redirect: ' . json_encode($queryParams));
        if ($request->has(['order_no', 'status', 'checksum'])) {
            $checksum = hash("sha512", $payooConfig['CHECKSUMKEY'] . '.' . $order_no . '.' . $status);

            if ($rq_checksum == $checksum) {
                $lastUnderscorePosition = strrpos($order_no, "_");
                $orderNo = substr($order_no, $lastUnderscorePosition + 1);

                $order = Order::where('order_code', $order_no)->first();

                Order::where('order_code', $order_no)->update([
                    "payment_status" => $status,
                    "payment_info" => json_encode($queryParams),
                    "order_code" => $order_no,
                    "order_status" => $status
                ]);

                if ($this->send_mail) {
                    $this->sendMail($order);
                }

                if(env('PAYOO_APP_ENV') == 'production') {
                    
                    if ($this->send_fast) {
                        $payload_order = $fast->createPayloadOrder($order);
                        $fast->createOrder($payload_order);
                    }
                }

 
            }
        }
    }
    public function payoo_ipn(Request $request)
    {
        $fast = $this->fast;

        $response = $request->all();
        $decoded = $response;
        $responseData = json_decode($decoded['ResponseData'], true);
        $orderNo = $responseData['OrderNo'];
        $parts = explode('_', $orderNo);
        $lastPart = end($parts);

        $payooIPN = new PayooIPN;
        $payooIPN->response = json_encode($request->all());
        $payooIPN->order_code = $orderNo;

        $payooIPN->save();

        $PaymentStatus = $responseData['PaymentStatus'];
        $order = Order::where('order_code', $orderNo)->first();
        \Illuminate\Support\Facades\Log::info($order);
        $order->order_code = $orderNo;
        $order->payment_status = $PaymentStatus;
        $order->order_status = 1;

        $order->save();

        Log::info('Payoo IPN: ' . json_encode($request->all()));
        if ($this->send_fast) {
            $payload_order = $fast->createPayloadOrder($order);
            $fast->createOrder($payload_order);
        }

        if ($this->send_mail) {
            $this->sendMail($order);
        }

        \Illuminate\Support\Facades\Log::info($request->all());
        return response()->json(['ReturnCode' => 0]);
    }
}
