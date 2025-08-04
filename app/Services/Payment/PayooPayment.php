<?php

namespace App\Services\Payment;

use App\Models\admin\Order\OrderItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\admin\Payoo\LogPayoo;

class PayooPayment implements PaymentStrategy
{

    private $checksum;
    private $payooConfig;
    private $PAYOO_ENV;

    public function __construct()
    {
        $this->payooConfig = config('payoo.' . env('PAYOO_ENV', 'sandbox'));
        $this->PAYOO_ENV = env('PAYOO_ENV', 'sandbox');
    }

    public function getOrderXML($order)
    {
        $payooConfig = $this->payooConfig;
        $order_items = OrderItem::where('order_id', $order['id'])->get();

        $orderItemsHtml = view('client.partials.payoo_detail', ['list' => $order_items])->render();
        return '<shops><shop><username>' . $payooConfig['BUSINESS_USERNAME'] . '</username><shop_id>' . $payooConfig['SHOP_ID'] . '</shop_id><shop_title>' . $payooConfig['SHOP_TITLE'] . '</shop_title><shop_domain>' . $payooConfig['SHOP_DOMAIN'] . '</shop_domain><order_no>' . $order['order_code'] . '</order_no><order_cash_amount>' . $order['total_price'] . '</order_cash_amount><order_ship_date></order_ship_date><order_ship_days>0</order_ship_days><order_description>' . urlencode($orderItemsHtml) . '</order_description><shop_back_url>' . $payooConfig['SHOP_BACK_URL'] . '</shop_back_url><notify_url>' . $payooConfig['NOTIFY_URL'] . '</notify_url><validity_time>' . $order['validity_time'] . '</validity_time><customer><name>' . $order['name'] . '</name><phone>' . $order['phone'] . '</phone><address>' . $order['address'] . '</address><email>' . $order['email'] . '</email></customer><count_down>1</count_down><direct_return_time>3</direct_return_time><JsonResponse>true</JsonResponse></shop></shops>';
    }

    protected function sendAPI($action, $data)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($action, $data);

            LogPayoo::create([
                'request' => json_encode($data),
                'response' => $response->body(),
                'created_at' => now()
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Error sending API request: " . $e->getMessage());
            return null;
        }
    }

    public function pay($order)
    {
        $orderXML = $this->getOrderXML($order);
        $this->checksum = hash("sha512", $this->payooConfig['CHECKSUMKEY'] . $orderXML);
        $params = array(
            'data' => $orderXML,
            'checksum' => $this->checksum,
            'refer' => $this->payooConfig['SHOP_DOMAIN'],
            'payment_group' => "cc,bank-account,qr-pay"
        );
        $result = $this->sendAPI($this->payooConfig['PGW_ENDPOINT'] . 'checkout', $params);

        if (is_array($result) && isset($result['result']) && $result['result'] == 'success') {
            // return redirect()::to($result['order']['payment_url']);
            header("Location: {$result['order']['payment_url']}");
            exit;
        }
    }
}
