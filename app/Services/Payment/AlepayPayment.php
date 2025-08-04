<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use App\Models\admin\Order\OrderItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\admin\Alepay\LogAlepay;
use App\Models\admin\Order\Order;
use App\Mail\Email;
use App\Services\OrderService;

class AlepayPayment implements PaymentStrategy
{
    private $baseUrl;
    private $tokenKey;
    private $checksumKey;
    private $encryptKey;
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->baseUrl = config('payment.alepay.base_url');
        $this->tokenKey = config('payment.alepay.token_key');
        $this->checksumKey = config('payment.alepay.checksum_key');
        $this->encryptKey = config('payment.alepay.encrypt_key');
        $this->orderService = $orderService;
    }

    private function generateSignature($data)
    {
        $stringData = [];
        foreach ($data as $key => $value) {
            // if (is_bool($value)) {
            //     $value = $value ? 'true' : 'false';
            // }
            // $stringData[] = "$key=$value";
            $stringData[] = $key . "=" . $value;
        }
        $stringData = implode('&', $stringData);
        return hash_hmac("sha256", $stringData, $this->checksumKey);
    }

    private function generateData($data)
    {
        ksort($data);
        $signature = $this->generateSignature($data);
        $data['signature'] = $signature;
        return $data;
    }

    private function getTransactionInfo($transactionCode)
    {
        $data = [
            'tokenKey' => $this->tokenKey,
            'transactionCode' => $transactionCode
        ];
        
        $data = $this->generateData($data);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/get-transaction-info', $data);

        return $response->json();
    }

    public function return(Request $request)
    {
        $transactionCode = $request->transactionCode;
        $errorCode = $request->errorCode;

        if ($transactionCode) {
            if ($errorCode == '000') {
                $alepay = LogAlepay::where('transaction_code', $transactionCode)->first();

                $alepay->update([
                    'return' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                ]);

                $order = Order::where('id', $alepay->order_id)->first();
                $order->update([
                    'payment_status' => 1,
                ]);

                Email::sendAdminOrderMail($order);
                $this->orderService->addOrder($order);

                return redirect(route('client.pay_success', ['data' => $alepay->order_id]));
            } else {
                return redirect()->route('client.cart.index')->with('error', 'Thanh toán thất bại! Mã lỗi: ' . $errorCode);
            }
        } else {
            return redirect()->route('client.home')->with('error', 'Không tìm thấy mã giao dịch!');
        }
    }

    public function cancel()
    {
        $transactionCode = session()->get('alepay_transaction_code', '');
        $alepay = LogAlepay::where('transaction_code', $transactionCode)->first();

        if ($transactionCode && $alepay) { 
            $transactionInfo = $this->getTransactionInfo($transactionCode);
            
            $alepay->update([
                'cancel' => json_encode($transactionInfo, JSON_UNESCAPED_UNICODE),
            ]);
            $order = Order::where('id', $alepay->order_id)->first();
            $order->update([
                'payment_status' => 2,
                'order_status' => 3,
            ]);

            return redirect(route('client.pay_fail', ['data' => $alepay->order_id]));
        } else {
            return redirect()->route('client.home')->with('error', 'Không tìm thấy mã giao dịch!');
        }
    }

    public function pay($order)
    {
        try {
            $data = [
                'tokenKey' => $this->tokenKey,
                'orderCode' => $order->order_code,
                'customMerchantId' => $order->telephone,
                'amount' => $order->total_price,
                // 'amount' => 10000,
                'currency' => 'VND',
                'orderDescription' => 'Thanh toán đơn hàng ' . $order->order_code,
                'totalItem' => $order->total_quantity,
                'allowDomestic' => true,
                'checkoutType' => 3,
                'returnUrl' => route('client.alepay.return'),
                'cancelUrl' => route('client.alepay.cancel'),
                'buyerName' => $order->name,
                'buyerEmail' => $order->email,
                'buyerPhone' => $order->telephone,
                'buyerAddress' => $order->address ? $order->address : $order->store->address,
                'buyerCity' => $order->province_id ? $order->province->full_name : $order->store->province->full_name,
                'buyerCountry' => 'Vietnam'
            ];
            
            $data = $this->generateData($data);
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/request-payment', $data);
          
            $result = $response->json();

            LogAlepay::create([
                'order_id' => $order->id,
                'transaction_code' => $result["transactionCode"],
                'request' => json_encode($data, JSON_UNESCAPED_UNICODE),
                'response' => $response,
            ]);

            if (@$result["code"] != '000') {
                throw new \Exception($result["message"]);
                return redirect()->route('client.cart.index')->with('error', $result["message"]);
            }

            session()->put('alepay_transaction_code', $result["transactionCode"]);
            
            return redirect($result["checkoutUrl"]);
        } catch (\Exception $e) {
            throw new \Exception($e);
            Log::info([
                'title' => 'AlepayPayment',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'stack_trace' => $e->getTraceAsString(),
                'method' => __METHOD__
            ]);
            return redirect()->route('client.cart.index')->with('error', "Có lỗi. Vui lòng thử lại sau!");
        }
    }

    public function getBanks()
    {
        $data = [
            'tokenKey' => $this->tokenKey,
        ];
        
        $data = $this->generateData($data);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/get-list-banks', $data);

        return $response->json();
    }

    public function getInstallmentInfo($amount)
    {
        $data = [
            'tokenKey' => $this->tokenKey,
            'amount' => $amount,
            'currencyCode' => 'VND',
        ];
        
        $data = $this->generateData($data);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/get-installment-info', $data);

        return $response->json();
    }
}
