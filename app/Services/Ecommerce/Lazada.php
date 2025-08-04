<?php

namespace App\Services\Ecommerce;

use App\Models\admin\Ecommerce\Lazada as LazadaModel;
use App\Services\Ecommerce\lazada\lazop\LazopClient;
use App\Services\Ecommerce\lazada\lazop\LazopRequest;
use Illuminate\Support\Facades\DB;
use App\Services\Ecommerce\EcommercePlatform;

class Lazada implements EcommercePlatform
{
    private $c;
    private $appKey = '129879';
    private $appSecret = 'QyNbXINEiM5iNVsU1la0eDIzfaWh8UIT';
    private $auth_url = 'https://auth.lazada.com/rest';
    private $url = 'https://api.lazada.vn/rest';

    public function __construct()
    {
        $this->c = new LazopClient($this->url, $this->appKey, $this->appSecret);
    }

    public function getToken()
    {
        // 0_129879_X6Zqq9RqDWXYR3m32MW9IW4q74363
        $code = request()->code;
        $request = new LazopRequest('/auth/token/create', 'GET');
        $request->addApiParam('code', '0_129879_ibCIZbGUo02Rz5H0TWid76rv6218');

        dd($this->c->execute($request));
    }

    public function refreshAccessToken($token)
    {
        $authClient = new LazopClient($this->auth_url, $this->appKey, $this->appSecret);

        $request = new LazopRequest('/auth/token/refresh', 'GET');
        $request->addApiParam('refresh_token', $token->refresh_token);
        $res = json_decode($authClient->execute($request));

        DB::table('token_ecommerce')->updateOrInsert(
            ['type' => 'lazada'],
            [
                'token' => $res->access_token,
                'now' => now()->timestamp,
                'expires_in' => $res->expires_in,
                'refresh_token' => $res->refresh_token,
                'refresh_expires_in' => $res->refresh_expires_in
            ]
        );
    }

    public function checkExpireToken()
    {
        $token = DB::table('token_ecommerce')->where('type', 'lazada')->first();
        if ($token && $token->now + $token->expires_in < time()) {
            $this->refreshAccessToken($token);
            $token = DB::table('token_ecommerce')->where('type', 'lazada')->first();
        }
        return $token;
    }

    public function getOrders()
    {
        // $this->getToken();die;
        $token = $this->checkExpireToken();

        $limit = 100;
        $currentDate = date('Y-m-d');
        $previousDate = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
        $day_get_order = $token->day_get_order;
        
        if ($token->offset + $limit > 10000) {
            return;
        }
        
        if ($previousDate != $day_get_order) {
            DB::table('token_ecommerce')
                ->where('type', 'lazada')
                ->update([
                    'offset' => 0,
                    'day_get_order' => $previousDate
                ]);
            return;
        }
        
        $now = time();
        $startOfDay = strtotime('yesterday', $now);
        $endOfDay = strtotime('tomorrow', $startOfDay) - 1;
        $formattedStartDate = date("c", $startOfDay);
        $formattedEndDate = date("c", $endOfDay + 86400);
        $offset = $token->offset;
        
        $request = new LazopRequest('/orders/get', 'GET');
        $request->addApiParam('offset', $offset);
        $request->addApiParam('limit', $limit);
        $request->addApiParam('update_after', $formattedStartDate);
        $request->addApiParam('update_before', $formattedEndDate);

        $res = $this->c->execute($request, $token->token);

        if ($res && ($ordersData = json_decode($res)) && isset($ordersData->data->orders)) {
            $list_order = $ordersData->data->orders;

            foreach ($list_order as $item) {
                $item->items = $this->getOrderItems($item->order_id, $token->token);
            }

            $this->saveOrder($list_order);

            $count_items = $ordersData->data->count;
            if($count_items == 100) {
                DB::table('token_ecommerce')
                ->where('type', 'lazada')
                ->update([
                    'offset' => $token->offset +  $limit,
                ]);
            }
        }
    }

    public function getOrderItems($order_id, $token)
    {
        $request = new LazopRequest('/order/items/get', 'GET');
        $request->addApiParam('order_id', $order_id);

        $res = $this->c->execute($request, $token);

        return json_decode($res);
    }

    public function saveOrder($list_order)
    {
        foreach ($list_order as $item) {
            $order = LazadaModel::where('order_code', $item->order_id)->first() ?? new LazadaModel();
            $address = $item->address_shipping;
            $order->created_at = $item->created_at;
            $order->name = $item->address_shipping->first_name . ' ' . $item->address_shipping->last_name;
            $order->phone = $address->phone;
            $order->address = "Tầng 9, Tòa Nhà Gelex Tower, Số 52 Lê Đại Hành, Phường Lê Đại Hành, Quận Hai Bà Trưng";
            $order->total_shipping = $item->shipping_fee;
            $order->total = $item->price;
            $order->total_price = $item->price + $item->shipping_fee - $item->voucher_platform - $item->shipping_fee_discount_platform;
            $order->order_code = $item->order_id;
            $order->payment_method = $item->payment_method;
            $order->order_status = $item->statuses[0];
            $order->member_id = 47;
            $order->province_id = "01";
            $order->district_id = "007";
            $order->ward_id = "00256";
            $order->response = json_encode($item);
            $order->save();
        }
    }

    public function getOrderAPI() {
        $page = request()->input('page', 1);
        $perPage = 100;
        $orders = DB::table('lazada')->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
        return response()->json(['message' => 'success', 'data' => $orders], 200);
    }

    public function getOrderDetailAPI() {
        $order = DB::table('lazada')->where('order_code', request()->order_code)->first();

        return response()->json(['message' => 'success', 'data' => $order], 200);
    }

}
