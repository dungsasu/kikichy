<?php

namespace App\Http\Controllers\client\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Fast\FastService;

class Ecommerce
{
    private $service;
    private $fast_service;
    public function __construct() {}

    public function handleDynamicMethod($ecommerce, $func)
    {
        $service = app()->make('EcommerceServiceInterface', ['platform' => $ecommerce]);
        $this->fast_service = new FastService();
        $this->service = $service;
        if (method_exists($this, $func)) {
            return $this->$func();
        }
        return response()->json(['message' => 'Method not found'], 404);
    }

    public function get_token_lazada()
    {
        $this->service->getToken();
    }
    public function get_refresh_token()
    {
        $this->service->authorizedShops();
    }

    public function get_orders()
    {
        return $this->service->getOrders();
    }

    public function get_orders_api()
    {
        return $this->service->getOrderAPI();
    }

    public function get_order_detail_api()
    {
        $order_code = request()->order_code;
        return $this->service->getOrderDetailAPI($order_code);
    }

    public function shopee_fast()
    {
        $result = $this->process_fast_orders('shopee', ["READY_TO_SHIP", "PROCESSED", "SHIPPED", "TO_CONFIRM_RECEIVE", "COMPLETED"], 'SPE');
        if($result) {
            return response()->json(['message' => 'Shoppe thành công'], 200);
        }
        return response()->json(['message' => 'Không còn đơn hàng Shopee nào'], 200);
    }

    public function tiktok_fast()
    {
        $result = $this->process_fast_orders('tiktok', ["DELIVERED", "COMPLETED", "AWAITING_SHIPMENT", "AWAITING_COLLECTION", "PARTIALLY_SHIPPING", "IN_TRANSIT"], 'K16');
        if($result) {
            return response()->json(['message' => 'Tiktok thành công'], 200);
        }
        return response()->json(['message' => 'Không còn đơn hàng Tiktok nào'], 200);
    }

    public function lazada_fast()
    {
        $result = $this->process_fast_orders('lazada', ["ready_to_ship", "shipped", "confirmed", "delivered"], 'LAZ');
        if($result) {
            return response()->json(['message' => 'Lazada thành công'], 200);
        }
        return response()->json(['message' => 'Không còn đơn hàng Lazada nào'], 200);
    }


    private function getPlatformData($platform, $response)
    {
        switch ($platform) {
            case 'shopee':
                return [$response->order_income, $response->order_income->items];
            case 'tiktok':
                return [$response->payment, $response->line_items];
            case 'lazada':
                return [$response, $response->items->data];
            default:
                return [null, []];
        }
    }

    private function createProductArray($platform, $line_items, $voucher_detail)
    {
        $arrProducts = [];
        $discount_item = 0;
        $discount_per_item = 0;
        $remainder = 0;
        $bundle_deal = true;

        if ($voucher_detail) {
            $item_count = 0;

            $total_discount = (int)$voucher_detail->discount;
            foreach ($line_items as $prd) {
                if ($prd->activity_type !== 'bundle_deal') {
                    $bundle_deal = false;
                }
            }
            if ($bundle_deal) {
                foreach ($line_items as $prd) {
                    if ($prd->activity_type == 'bundle_deal') {
                        for ($i = 0; $i < $prd->quantity_purchased; $i++) {
                            $item_count += 1;
                        }
                    }
                }
            } else {
                foreach ($line_items as $prd) {
                    if ($prd->activity_type !== 'bundle_deal') {
                        for ($i = 0; $i < $prd->quantity_purchased; $i++) {
                            $item_count += 1;
                        }
                    }
                }
                $item_count = $item_count ? $item_count : 1;
            }
            $discount_per_item = intdiv($total_discount, $item_count);
            $remainder = $total_discount % $item_count;
        }
        foreach ($line_items as $index => $prd) {
            switch ($platform) {
                case 'shopee':
                    if ($prd->activity_type == 'bundle_deal') {
                        for ($i = 0; $i < $prd->quantity_purchased; $i++) {
                            $arrProduct = [];
                            $arrProduct["ItemCode"] = $prd->model_sku;
                            $arrProduct["Quantity"] = 1;
                            $arrProduct["Price"] = $prd->original_price / $prd->quantity_purchased;
                            $arrProduct["Discount"] = $prd->seller_discount / $prd->quantity_purchased;
                            $arrProduct["Amount"] = $prd->discounted_price / $prd->quantity_purchased;
                            $arrProducts[] = $arrProduct;
                        }
                    } else {
                        $discount_item = $discount_per_item;
                        if ($index < $remainder) {
                            $discount_item += 1;
                        }
                        for ($i = 0; $i < $prd->quantity_purchased; $i++) {
                            $arrProduct = [];
                            $arrProduct["ItemCode"] = $prd->model_sku;
                            $arrProduct["Quantity"] = 1;
                            $arrProduct["Price"] = $prd->original_price / $prd->quantity_purchased;
                            $arrProduct["Discount"] = $prd->seller_discount / $prd->quantity_purchased + $discount_item;
                            $arrProduct["Amount"] = ($prd->discounted_price / $prd->quantity_purchased) - ($discount_item / $prd->quantity_purchased);
                            $arrProducts[] = $arrProduct;
                        }
                    }
                    break;
                case 'tiktok':
                    $arrProducts[] = [
                        "ItemCode" => $prd->seller_sku,
                        "Quantity" => 1,
                        "Price" => $prd->original_price,
                        "Discount" => $prd->seller_discount,
                        "Amount" => $prd->original_price - $prd->seller_discount
                    ];
                    break;
                case 'lazada':
                    $arrProducts[] = [
                        "ItemCode" => $prd->sku,
                        "Quantity" => 1,
                        "Price" => $prd->item_price,
                        "Discount" => $discount_item,
                        "Amount" => $prd->item_price - $discount_item
                    ];
                    break;
            }
        }
        if ($bundle_deal) {
            foreach ($arrProducts as $key => $prd) {
                $discount_item = $discount_per_item;
                if ($key < $remainder) {
                    $discount_item += 1;
                }
                $arrProducts[$key]["Discount"] = $prd["Discount"] + $discount_item;
                $arrProducts[$key]["Amount"] = $prd["Amount"] - $discount_item;
            }
        }

        return $arrProducts;
    }


    // private function createProductArray($platform, $line_items, $voucher_detail)
    // {
    //     $arrProducts = [];
    //     $discount_item = 0;
    //     if ($voucher_detail) {
    //         $discount_item = (int)$voucher_detail->discount / count($line_items);
    //     }
    //     foreach ($line_items as $prd) {
    //         switch ($platform) {
    //             case 'shopee':
    //                 for($i = 0; $i < $prd->quantity_purchased; $i++) {
    //                     $arrProduct = [];
    //                     $arrProduct["ItemCode"] = $prd->model_sku;
    //                     $arrProduct["Quantity"] = 1;
    //                     $arrProduct["Price"] = $prd->original_price / $prd->quantity_purchased;
    //                     $arrProduct["Discount"] = $prd->seller_discount / $prd->quantity_purchased;
    //                     $arrProduct["Amount"] = $prd->discounted_price / $prd->quantity_purchased;
    //                     $arrProducts[] = $arrProduct;
    //                 }
    //                 break;
    //             case 'tiktok':
    //                 $arrProducts[] = [
    //                     "ItemCode" => $prd->seller_sku,
    //                     "Quantity" => 1,
    //                     "Price" => $prd->original_price,
    //                     "Discount" => $prd->seller_discount,
    //                     "Amount" => $prd->original_price - $prd->seller_discount
    //                 ];
    //                 break;
    //             case 'lazada':
    //                 $arrProducts[] = [
    //                     "ItemCode" => $prd->sku,
    //                     "Quantity" => 1,
    //                     "Price" => $prd->item_price,
    //                     "Discount" => $discount_item,
    //                     "Amount" => $prd->item_price - $discount_item
    //                 ];
    //                 break;
    //         }
    //     }
    //     return $arrProducts;
    // }

    private function calculateTotalAmount($platform, $payment_response, $line_items)
    {
        $total_amount = 0;
        // foreach ($line_items as $prd) {
        //     if ($platform == 'shopee') {
        //         $total_amount += $prd->discounted_price ;
        //     }
        // }
        if ($platform == 'shopee') {
            $total_amount = $payment_response->original_cost_of_goods_sold - $payment_response->voucher_from_seller;
        }
        if ($platform == 'tiktok') {
            $total_amount = (int)$payment_response->original_total_product_price - (int)$payment_response->seller_discount;
        }
        if ($platform == 'lazada') {
            $total_amount = (int)$payment_response->price - $payment_response->voucher_seller;
        }
        return $total_amount;
    }

    private function shouldCreateVoucher($payment_response, $voucher_code)
    {
        return ((int)($payment_response->voucher_from_seller ?? 0)
            // ?: ((int)($payment_response->seller_discount ?? 0) 
            ?: (int)($payment_response->voucher_seller ?? 0)) > 0
            && $voucher_code == null;
    }

    private function createAndSaveVoucher($platform, $response, $payment_response)
    {

        if ($platform == 'shopee') {
            $voucher_amount = $payment_response->voucher_from_seller;
            $order_code = $response->order_sn;
        }
        if ($platform == 'tiktok') {
            $voucher_amount = $payment_response->seller_discount;
            $order_code = $response->id;
        }
        if ($platform == 'lazada') {
            $voucher_amount = $payment_response->voucher_seller;
            $order_code = $response->order_id;
        }

        $res = $this->fast_service->createVoucherForOrder($voucher_amount, $platform);
        $ret = $res->getData(true);

        $res_coupon = $this->fast_service->createVoucherFast($ret['voucher_code']);
        if ($res_coupon->json()['isSuccess']) {
            DB::table($platform)->where('order_code', $order_code)->update(['voucher_code' => $ret['voucher_code']]);
        } else {
            DB::table($platform)->where('order_code', $order_code)->update(['voucher_code' => '0']);
        }
    }


    private function process_fast_orders($platform, $statuses, $platform_code)
    {
        $query = DB::table($platform)
            ->where("fast", 0)
            ->whereIn("order_status", $statuses)
            ->where('response', '!=', null)
            ->orderBy("created_at", "asc")
            ->take(100);
        if ($platform == 'shopee') {
            $query->where('payment_response', '!=', null);
        }
        $list_orders = $query->get();

        if (count($list_orders) == 0) {
            return false;
        }

        foreach ($list_orders as $item) {
            if ($platform == 'shopee') {
                $response = json_decode($item->payment_response)->response;
                if (!$response) {
                    continue;
                }
            } else if ($platform == 'tiktok') {
                $response = json_decode($item->response);
                if (!@$response->payment) {
                    continue;
                }
            } else if ($platform == 'lazada') {
                $response = json_decode($item->response);
                if (!@$response) {
                    continue;
                }
            }
            list($payment_response, $line_items) = $this->getPlatformData($platform, $response);
            if (!$payment_response || !$line_items) {
                continue;
            }
            if ($this->shouldCreateVoucher($payment_response, $item->voucher_code)) {
                $this->createAndSaveVoucher($platform, $response, $payment_response);
            }
        }
        $list_orders_after_create_voucher = $query->get();
        $data = [];

        foreach ($list_orders_after_create_voucher as $item) {
            if ($platform == 'shopee') {
                $response = json_decode($item->payment_response)->response;
                if (!$response) {
                    continue;
                }
            } else if ($platform == 'tiktok') {
                $response = json_decode($item->response);
                if (!@$response->payment) {
                    continue;
                }
            } else if ($platform == 'lazada') {
                $response = json_decode($item->response);
                if (!@$response) {
                    continue;
                }
            }

            list($payment_response, $line_items) = $this->getPlatformData($platform, $response);
            if (!$payment_response || !$line_items) {
                continue;
            }

            $voucher_detail = DB::table('vouchers_' . date('Y'))->where('code', $item->voucher_code)->first();

            $arrProducts = $this->createProductArray($platform, $line_items, $voucher_detail);
            $total_amount = $this->calculateTotalAmount($platform, $payment_response, $line_items);

            //neu tạo voucher thất bại thì thử lại
            if ($item->voucher_code == '0') {
                $this->createAndSaveVoucher($platform, $response, $payment_response);
            }

            if ($item->voucher_code != '0' || $item->voucher_code == null) {
                $data[] = $this->fast_service->createPayloadOrderEcommerce($item, $arrProducts, $platform_code, $total_amount);
            }
        }

        
        foreach ($data as $val) {
            $res = $this->fast_service->createOrder($val);

            if ($res) {
                DB::table($platform)->where("order_code", $val['VoucherCode'])->update(["fast" => 1]);
            }
        }
        return true;
    }


    public function ecommerce_to_fast()
    {
        $this->shopee_fast();
        $this->tiktok_fast();
        $this->lazada_fast();
    }
}
