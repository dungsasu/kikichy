<?php

namespace App\Services;

use App\Models\admin\Product\ProductAttribute;
use Illuminate\Support\Facades\Http;

class GetflyService
{
    protected $apiUrl;
    protected $apiKey;
    protected $header;

    public function __construct()
    {
        $this->apiUrl = config('services.getfly.url');
        $this->apiKey = config('services.getfly.key');
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-API-KEY' => $this->apiKey
        ];
    }

    public function getProducts($filters = [])
    {
        $params = "fields=product_name,product_code,cover_price,price_online&limit=10&offset=0";
        foreach ($filters as $filterName => $filterValue) {
            $params .= "&filtering[$filterName]=$filterValue";
        }

        $response = Http::withHeaders($this->header)->get($this->apiUrl . "/api/v6/products?$params");

        if ($response->successful()) {
            $data = $response->json();
            return $data;
        }

        throw new \Exception('Failed to fetch accounts: ' . $response->body());
    }

    public function getProduct($productId)
    {
        $response = Http::withHeaders($this->header)->get($this->apiUrl . "/api/v6/products/{$productId}");

        if ($response->successful()) {
            $data = $response->json();
            return $data;
        }

        throw new \Exception('Failed to fetch accounts: ' . $response->body());
    }

    public function addOrder($order)
    {
        $saved_order_details = [];
        foreach ($order->items as $itemOrder) {
            if ($itemOrder->product_attribute->getfly_id) {
                $id = $itemOrder->product_attribute->getfly_id;
            } else {
                $info = $this->getProducts([
                    'product_code' => $itemOrder->product_attribute->code,
                ]);
                
                ProductAttribute::where('id', $itemOrder->product_attribute->id)->update([
                    'getfly_id' => $info['data'][0]['id'],
                ]);

                $id = $info['data'][0]['id'];
            }
            $saved_order_details[] = [
                'id' => $id,
                'product_id' => $id,
                'quantity' => $itemOrder->quantity,
                'price' => $itemOrder->price,
                'product_sale_off' => 0,
                'cash_discount' => 0,
            ];
        }

        $data = [
            'account_code' => $order->telephone,
            'account_name' => $order->name,
            'account_address' => $order->store_id ? $order->store->name : "{$order->address}, {$order->ward->full_name}, {$order->district->full_name}, {$order->province->full_name}",
            'account_email' => $order->email,
            'account_phone' => $order->telephone,
            'order_date' => $order->created_at->format('Y-m-d H:i:s'),
            'saved_order_details' => $saved_order_details,
        ];

        $response = Http::withHeaders($this->header)->post(
            $this->apiUrl . "/api/v6/sale_orders",
            $data
        );

        return [
            'request' => $data,
            'response' => $response->json(),
        ];
    }

    public function getOrder($id)
    {
        $params = "fields=order_code,status,account_id,account_code,account_email,account_phone,account_address&limit=1&offset=0&filtering[id]=$id";

        $response = Http::withHeaders($this->header)->get($this->apiUrl . "/api/v6/sale_orders?$params");

        if ($response->successful()) {
            $data = $response->json();
            return $data;
        }

        throw new \Exception('Failed to fetch accounts: ' . $response->body());
    }

    public function getOrders($arrayId)
    {
        $params = "fields=order_code,status,account_id,account_code,account_email,account_phone,account_address";
        foreach ($arrayId as $i => $id) {
            $params .= "&filtering[id][$i]=$id";
        }

        $response = Http::withHeaders($this->header)->get($this->apiUrl . "/api/v6/sale_orders?$params");

        if ($response->successful()) {
            $data = $response->json();
            return $data;
        }

        throw new \Exception('Failed to fetch accounts: ' . $response->body());
    }
}
