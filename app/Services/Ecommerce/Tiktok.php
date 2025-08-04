<?php

namespace App\Services\Ecommerce;

use App\Services\Ecommerce\EcommercePlatform;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class Tiktok implements EcommercePlatform
{
    private $host;
    private $host2;

    private $app_key;
    private $app_secret;

    public function __construct()
    {
        $this->host = 'https://auth.tiktok-shops.com/api/v2';
        $this->host2 = 'https://open-api.tiktokglobalshop.com';
        $this->app_key = '6dcort8cn1hg9';
        $this->app_secret = '9b7988bd0a8bdf1cf0009fc01c66b53fe3856681';
    }

    public function getToken() {}

    public function refreshAccessToken($token)
    {
        $path = '/token/refresh';
        $response = Http::get($this->host . $path, [
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret,
            'refresh_token' => $token->refresh_token,
            'grant_type' => 'refresh_token'
        ]);

        $response = $response->json();

        DB::table('token_ecommerce')->where('type', 'tiktok')->update([
            'token' => $response['data']['access_token'],
            'expires_in' => $response['data']['access_token_expire_in'],
            'refresh_token' => $response['data']['refresh_token'],
            'refresh_expires_in' => $response['data']['refresh_token_expire_in']
        ]);
    }

    public function checkExpireToken()
    {
        $token = DB::table('token_ecommerce')->where('type', 'tiktok')->first();

        if ($token && $token->expires_in < time()) {
            $this->refreshAccessToken($token);
            $token = DB::table('token_ecommerce')->where('type', 'tiktok')->first();
        }

        return $token;
    }

    public function authorizedShops()
    {
        $url = 'https://open-api.tiktokglobalshop.com/authorization/202309/shops?app_key=' . $this->app_key . '&timestamp=' . time() . '&sign=';

        $token = $this->checkExpireToken();
        $sign = $this->generateSignature($url, $this->app_secret);

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'x-tts-access-token' => $token->token
        ])->get($url, [
            'app_key' => $this->app_key,
            'timestamp' => time(),
            'sign' => $sign
        ]);

        DB::table('token_ecommerce')->where('type', 'tiktok')->update([
            'cipher' => $response['data']['shops'][0]['cipher']
        ]);
    }

    public function getOrders()
    {
        $token = $this->checkExpireToken();
        $path = '/order/202309/orders/search';
        $current_time = time();
        $page_size = 100;
        $now = time();
        $daysAgo = $now - (30 * 24 * 60 * 60);
        $page_token = DB::table('token_ecommerce')->where('type', 'tiktok')->first()->next_cursor;
        $url = 'https://open-api.tiktokglobalshop.com' . $path . '?access_token=' . $token->token . '&app_key=' . $this->app_key . '&page_size=' . $page_size . '&shop_cipher=' . $token->cipher . '&shop_id=&sign=&timestamp=' . $current_time . '&version=202309';
        if($page_token) {
            $url .= '&page_token=' . $page_token;
        }
        $body = '{"create_time_ge": '.$daysAgo.'}';
        // $body = '{    "create_time_ge": '.$sevenDaysAgo.',    "create_time_lt": '.$now.'}';
        $sign = $this->generateSignaturePost($url, $body, $this->app_secret);
        $url = 'https://open-api.tiktokglobalshop.com' . $path . '?access_token=' . $token->token . '&app_key=' . $this->app_key . '&page_size=' . $page_size . '&shop_cipher=' . $token->cipher . '&shop_id=&sign=' . $sign . '&timestamp=' . $current_time . '&version=202309';
        if($page_token) {
            $url .= '&page_token=' . $page_token;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Tts-Access-Token: ' . $token->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $ret = json_decode($result);
        if($ret->data->next_page_token) {
            DB::table('token_ecommerce')->where('type', 'tiktok')->update([
                'more' => $ret->data->next_page_token ? 1 : 0,
                'next_cursor' => $ret->data->next_page_token,
                'day_get_order' => date('Y-m-d')
            ]);
        } else {
            DB::table('token_ecommerce')->where('type', 'tiktok')->update([
                'more' => 0,
                'next_cursor' => 0,
                'day_get_order' => date('Y-m-d')
            ]);
        }

        foreach($ret->data->orders as $item) {
            DB::table('tiktok')->updateOrInsert(
                ['order_code' => $item->id],
                [
                    'order_status' => $item->status,
                    'created_at' => date('Y-m-d', $item->create_time),
                    'response' => json_encode($item),
                    'payment_method' => $item->payment_method_name,
                    'member_id' => 31,
                    'province_id' => '01',
                    'district_id' => '007',
                    'ward_id' => '00256',
                    'address' => 'Tầng 9, Tòa Nhà Gelex Tower, Số 52 Lê Đại Hành, Phường Lê Đại Hành, Quận Hai Bà Trưng',
                ]
            );
        }
  
        // $this->getOrderItems($str_order_sn, $token);
    }

    public function getOrderItems($order, $token) {
        $token = $this->checkExpireToken();
        $path = '/order/202309/orders';
        $page_size = 1;
        $current_time = time();
        $url = 'https://open-api.tiktokglobalshop.com' . $path . '?access_token=' . $token->token . '&app_key=' . $this->app_key . '&shop_cipher=' . $token->cipher . '&shop_id=&sign=&timestamp=' . $current_time . '&version=202309&ids=' . $order;
        // $body = '{    "create_time_ge": '.$sevenDaysAgo.',    "create_time_lt": '.$now.'}';
        $sign = $this->generateSignaturePost($url, '', $this->app_secret);
        $url = 'https://open-api.tiktokglobalshop.com' . $path . '?access_token=' . $token->token . '&app_key=' . $this->app_key . '&shop_cipher=' . $token->cipher . '&shop_id=&sign=' . $sign . '&timestamp=' . $current_time . '&version=202309&ids=' . $order;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Tts-Access-Token: ' . $token->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $ret = json_decode($result);
        dd($ret);
    }


    function generateSignature($url, $secret)
    {
        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'], $queries);
        $keys = [];
        foreach ($queries as $k => $v) {
            if ($k !== "sign" && $k !== "access_token") {
                $keys[] = $k;
            }
        }

        sort($keys);


        $input = "";
        foreach ($keys as $key) {
            $input .= $key . $queries[$key];
        }
        $input = $parsedUrl['path'] . $input;
        $input = $secret . $input . $secret;


        return $this->generateSHA256($input, $secret);
    }

    function generateSignaturePost($url, $body = '', $secret)
    {
        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'], $queries);
        $keys = [];

        foreach ($queries as $k => $v) {
            if ($k !== "sign" && $k !== "access_token") {
                $keys[] = $k;
            }
        }

        sort($keys);
        $input = '';
        foreach ($keys as $key) {
            $input .= $key . $queries[$key];
        }

        $input = $parsedUrl['path'] . $input;

        $content_type = 'application/json';
        if ($content_type !== 'multipart/form-data' && $body) {
            $input .= $body;
        }

        $input = $secret . $input . $secret;

        return $this->generateSHA256($input, $secret);
    }

    function generateSHA256($input, $secret)
    {
        // Generate the HMAC using SHA-256
        $hash = hash_hmac('sha256', $input, $secret, false);
        return ($hash);
    }

    public function getOrderAPI() {
        $page = request()->input('page', 1);
        $perPage = 100;
        $orders = DB::table('tiktok')->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
        return response()->json(['message' => 'success', 'data' => $orders], 200);
    }

    public function getOrderDetailAPI() {
        $order = DB::table('tiktok')->where('order_code', request()->order_code)->first();

        return response()->json(['message' => 'success', 'data' => $order], 200);
    }

}
