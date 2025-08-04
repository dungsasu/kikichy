<?php

namespace App\Services\Ecommerce;

use Illuminate\Support\Facades\Http;
use App\Services\Ecommerce\EcommercePlatform;
use Illuminate\Support\Facades\DB;

class Shopee implements EcommercePlatform
{
    private $partnerId;
    private $partnerKey;
    private $shopId;

    function __construct()
    {
        $this->partnerId = config('shopee.production.partner_id');
        $this->partnerKey = config('shopee.production.partner_key');
        $this->shopId = config('shopee.production.shop_id');
    }
    function authShop()
    {
        $path = "/api/v2/shop/auth_partner";
        $host = config('shopee.production.baseUrl');
        $partnerId = config('shopee.production.partner_id');
        $partnerKey = config('shopee.production.partner_key');
        $redirectUrl = route('client.ecommerce.shopee_redirect');

        $timest = time();
        $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s&redirect=%s", $host, $path, $partnerId, $timest, $sign, $redirectUrl);
        return $url;
    }

    public function getToken()
    {
        $host = config('shopee.production.baseUrl');
        $path = "/api/v2/auth/token/get";
        $partnerId = $this->partnerId;
        $partnerKey = $this->partnerKey;

        $timest = time();
        $body = array("code" => '726368674d4654476d62727276466a49',  "shop_id" => $this->shopId, "partner_id" => $partnerId);
        $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partnerId, $timest, $sign);


        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($c);
        echo "raw result: $resp";

        $ret = json_decode($resp, true);
        $accessToken = $ret["access_token"];
        $newRefreshToken = $ret["refresh_token"];
        DB::table('token_ecommerce')->where('type', 'shopee')->update([
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'now' => time(),
            'expires_in' => $ret['expire_in'],
            'type' => 'shopee'
        ]);

        return $ret;
    }

    public function refreshAccessToken($refreshToken)
    {
        $host = config('shopee.production.baseUrl');
        $path = "/api/v2/auth/access_token/get";
        $partnerId = $this->partnerId;
        $partnerKey = $this->partnerKey;

        $timest = time();
        $body = array("partner_id" => $partnerId, "shop_id" => $this->shopId, "refresh_token" => $refreshToken);
        $baseString = sprintf("%s%s%s", $partnerId, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $host, $path, $partnerId, $timest, $sign);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($c);
        $ret = json_decode($result, true);

        $accessToken = $ret["access_token"];
        $newRefreshToken = $ret["refresh_token"];

        DB::table('token_ecommerce')->where('type', 'shopee')->update([
            'token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'now' => time(),
            'expires_in' => $ret['expire_in'],
        ]);

        echo $ret;
    }

    public function checkExpireToken()
    {
        $token = DB::table('token_ecommerce')->where('type', 'shopee')->first();

        if ($token && $token->now + $token->expires_in < time()) {
            $this->refreshAccessToken($token->refresh_token);
            $token = DB::table('token_ecommerce')->where('type', 'shopee')->first();
        }
        return $token;
    }

    public function getOrders()
    {
        $token = $this->checkExpireToken();

        $currentDate = date('Y-m-d');
        $previousDate = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
        $day_get_order = $token->day_get_order;

        if ($currentDate != $day_get_order) {
            DB::table('token_ecommerce')
                ->where('type', 'shopee')
                ->update([
                    'more' => 1,
                    'next_cursor' => 0,
                    'day_get_order' => $currentDate
                ]);
            return;
        }
        // if ($token->more == false) {
        //     return response()->json(['message' => 'No more order'], 200);
        // }

        $host = config('shopee.production.baseUrl');
        $path = "/api/v2/order/get_order_list";
        $partnerId = $this->partnerId;
        $partnerKey = $this->partnerKey;

        $time_to = time(); // Thời gian hiện tại tính bằng giây
        $time_from = strtotime('-1 days', $time_to);

        $timest = time();
        $baseString = sprintf("%s%s%s%s%s", $partnerId, $path, $timest, $token->token, $this->shopId);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $page_size = 10;
        $cursor = $token->next_cursor;
        $response_optional_fields = 'order_status';
        $print_r = "%s%s?access_token=%s&page_size=%s&cursor=%s&partner_id=%s&shop_id=%s&sign=%s&time_from=%s&time_range_field=%s&time_to=%s&timestamp=%s&response_optional_fields=%s";
        $url = sprintf($print_r, $host, $path, $token->token, $page_size, $cursor, $partnerId, $this->shopId, $sign, $time_from, 'create_time', $time_to, time(), $response_optional_fields);

        $response = Http::withOptions([
            'allow_redirects' => true,
            'timeout' => 0,
        ])->get($url);

        $ret = $response->json();


        if ($ret['error'] == '') {
            DB::table('token_ecommerce')->where('type', 'shopee')->update([
                'more' => !empty($ret['response']['more']) ? 1 : 0,
                'next_cursor' => !empty($ret['response']['next_cursor']) ? $ret['response']['next_cursor'] : 0,
                'day_get_order' => date('Y-m-d')
            ]);


            $array_order_sn = [];
            foreach ($ret['response']['order_list'] as $order) {
                DB::table('shopee')->updateOrInsert(
                    ['order_code' => $order['order_sn']],
                    [
                        'order_status' => $order['order_status'],
                        'payment_response' => $this->getEscrowDetail($order['order_sn'], $token)
                    ]
                );

                $array_order_sn[] = $order['order_sn'];
                

            }
            $str_order_sn = implode(',', $array_order_sn);
            $this->getOrderItems($str_order_sn, $token);
        }
    }
    public function getOrderItems($str_order_sn, $token)
    {
        $host = config('shopee.production.baseUrl');
        $path = "/api/v2/order/get_order_detail";
        $partnerId = $this->partnerId;
        $partnerKey = $this->partnerKey;

        $time_to = time(); // Thời gian hiện tại tính bằng giây

        $timest = time();
        $baseString = sprintf("%s%s%s%s%s", $partnerId, $path, $timest, $token->token, $this->shopId);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $response_optional_fields = 'buyer_user_id,buyer_username,estimated_shipping_fee,recipient_address,actual_shipping_fee ,goods_to_declare,note,note_update_time,item_list,pay_time,dropshipper, dropshipper_phone,split_up,buyer_cancel_reason,cancel_by,cancel_reason,actual_shipping_fee_confirmed,buyer_cpf_id,fulfillment_flag,pickup_done_time,package_list,shipping_carrier,payment_method,total_amount,buyer_username,invoice_data,no_plastic_packing,order_chargeable_weight_gram';
        $print_r = "%s%s?access_token=%s&sign=%s&partner_id=%s&timestamp=%s&shop_id=%s&order_sn_list=%s&response_optional_fields=%s";
        $url = sprintf($print_r, $host, $path, $token->token, $sign, $partnerId, time(), $this->shopId, $str_order_sn, $response_optional_fields);

        $response = Http::withOptions([
            'allow_redirects' => true,
            'timeout' => 0,
        ])->get($url);

        $ret = $response->json();

        if ($ret['error'] == '') {
            foreach ($ret['response']['order_list'] as $order) {
                DB::table('shopee')->updateOrInsert(
                    ['order_code' => $order['order_sn']],
                    [
                        'total_price' => $order['total_amount'],
                        'response' => json_encode($order),
                        'payment_method' => $order['payment_method'],
                        'created_at' => date('Y-m-d', $order['create_time']) ,
                        'province_id' => '01',
                        'district_id' => '007',
                        'ward_id' => '00256',
                        'address' => 'Tầng 9, Tòa Nhà Gelex Tower, Số 52 Lê Đại Hành, Phường Lê Đại Hành, Quận Hai Bà Trưng',
                        'member_id' => 30,
                        'note' => $order['note']
                    ]
                );

            }
        }
    }

    public function getEscrowDetail($order_sn, $token) {
        $host = config('shopee.production.baseUrl');
        $path = "/api/v2/payment/get_escrow_detail";
        $partnerId = $this->partnerId;
        $partnerKey = $this->partnerKey;
        $timest = time();
        $baseString = sprintf("%s%s%s%s%s", $partnerId, $path, $timest, $token->token, $this->shopId);
        $sign = hash_hmac('sha256', $baseString, $partnerKey);
        $print_r = "%s%s?access_token=%s&sign=%s&partner_id=%s&timestamp=%s&shop_id=%s&order_sn=%s";
        $url = sprintf($print_r, $host, $path, $token->token, $sign, $partnerId, time(), $this->shopId, $order_sn);

        $response = Http::withOptions([
            'allow_redirects' => true,
            'timeout' => 0,
        ])->get($url);

        $ret = $response->json();

        return $ret;
    }

}
