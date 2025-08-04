<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\GetflyService;
use App\Services\EcountService;
use App\Models\admin\Order\LogGetfly;
use App\Models\admin\Order\Order;

class OrderService
{
    protected $getflyService;
    protected $ecountService;
    public $getflyOrderStatus = [
        1 => 'Chờ duyệt',
        2 => 'Đã duyệt',
        3 => 'Đang xuất kho',
        4 => 'Đã hoàn thành',
        5 => 'Đã huỷ',
    ];

    public function __construct(GetflyService $getflyService, EcountService $ecountService)
    {
        $this->getflyService = $getflyService;
        $this->ecountService = $ecountService;
    }

    public function addOrder($order)
    {
        $getfly = $this->getflyService->addOrder($order);
        LogGetfly::create([
            'request' => json_encode($getfly['request'], JSON_UNESCAPED_UNICODE),
            'response' => json_encode($getfly['response'], JSON_UNESCAPED_UNICODE),
            'order_id' => $order->id,
            'status' => 1
        ]);

        // add more logic here

        return compact(
            'getfly',
        );
    }

    public function getUpdateOrder()
    {
        try {
            $alepayOrders = Order::where('type', 1)
                ->where('order_status', 1)
                ->where('created_at', '<', now()->subHours(3))
                ->whereHas('alepay_info')
                ->with('alepay_info')
                ->get();
            foreach ($alepayOrders as $alepayOrder) {
                if (!$alepayOrder->alepay_info->return && !$alepayOrder->alepay_info->cancel) {
                    $alepayOrder->update([
                        'order_status' => 3,
                    ]);
                }
            }

            $orders = Order::where('type', 1)
                ->where('order_status', 1)
                ->whereHas('getfly_info')
                ->with('getfly_info')
                ->get();

            $getflyOrderIds = $orders->pluck('getfly_info.response')->map(function ($item) {
                return json_decode($item, true)['id'];
            })->toArray();

            $getfly = $this->getflyService->getOrders($getflyOrderIds);

            foreach ($orders as $order) {
                foreach ($getfly['data'] as $item) {
                    if ($item['id'] == json_decode($order->getfly_info->response, true)['id']) {
                        $order->getfly_info->update([
                            'status' => $item['status'],
                        ]);
                        switch ($item['status']) {
                            case 4:
                                $order->update([
                                    'order_status' => 2,
                                    'payment_status' => 1,
                                ]);
                                break;
                            case 5:
                                $order->update([
                                    'order_status' => 3,
                                ]);
                                break;
                        }
                    }
                }
            } 

            // foreach ($orders as $order) {
            //     $info = json_decode($order->getfly_info->response, true);
            //     if (!@$info['errors'] && @$info['id']) {
            //         $getfly = $this->getflyService->getOrder($info['id']);
            //         $order->getfly_info->update([
            //             'status' => $getfly['data'][0]['status'],
            //         ]);
            //         switch ($getfly['data'][0]['status']) {
            //             case 4:
            //                 $order->update([
            //                     'order_status' => 2,
            //                     'payment_status' => 1,
            //                 ]);
            //                 break;
            //             case 5:
            //                 $order->update([
            //                     'order_status' => 3,
            //                 ]);
            //                 break;
            //         }
            //     }
            // }

            return response()->json([
                'status' => 'success',
                'message' => 'Update order success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
