<?php

namespace App\Http\Controllers\admin\Order;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Order\Order as OrderModel;
use App\Models\admin\Member\Member as MemberModel;
use App\Models\admin\Payoo\PayooIPN as PayooIPNModel;
use App\Services\Fast\FastService;
use Illuminate\Support\Facades\DB;

use stdClass;

class Order extends BaseController
{
    private $fast;
    public function __construct(FastService $fast)
    {
        $this->fast = $fast;
        $this->searchField = 'order_code,fast_id';
        
        // Định nghĩa custom filters
        $this->customFilters = [
            [
                'field' => 'type',
                'operator' => '='
            ],
            [
                'field' => 'payment_method',
                'operator' => '='
            ],
            [
                'field' => 'order_status',
                'operator' => '='
            ],
            [
                'field' => 'payment_status',
                'operator' => '='
            ],
            [
                'field' => 'total_price',
                'type' => 'number_range'
            ]
        ];
        
        parent::__construct(OrderModel::class, 'admin.order', 'order');
    }

    public function index()
    {
        $categories = [];
        
        // Định nghĩa các custom filters cho trang order
        $customFilters = [
            [
                'field' => 'type',
                'label' => 'Nguồn',
                'placeholder' => 'Lọc theo nguồn',
                'options' => [
                    ['value' => 'null', 'label' => 'Web'],
                    ['value' => 'fast', 'label' => 'Fast'],
                    ['value' => 'app', 'label' => 'App'],
                ]
            ],
            [
                'field' => 'payment_method',
                'label' => 'Phương thức thanh toán',
                'placeholder' => 'Lọc theo phương thức thanh toán',
                'options' => [
                    ['value' => 'cod', 'label' => 'COD'],
                    ['value' => 'payoo', 'label' => 'Payoo'],
                ]
            ]
        ];
        

        parent::setData([
            'categories' => $categories,
            'customFilters' => $customFilters
        ]);
        return parent::index();
    }

    public function edit($id)
    {
        $data = OrderModel::where('id', $id)
            ->with('items.product')
            ->with('member')
            ->first();

        $order = OrderModel::find($id);
        $order->new = 0;
        $order->save();
        $check_fast_exists = DB::table('log_fast')->where('order_code', $order->order_code)->exists();
        
        // Get log_fast request data
        $log_fast_data = DB::table('log_fast')->where('order_code', $order->order_code)->first();

        $payoo_info = new stdClass();
        if ($order->payment_method == 'payoo') {
            $payoo_info_raw = PayooIPNModel::where('order_code', $order->order_code)->first();
            if (!is_null($payoo_info_raw)) {
                $payoo_info_decoded = json_decode($payoo_info_raw->response);
                if (!is_null($payoo_info_decoded) && property_exists($payoo_info_decoded, 'ResponseData')) {
                    $payoo_info = json_decode($payoo_info_decoded->ResponseData);
                }
            }
        }

        $this->setData([
            'data' => $data,
            'payoo_info' => $payoo_info,
            'check_fast_exists' => $check_fast_exists,
            'log_fast_data' => $log_fast_data
        ]);

        return parent::edit($id);
    }

    public function send_fast() {
        $order_id = request()->input('order_id');
        $order = OrderModel::find($order_id);

        $payload_order = $this->fast->createPayloadOrder($order);
        $res = $this->fast->createOrder($payload_order);

        if($res) {
            return redirect()
            ->route('admin.order.index')
            ->with('success', 'Thành công');
        } else {
            return redirect()
                ->route('admin.order.index')
                ->with('error', 'Đơn sang fast không thành công');
        }

    }
}
