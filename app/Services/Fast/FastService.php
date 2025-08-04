<?php

namespace App\Services\Fast;

use Illuminate\Support\Facades\Http;
use App\Models\admin\Member\Member as MemberModel;
use App\Models\admin\Order\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\admin\Voucher\Voucher;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;
use App\Models\admin\Order\Order;
use App\Models\admin\Product\Product as ProductModel;
use Illuminate\Support\Facades\Session;

class FastService
{
    private $link = 'https://pos.dmcfashion.com:8080/';
    private $link2 = 'https://pos.dmcfashion.com:8081/';

    private $type = [
        'pointcard' => 'api/pointcard',
        'customer' => 'api/customers',
        'create_order' => 'api/order',
        'create_voucher' => 'api/coupons',

    ];
    private $paycode = [
        'cod' => '03',
        'payoo' => '06',
        'fast' => '05'
    ];

    private $fastInventoryWeb = [
        'K02',
        'K03',
        'A01',
        'A02',
        'A03',
        'A04',
        'B01',
        'B02',
        'B03',
        'B04',
        'B05',
        'B06',
        'B07'
    ];

    public function getLink()
    {
        return $this->link;
    }
    public function getLink2()
    {
        return $this->link2;
    }
    public function getType()
    {
        return $this->type;
    }

    public function getHttpClient()
    {
        Log::info('HTTP Client configured with Basic Auth', [
            'username' => 'fasthn', // Chỉ log username (đừng log password để bảo mật)
            // Bạn không nên log password vì lý do bảo mật
        ]);

        return Http::withBasicAuth('fasthn', 'fast$20#09@91');
    }


    public function apiStoreUpdate()
    {
        $request = request();
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return response()->json(['success' => 'false', 'msg' => 'Cập nhật không thành công'], 200);
        }

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                if (
                    !isset($item['store_code'], $item['code_product'], $item['quantity']) ||
                    !is_string($item['store_code']) ||
                    !is_string($item['code_product']) ||
                    !is_string($item['quantity'])
                ) {
                    DB::rollBack();
                    return response()->json(['success' => 'false', 'msg' => 'Dữ liệu không hợp lệ'], 200);
                }

                $string = $item['code_product'];
                $product_code = substr($string, 0, 17);

                if (strlen($string) == 17) {
                    $color = '';
                    $size = '';
                    $variant = '';
                } else {
                    $color = substr($string, 17, 2);
                    $sizeLength = strlen($string) - 19;
                    $size = substr($string, -$sizeLength);
                    $variant = $item['code_product'];
                };


                if (!isset($inventoryData[$string]['store'][$item['store_code']])) {
                    $inventoryData[$string]['store'][$item['store_code']] = [];
                }
                $inventoryData[$string]['store'][$item['store_code']] = $item['quantity'];
                $inventoryData[$string]['product_code'] = $product_code;
                $inventoryData[$string]['variant'] = $variant;
                $inventoryData[$string]['size'] = $size;
                $inventoryData[$string]['color'] = $color;

                $product = DB::table('products')->where('code', 'like', '%' . $product_code . '%')->where('published', 1)->first();
                $inventoryData[$string]['product'] = $product;
            }
            foreach ($inventoryData as $item) {
                $totalWeb = 0;
                foreach ($item['store'] as $key => $val) {
                    foreach ($this->fastInventoryWeb as $store) {
                        if ($key == $store) {
                            $totalWeb += $val;
                        }
                    }
                }
                if (strlen($item['variant']) >= 16) {
                    DB::table('products_fast')->upsert(
                        [
                            [
                                'store' => json_encode($item['store']),
                                'quantity' => $totalWeb,
                                'total_quantity' => array_sum($item['store']),
                                'code_prd' => $item['product_code'],
                                'variant' => $item['variant'],
                                'size_name' => $item['size'],
                                'color_name' => $item['color'],
                                'created_at' => now(),
                                'updated_at' => now(),
                                'product_id' => $item['product'] ? $item['product']->id : null
                            ]
                        ],
                        ['variant'],
                        ['quantity', 'total_quantity', 'store', 'size_name', 'color_name', 'updated_at', 'product_id']
                    );
                }
            }
            // Log::info('Upsert successful for variant: ' . $item['variant'], ['item' => $item]);
            DB::commit();
            return response()->json(['success' => true, 'msg' => "Cập nhật sản phẩm thành công."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => "Cập nhật sản phẩm thất bại."]);
        }
    }

    public function getItemInventory($codeProduct = '')
    {

        $rs = Http::post($this->link2 . 'APIGetInfo.asmx/CapNhatTonKhoByItem', [
            'ma_vt' => $codeProduct,
            'ma_kho' => '',
        ]);

        $data = json_decode($rs->body(), true);

        if (isset($data['d'])) {
            $jsonStrings = preg_split('/(?<=})\s*(?={)/', $data['d']);

            $mergedData = [
                "isSuccess" => "Cập nhật số lượng tồn thành công",
                "lstItem" => []
            ];

            foreach ($jsonStrings as $jsonString) {
                $decodedData = json_decode($jsonString, true);
                if (isset($decodedData['lstItem']) && is_array($decodedData['lstItem'])) {
                    $mergedData['lstItem'] = array_merge($mergedData['lstItem'], $decodedData['lstItem']);
                }
            }
            $data = $mergedData;
        }

        if (!is_array($data)) {
            return response()->json(['success' => 'false', 'msg' => 'Cập nhật không thành công'], 200);
        }

        // Log::info('Data from Fast: ', ['data' => $data]);

        $data = $data['lstItem'];

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                if (
                    !isset($item['store_code'], $item['code_product'], $item['quantity']) ||
                    !is_string($item['store_code']) ||
                    !is_string($item['code_product']) ||
                    !is_string($item['quantity'])
                ) {
                    DB::rollBack();
                    return response()->json(['success' => 'false', 'msg' => 'Dữ liệu không hợp lệ'], 200);
                }

                $string = $item['code_product'];
                $product_code = substr($string, 0, 17);

                if (strlen($string) == 17) {
                    $color = '';
                    $size = '';
                    $variant = '';
                } else {
                    $color = substr($string, 17, 2);
                    $sizeLength = strlen($string) - 19;
                    $size = substr($string, -$sizeLength);
                    $variant = $item['code_product'];
                };

                if (!isset($inventoryData[$string]['store'][$item['store_code']])) {
                    $inventoryData[$string]['store'][$item['store_code']] = [];
                }
                $inventoryData[$string]['store'][$item['store_code']] = $item['quantity'];
                $inventoryData[$string]['product_code'] = $product_code;
                $inventoryData[$string]['variant'] = $variant;
                $inventoryData[$string]['size'] = $size;
                $inventoryData[$string]['color'] = $color;
                $product = DB::table('products')->where('code', 'like', '%' . $product_code . '%')->where('published', 1)->first();
                $inventoryData[$string]['product'] = $product;
            }

            foreach ($inventoryData as $item) {
                $totalWeb = 0;
                foreach ($item['store'] as $key => $val) {
                    foreach ($this->fastInventoryWeb as $store) {
                        if ($key == $store) {
                            $totalWeb += $val;
                        }
                    }
                }
                if (strlen($item['variant']) >= 16) {
                    try {
                        DB::table('products_fast')->upsert(
                            [
                                [
                                    'store' => json_encode($item['store']),
                                    'quantity' => $totalWeb,
                                    'total_quantity' => array_sum($item['store']),
                                    'code_prd' => $item['product_code'],
                                    'variant' => $item['variant'],
                                    'size_name' => $item['size'],
                                    'color_name' => $item['color'],
                                    'update_fast' => 1,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                    'product_id' => $item['product'] ? $item['product']->id : null
                                ]
                            ],
                            ['variant'],
                            ['quantity', 'total_quantity', 'store', 'size_name', 'color_name', 'updated_at', 'product_id', 'update_fast']
                        );
                        // Log::info('Upsert successful for variant: ' . $item['variant'], ['item' => $item]);
                    } catch (\Exception $e) {
                        Log::error('Upsert failed for variant: ' . $item['variant'], ['item' => $item, 'error' => $e->getMessage()]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'msg' => "Cập nhật sản phẩm thành công."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => "Cập nhật sản phẩm thất bại."]);
        }
    }

    public function allItemsFast()
    {
        $rs = $this->Parse($this->link2 . 'APIGetInfo.asmx/CapNhatTonKhoAllItems');
        $firstRecord = DB::table('all_items')->first();

        if ($firstRecord) {
            // Cập nhật record đầu tiên
            DB::table('all_items')
                ->where('id', $firstRecord->id)
                ->update(['response' => $rs]);
        } else {
            // Chèn một record mới vì bảng đang trống
            DB::table('all_items')->insert([
                'response' => $rs,
            ]);
        }

        // $data = DB::table('all_items')->first();
        // $rs = $data->response;

        $jsonString = $rs;
        $jsonString = substr($jsonString, 6, -1);
        $jsonString = preg_replace_callback('/\{(.*?)\}/', function ($matches) {
            return str_replace("'", '"', $matches[0]);
        }, $jsonString);

        $jsonStrings = preg_split('/(?<=\})(?=\{)/', $jsonString);

        $jsonArrays = array_map(function ($json) {
            return json_decode(trim($json, "\""), true);
        }, $jsonStrings);

        $newArray = [];
        foreach ($jsonArrays as $item) {
            if ($item) {
                $newArray[] = $item;
            }
        }

        $jsonArrays = $newArray;

        $countUpdated = 0;

        foreach ($jsonArrays as $item) {
            foreach ($item['lstItem'] as $val) {
                $string = $val['code_product'];
                $product_code = substr($string, 0, 17);
                $product = DB::table('products')->where('code', 'like', '%' . $product_code . '%')->first();
                $color = substr($string, 17, 2);
                $sizeLength = strlen($string) - 19;
                $size = substr($string, -$sizeLength);
                $now = now();
                if ($product) {
                    DB::table('products_fast')->upsert([
                        [
                            'product_id' => $product->id,
                            'code_prd' => $product_code,
                            'variant' => $val['code_product'],
                            'store' => $val['store_code'],
                            'quantity' => $val['quantity'],
                            'size_name' => $size,
                            'color_name' => $color,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    ], ['variant'], ['code_prd', 'product_id', 'quantity', 'size_name', 'color_name', 'store', 'updated_at']);

                    $countUpdated++;
                } else {
                    DB::table('products_fast')->upsert([
                        [
                            'product_id' => null,
                            'code_prd' => $product_code,
                            'variant' => $val['code_product'],
                            'store' => $val['store_code'],
                            'quantity' => $val['quantity'],
                            'size_name' => $size,
                            'color_name' => $color,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    ], ['variant'], ['code_prd', 'product_id', 'quantity', 'size_name', 'color_name', 'store', 'updated_at']);
                }
            }
        }

        return response()->json(['countUpdated' => $countUpdated]);
    }

    public function Parse($url)
    {
        $fileContents = file_get_contents($url);
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);

        return $json;
    }

    public function createMember($member)
    {
        if ($member->dob) {
            $member->dob = date('Y-m-d', strtotime(str_replace('/', '-', $member->dob)));
        }

        $data = [
            'phone' => $member->phone,
            'name' => $member->name,
            'birthday' => $member->dob ?: "NULL",
            'address' => '',
            'job' => '',
            'idcustomer' => $member->id,
        ];

        $response = $this->getHttpClient()->post($this->link . $this->type['customer'], $data);

        if ($response->json()['isSuccess'] == 1) {
            $member->ma_kh = $response->json()['ma_kh'];
            $member->save();
            return response()->json(['success' => 1, 'message' => 'Thành công'], 200);
        }

        return response()->json(['success' => 0, 'message' => 'Có lỗi khi tạo mới khách hàng'], 400);
    }

    // public function createPayloadOrder($order)
    // {
    //     if ($order->member_id) {
    //         $member = MemberModel::where('id', $order->member_id)->first();
    //     }
    //     $province = DB::table('provinces')->where('code', '=', $order->province_id)->first();
    //     $district = DB::table('districts')->where('code', '=', $order->district_id)->first();
    //     $ward = DB::table('wards')->where('code', '=', $order->ward_id)->first();
    //     $order_items = OrderItem::where('order_id', $order->id)->get();

    //     foreach ($order_items as $item) {
    //         $item->price = intval($item->price);

    //         $data_prod = DB::table('products')->where('id', $item->product_id)->first();
    //         $array = json_decode($item->options, true);
    //         $sizeValue =  strtoupper($array['size']['value']);
    //         $fcolor = '';
    //         if (!isset($array['color']['fcolor']) || !$array['color']['fcolor']) {
    //             $data_color = DB::table('colors')->where('code', $array['color']['value'])->first();
    //             $fcolor = @$data_color->alias;
    //         }
    //         $fcolorValue = isset($array['color']['fcolor']) && strtoupper($array['color']['fcolor']) ? strtoupper($array['color']['fcolor']) : $fcolor;
    //         //$data_prod->price giá gốc
    //         //$item->price giá sau khuyến mãi


    //         for ($i = 0; $i < $item->quantity; $i++) {
    //             $arrProducts[] = array(
    //                 "ItemCode" => @$data_prod->code . $fcolorValue . $sizeValue,
    //                 "Quantity" => 1,
    //                 "Price" => $data_prod->price,
    //                 "Discount" => (@$data_prod->price - $item->price),
    //                 "Amount" => $item->price
    //             );
    //         }
    //         // $arrProducts[] = array(
    //         //     "ItemCode" => @$data_prod->code . $fcolorValue . $sizeValue,
    //         //     "Quantity" => @$item->quantity,
    //         //     "Price" => $data_prod->price,
    //         //     "Discount" => (@$data_prod->price - $item->price)*$item->quantity,
    //         //     "Amount" => $item->price * $item->quantity
    //         // );
    //     }
    //     $paycode = '';
    //     if ($order->payment_method == 'cod') {
    //         $paycode = $this->paycode['cod'];
    //     }
    //     if ($order->payment_method == 'payoo') {
    //         $paycode = $this->paycode['payoo'];
    //     }


    //     if ($order->total_shipping)
    //         $arrProducts[] = array(
    //             "ItemCode" => 'PHISHIP30K',
    //             "Quantity" => 1,
    //             "Price" => 30000,
    //             "Discount" => 0,
    //             "Amount" => 30000
    //         );

    //     $arrOrderPara = array(
    //         "VoucherCode" => $order->order_code,
    //         "CouponCode" => $order->voucher_code ? $order->voucher_code : '',
    //         "VoucherDate" => date('Y-m-d', strtotime($order->created_at)),
    //         "CustomerCode" => $member->phone . ' - ' . $member->name,
    //         "Note" => '',
    //         "dc_chi_tiet" => $order->address,
    //         "tinh_thanh" => $province->name ? $province->name : '',
    //         "quan_huyen" => $district->name ? $district->name : '',
    //         "phuong_xa" => $ward->name ? $ward->name : '',
    //         "can_nang" => '',
    //         "ma_dv" => '',
    //         "ma_dvgt" => '',
    //         "noi_dung_hh" => $order->order_code,
    //         "tien_thu_ho" => '',
    //         "gia" => '0',
    //         "SalesEmployee" => '',
    //         "ghi_chu" => $order->note ? $order->note : '',
    //         "ma_httt" => '',
    //         "ma_loai_hh" => '7',
    //         "detail" => $arrProducts,
    //         "paydetail" => array(
    //             array(
    //                 "PayCode" => $paycode,
    //                 "PaymentAmount" => $order->total_price
    //             ),
    //         )
    //     );


    //     return $arrOrderPara;
    // }

    public function createPayloadOrder($order)
    {
        if ($order->member_id) {
            $member = MemberModel::where('id', $order->member_id)->first();
        }
        $province = DB::table('provinces')->where('code', '=', $order->province_id)->first();
        $district = DB::table('districts')->where('code', '=', $order->district_id)->first();
        $ward = DB::table('wards')->where('code', '=', $order->ward_id)->first();
        $order_items = OrderItem::where('order_id', $order->id)->get();

        if ($order->voucher_code) {
            $voucher = Voucher::where('code', $order->voucher_code)->first();
            if ($voucher) {
                $voucher_discount = $voucher->discount;
            }
        }

        foreach ($order_items as $item) {
            $item->price = intval($item->price);

            $data_prod = DB::table('products')->where('id', $item->product_id)->first();
            $array = json_decode($item->options, true);
            $sizeValue =  strtoupper($array['size']['value']);
            $fcolor = '';

            if (!isset($array['color']['fcolor']) || !$array['color']['fcolor']) {
                $data_color = DB::table('colors')->where('code', $array['color']['value'])->first();
                $fcolor = @$data_color->alias;
            }

            if (!$fcolor) {
                $fcolor = strtoupper($array['color']['value']);
            }

            $fcolorValue = isset($array['color']['fcolor']) && strtoupper($array['color']['fcolor']) ? strtoupper($array['color']['fcolor']) : $fcolor;

            //$data_prod->price giá gốc
            //$item->price giá sau khuyến mãi

            for ($i = 0; $i < $item->quantity; $i++) {
                $arrProducts[] = array(
                    "ItemCode" => @$data_prod->code . $fcolorValue . $sizeValue,
                    "Quantity" => 1,
                    "Price" => $data_prod->price,
                    "Discount" => (@$data_prod->price - $item->price),
                    "Amount" => $item->price
                );
            }
        }
        $paycode = '';
        if ($order->payment_method == 'cod') {
            $paycode = $this->paycode['cod'];
        }
        if ($order->payment_method == 'payoo') {
            $paycode = $this->paycode['payoo'];
        }

        $numberOfProducts = count($arrProducts);
        if ($numberOfProducts > 0 && isset($voucher_discount)) {
            $equalDiscount = intdiv($voucher_discount, $numberOfProducts);
            $remainder = $voucher_discount % $numberOfProducts;

            foreach ($arrProducts as $index => $product) {
                $productDiscount = $equalDiscount;
                if ($index < $remainder) {
                    $productDiscount++;
                }
                $arrProducts[$index]['Discount'] = $productDiscount;
                $arrProducts[$index]['Amount'] = ($product['Price'] * $product['Quantity']) - $productDiscount;
            }
        }

        if ($order->total_shipping)
            $arrProducts[] = array(
                "ItemCode" => 'PHISHIP30K',
                "Quantity" => 1,
                "Price" => 30000,
                "Discount" => 0,
                "Amount" => 30000
            );

        $arrOrderPara = array(
            "VoucherCode" => @$order->order_code,
            "CouponCode" => @$order->voucher_code ? @$order->voucher_code : '',
            "VoucherDate" => date('Y-m-d', strtotime(@$order->created_at)),
            "CustomerCode" => @$member->phone . ' - ' . @$member->name,
            "Note" => @$order->node ? @$order->node : '',
            "dc_chi_tiet" => @$order->address,
            "tinh_thanh" => @$province->name ? @$province->name : '',
            "quan_huyen" => @$district->name ? @$district->name : '',
            "phuong_xa" => @$ward->name ? @$ward->name : '',
            "can_nang" => '',
            "ma_dv" => '',
            "ma_dvgt" => '',
            "noi_dung_hh" => @$order->order_code,
            "tien_thu_ho" => '',
            "gia" => '0',
            "SalesEmployee" => '',
            "ghi_chu" => @$order->note ? @$order->note : '',
            "ma_httt" => '',
            "ma_loai_hh" => '7',
            "detail" => @$arrProducts,
            "paydetail" => array(
                array(
                    "PayCode" => @$paycode,
                    "PaymentAmount" => @$order->total_price
                ),
            )
        );


        return $arrOrderPara;
    }
    public function createPayloadOrderEcommerce($order, $arrProducts, $source, $total_amount = 0)
    {
        if ($order->member_id) {
            $member = MemberModel::where('id', '=', $order->member_id)->first();
        }
        $province = DB::table('provinces')->where('code', '=', $order->province_id)->first();
        $district = DB::table('districts')->where('code', '=', $order->district_id)->first();
        $ward = DB::table('wards')->where('code', '=', $order->ward_id)->first();
        $paycode = $this->paycode['fast'];

        $dept = $source;
        $source_code = $source == 'K16' ? 'TIK' : $source;

        $arrOrderPara = array(
            "VoucherCode" => $order->order_code,
            "CouponCode" => $order->voucher_code ? $order->voucher_code : '',
            "VoucherDate" => date('Y-m-d', strtotime($order->created_at)),
            "CustomerCode" => ($member ? $member->phone . ' - ' . $member->name : ''),
            "Note" => '',
            "dc_chi_tiet" => $order->address,
            "tinh_thanh" => $province->name ? $province->name : '',
            "quan_huyen" => $district->name ? $district->name : '',
            "phuong_xa" => $ward->name ? $ward->name : '',
            "can_nang" => '',
            "ma_dv" => '',
            "ma_dvgt" => '',
            "noi_dung_hh" => $order->order_code,
            "tien_thu_ho" => '',
            "gia" => '0',
            "SalesEmployee" => $member->sales_employee ?? '',
            "ghi_chu" => $order->order_code,
            "ma_httt" => '',
            "ma_loai_hh" => '7',
            "DeptCode" => $dept,
            "SourceCode" => $source_code,
            "detail" => $arrProducts,
            "paydetail" => array(
                array(
                    "PayCode" => $paycode,
                    "PaymentAmount" => $total_amount
                ),
            )
        );
        return $arrOrderPara;
    }



    public function createOrder($payloadOrder)
    {
        $response = $this->getHttpClient()->post($this->link . $this->type['create_order'], $payloadOrder);
        Log::info('Response from create order API', [
            'response' => $response->getBody()->getContents(),
        ]);
        DB::table('log_fast')->insert([
            'request' => json_encode($payloadOrder),
            'response' => json_encode($response->json()),
            'created_at' => now(),
            'order_code' => $payloadOrder['VoucherCode'],
            'success' => $response->json()['isSuccesss'] ? $response->json()['isSuccesss'] : 0,
            'url' => $this->link . $this->type['create_order']
        ]);
        if ($response->successful() && $response->json()['isSuccesss'] == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public function updateAllInventory()
    {
        $today = Carbon::today();

        $products_fast = DB::table('products_fast')
            ->where('update_fast', 0)
            ->orWhereNull('update_fast')
            ->limit(100)
            ->get();

        if ($products_fast->isEmpty() || $products_fast->first()->updated_at < $today) {
            DB::table('products_fast')->update(['update_fast' => 0]);
        }

        foreach ($products_fast as $product) {
            $this->getItemInventory($product->code_prd);
        }

        return response()->json(['message' => 'Cập nhật thành công'], 200);
    }

    public function createVoucher()
    {
        $request = request();
        $code = $request->input('code', '');
        $discount = $request->input('discount', '');
        if (!$code || !$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher code and discount are required'
            ], 400);
        }
        $year = now()->year;
        $tableName = 'vouchers_' . $year;
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('code');
                $table->string('name')->nullable();
                $table->string('type')->nullable();
                $table->double('discount', 8, 0)->nullable();
                $table->date('date_start')->nullable();
                $table->date('date_expiration')->nullable();
                $table->string('department')->nullable();
                $table->string('department_group1')->nullable();
                $table->string('department_group2')->nullable();
                $table->string('department_group3')->nullable();
                $table->string('customer')->nullable();
                $table->string('customer_group1')->nullable();
                $table->string('customer_group2')->nullable();
                $table->string('customer_group3')->nullable();
                $table->double('bill_from', 11, 0)->nullable();
                $table->double('bill_to', 11, 0)->nullable();
                $table->integer('quantity_from')->nullable();
                $table->integer('quantity_to')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamp('created_time')->useCurrent();
                $table->boolean('ad_ckc_yn')->default(0);
                $table->boolean('ad_ckvip_yn')->default(0);
                $table->boolean('ad_cktang_yn')->default(0);
                $table->boolean('ad_ckcombo_yn')->default(0);
                $table->integer('ordering')->nullable();
                $table->integer('used')->nullable();
                $table->string('minuspoint')->nullable();
                $table->timestamps();
            });
        }

        $voucher_id = null;

        DB::transaction(function () use ($request, $code, $discount, $tableName, &$voucher_id) {
            $voucher = DB::table($tableName)->where('code', $code)->first();
            Log::info('Request Data:', $request->all());

            $data = $request->only([
                'code',
                'name',
                'type',
                'discount',
                'date_start',
                'date_expiration',
                'department',
                'department_group1',
                'department_group2',
                'department_group3',
                'customer',
                'customer_group1',
                'customer_group2',
                'customer_group3',
                'bill_from',
                'bill_to',
                'quantity_from',
                'quantity_to',
                'status'
            ]);
            $data['created_time'] = now();
            $data['created_at'] = now();
            $data['updated_at'] = now();

            $additionalFields = ['ad_ckc_yn', 'ad_ckvip_yn', 'ad_cktang_yn', 'ad_ckcombo_yn'];
            foreach ($additionalFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = $request->input($field, 0);
                }
            }

            if ($voucher && @$data['discount'] > 0) {
                DB::table($tableName)->where('code', $code)->update($data);
                $voucher_id = $voucher->id;
            } else {
                $voucher_id = DB::table($tableName)->insertGetId($data);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật voucher thành công',
            'voucher_id' => $voucher_id,
            'voucher_code' => $code
        ], 200);
    }

    // ...existing code...

    function payloadVoucherFast($voucher)
    {
        $year = now()->year;
        $currentTimestamp = time();

        $data = array(
            'cpid' => $currentTimestamp,
            'cpcode' => $voucher->code,
            'cpname' => $voucher->name,
            'cptype' => intval($voucher->type ?? 5),
            'cpvalue' => strval($voucher->discount),
            'cpdiscountrate' => intval($voucher->type) == 3 || intval($voucher->type) == 4 ? strval($voucher->discount) : null,
            'minuspoint' => intval($voucher->minuspoint ?? 0),
            'timeofuse' => 1,
            'datefrom' => $voucher->date_start ?? date('Y-m-d'),
            'dateto' => $voucher->date_expiration ?? date('Y-m-d', strtotime("+1 day")),
            'customer' => $voucher->customer,
            'valuefrom' => intval($voucher->bill_from ?? 0),
            'ad_ckc_yn' => intval($voucher->ad_ckc_yn ?? 0),
            'ad_ckvip_yn' => intval($voucher->ad_ckvip_yn ?? 0),
            'ad_cktang_yn' => intval($voucher->ad_cktang_yn ?? 0),
            'ad_ckcombo_yn' => intval($voucher->ad_ckcombo_yn ?? 0)
        );
        return $data;
    }

    public function createVoucherCode($prefix = 'DMC')
    {
        do {
            $code = $prefix . strtoupper(bin2hex(random_bytes(5)));
        } while ($this->voucherCodeExists($code));

        return $code;
    }

    private function voucherCodeExists($code)
    {
        $year = now()->year;
        $tableName = 'vouchers_' . $year;
        return DB::table($tableName)->where('code', $code)->exists();
    }

    public function updateCustomer()
    {
        $request = request();
        $email = $request->input('email', '');
        $phone = $request->input('phone', '');
        $name = $request->input('name', '');
        $address = $request->input('address', '');
        $birthday = $request->input('birthday', '');
        $job = $request->input('job', '');

        Log::info('Request create customer:', $request->all());
        if ($request->has('order_id')) {
            $order = Order::where('order_code', $request->input('order_id'))
                ->orWhere('fast_id', $request->input('order_id'))
                ->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Order not found',
                ], 404);
            }

            Log::info('Update order request in customer: ', $request->all());
            $order->shipping_code = $request->input('shipping_code', '');
            $order->order_status_fast = $request->input('order_status', '');
            $order->shipping_partner = $request->input('shipping_partner', '');
            $order->save();
        }
        if (empty($phone) || empty($name)) {
            return response()->json([
                'success' => false,
                'message' => 'Phone and name are required'
            ], 400);
        }
        $tablename = 'members';
        $member = DB::table($tablename)->where('phone', $phone)->first();
        $ma_kh = 0;

        if ($member) {
            DB::table($tablename)->where('id', $member->id)->update([
                'name' => $name,
                'address' => $address,
                'dob' => $birthday,
                'job' => $job,
                'email' => $email
            ]);
            $ma_kh = $member->id;
        } else {
            $ma_kh = DB::table($tablename)->insertGetId([
                'phone' => $phone,
                'name' => $name,
                'address' => $address,
                'dob' => $birthday,
                'job' => $job,
                'email' => $email,
                'gave_gift' => 0,
                'created_at' => now()
            ]);
        }

        if ($ma_kh) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật dữ liệu thành công',
                'ma_kh' => $ma_kh
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to update or create member'
        ], 500);
    }

    public function create_order()
    {
        $request = request();
        $all = $request->all();
        $data = $request->all();
        Log::info('request create order', ['data' => $all]);

        $items = $data['items'] ?? [];
        if (empty($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa có sản phẩm trong giỏ hàng'
            ]);
        }

        if (!@$data['fast_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Mã đơn hàng không hợp lệ'
            ]);
        };

        // Kiểm tra xem đơn hàng đã tồn tại chưa
        $existingOrder = Order::where('fast_id', $data['fast_id'])->first();
        if ($existingOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng đã tồn tại'
            ]);
        }

        // Tính tổng giá trị đơn hàng
        $totalBeforeDiscount = 0;
        $totalAfterDiscount = 0;
        foreach ($items as $item) {
            $totalBeforeDiscount += $item['price_old'] * $item['quantity'];
            $totalAfterDiscount += $item['price'] * $item['quantity'];
        }

        // Tạo đơn hàng
        DB::beginTransaction();
        try {
            $order = Order::create([
                'fast_id' => @$data['fast_id'],
                'fast_items' => json_encode($items),
                'user_fast' => @$data['user_id'],
                'name' => @$data['sender_name'],
                'phone' => @$data['sender_telephone'],
                'email' => @$data['sender_email'],
                'address' => @$data['sender_address'],
                'payment_method' => 'cod', // Cố định payment_method
                'payment_status' => 1,
                'note' => @$data['sender_comments'],
                'total_shipping' => @$data['fee_ship'],
                'total' => @$totalBeforeDiscount,
                'total_price' => @$totalAfterDiscount,
                'created_at' => Carbon::now(),
                'order_status' => 1,
                'type' => 'fast',
            ]);

            // Lưu chi tiết đơn hàng
            foreach ($items as $item) {
                $code = substr($item['fast_code'], 0, 17);
                $product = ProductModel::where('code', $code)->first();

                $data2 = [
                    'name' => @$item['name'],
                    'order_id' => @$order->id,
                    'price_old' => @$item['price_old'],
                    'price' => @$item['price'],
                    'product_id' => '',
                    'fast_id' => @$data['fast_id'],
                    'quantity' => @$item['quantity'],
                    'created_at' => Carbon::now(),
                    'options' => json_encode([
                        'size' => [
                            'label' => @$item['size'],
                            'value' => @$item['size']
                        ],
                        'color' => [
                            'label' => @$item['color'],
                            'value' => @$item['color']
                        ]
                    ])
                ];
                if ($product) {
                    $data2['product_id'] = $product->id;
                }
                OrderItem::create($data2);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Xử lý lỗi nếu cần
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Tạo đơn hàng thành công',
            'order_id' => $order->id,
        ]);
    }

    public function sync_members_daily()
    {
        $members = MemberModel::where('updated_at', '<', Carbon::now())->orWhereNull('updated_at')->limit(30)->get();

        foreach ($members as $member) {
            $data = array(
                'phone' => $member->phone
            );
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->link . $this->type['pointcard']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_USERPWD, 'fasthn:fast$20#09@91');

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception('cURL Error: ' . curl_error($ch));
            }

            curl_close($ch);
            $res = json_decode($response, true);

            DB::beginTransaction();
            try {
                $member->update([
                    'ma_the' => $res['ma_the'],
                    'hang_the' => $res['hang_the'] ?? null,
                    'diem_tich_luy' => $res['diem_tich_luy'] ?? null,
                    'diem_thuong' => $res['diem_thuong'] ?? null,
                    'coupon_len_hang' => $res['coupon_len_hang'] ?? '',
                    'diem_len_hang' => $res['diem_len_hang'] ?? null,
                    'ty_le_len_hang' => $res['ty_le_len_hang'] ?? 0
                ]);
                DB::commit();
                Log::info('Member updated successfully', ['member' => $member]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error when sync member', ['error' => $e->getMessage()]);
            }
        }
        return response()->json(['success' => true, 'message' => 'Đồng bộ khách hàng thành công']);
    }

    public function syncMemberFromFast($member)
    {
        try {
            $data = array(
                'phone' => $member->phone
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->link . $this->type['pointcard']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_USERPWD, 'fasthn:fast$20#09@91');

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                curl_close($ch);
                Log::error('cURL Error when syncing member: ' . curl_error($ch), ['member_id' => $member->id]);
                return false;
            }

            curl_close($ch);
            $res = json_decode($response, true);

            if ($res && is_array($res)) {
                DB::beginTransaction();
                try {
                    $member->update([
                        'ma_the' => $res['ma_the'] ?? $member->ma_the,
                        'ma_kh' => $res['ma_the'] ?? $member->ma_the,
                        'hang_the' => $res['hang_the'] ?? $member->hang_the,
                        'diem_tich_luy' => $res['diem_tich_luy'] ?? $member->diem_tich_luy,
                        'diem_thuong' => $res['diem_thuong'] ?? $member->diem_thuong,
                        'coupon_len_hang' => $res['coupon_len_hang'] ?? $member->coupon_len_hang,
                        'diem_len_hang' => $res['diem_len_hang'] ?? $member->diem_len_hang,
                        'ty_le_len_hang' => $res['ty_le_len_hang'] ?? $member->ty_le_len_hang,
                        'updated_at' => now()
                    ]);
                    DB::commit();
                    Log::info('Member synced successfully from Fast', [
                        'member_id' => $member->id,
                        'phone' => $member->phone,
                        'ma_the' => $res['ma_the'] ?? null
                    ]);
                    return true;
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error when updating member from Fast sync', [
                        'member_id' => $member->id,
                        'error' => $e->getMessage()
                    ]);
                    return false;
                }
            }

            Log::warning('No valid data received from Fast API', [
                'member_id' => $member->id,
                'phone' => $member->phone,
                'response' => $response
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception when syncing member from Fast', [
                'member_id' => $member->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }


    public function orderUpdate()
    {
        $request = request();
        $data = $request->all();
        $order_code = $request->input('codeOrder');
        $status = $request->input('statusOrder');
        Log::info('Order update request: ', $data);

        if (!$order_code) {
            return response()->json(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ']);
        }
        if (!$status) {
            return response()->json(['success' => false, 'message' => 'Trạng thái đơn hàng không hợp lệ']);
        }

        $order = Order::where('order_code', $order_code)->orWhere('fast_id', $order_code)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        }
        DB::beginTransaction();
        try {
            $order->update([
                'order_status' => $status
            ]);
            Log::info('Order updated: ', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'new_status' => $status,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error when update order', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Cập nhật đơn hàng thất bại']);
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật đơn hàng thành công']);
    }
    public function change_voucher_status()
    {
        $request = request();
        $vouchers = $request->all();

        Log::info('Change voucher status request', ['request' => $request->all()]);

        DB::beginTransaction();

        try {
            foreach ($vouchers as $item) {
                $year = now()->year;
                $tableName = 'vouchers_' . $year;

                if (!Schema::hasTable($tableName)) {
                    Log::warning('Voucher table not found', ['table' => $tableName]);
                    continue;
                }

                $voucher = DB::table($tableName)->where('code', $item['code'])->first();

                if ($voucher) {
                    DB::table($tableName)
                        ->where('code', $item['code'])
                        ->update([
                            'status' => $item['status'],
                            'updated_at' => now()
                        ]);
                    Log::info('Voucher status updated successfully', [
                        'voucher_code' => $item['code'],
                        'status' => $item['status'],
                        'table' => $tableName
                    ]);
                } else {
                    $found = false;
                    $years = [now()->year - 1, now()->year + 1];

                    foreach ($years as $checkYear) {
                        $checkTableName = 'vouchers_' . $checkYear;
                        if (Schema::hasTable($checkTableName)) {
                            $voucherInOtherYear = DB::table($checkTableName)->where('code', $item['code'])->first();
                            if ($voucherInOtherYear) {
                                DB::table($checkTableName)
                                    ->where('code', $item['code'])
                                    ->update([
                                        'status' => $item['status'],
                                        'updated_at' => now()
                                    ]);
                                Log::info('Voucher status updated successfully in different year', [
                                    'voucher_code' => $item['code'],
                                    'status' => $item['status'],
                                    'table' => $checkTableName
                                ]);
                                $found = true;
                                break;
                            }
                        }
                    }

                    if (!$found) {
                        Log::warning('Voucher not found in any table', ['voucher_code' => $item['code']]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating voucher status', ['error' => $e->getMessage()]);
            throw $e;
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái voucher thành công']);
    }

    public function updateMemberLevel()
    {
        $request = request();
        $data = $request->all();

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ!'
            ], 400);
        }

        $results = [];

        DB::beginTransaction();
        Log::info('updateMemberLevel request: ', $data);
        try {
            foreach ($data as $item) {
                if (empty($item['so_dien_thoai'])) {
                    continue;
                }

                $soDienThoai = $item['so_dien_thoai'];
                $hangThe = $item['hang_the'];
                $diemConLai = $item['diem_con_lai'];
                $maCouponTang = $item['ma_coupon_tang'];
                $soDiemTang = $item['so_diem_tang'];
                $quaTangKhac = $item['qua_tang_khac'];

                $member = MemberModel::where('phone', $soDienThoai)->first();

                if (!$member) {
                    $results[] = [
                        'so_dien_thoai' => $soDienThoai,
                        'status' => 'error',
                        'message' => 'Thành viên không tồn tại'
                    ];
                    return response()->json([
                        'success' => false,
                        'message' => 'Thành viên không tồn tại',
                    ]);
                }

                $member->update([
                    'hang_the' => $hangThe,
                    'diem_con_lai' => $diemConLai,
                    'ma_coupon_tang' => $maCouponTang,
                    'so_diem_tang' => $soDiemTang,
                    'qua_tang_khac' => $quaTangKhac,
                ]);
                Log::info('updateMemberLevel updated successfully', [
                    'hang_the' => $hangThe,
                    'diem_con_lai' => $diemConLai,
                    'ma_coupon_tang' => $maCouponTang,
                    'so_diem_tang' => $soDiemTang,
                    'qua_tang_khac' => $quaTangKhac,
                ]);

                // Thêm vào kết quả
                $results[] = [
                    'so_dien_thoai' => $soDienThoai,
                    'status' => 'success',
                    'ma_kh' => $member->ma_kh
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật dữ liệu thành công!',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi cập nhật dữ liệu!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update_order()
    {
        $request = request();
        if (!$request->has('order_id') || !$request->has('shipping_code') || !$request->has('order_status') || !$request->has('shipping_partner')) {
            return response()->json([
                'message' => 'Thiếu trường yêu cầu',
            ], 400);
        }

        $order = Order::where('order_code', $request->input('order_id'))
            ->orWhere('fast_id', $request->input('order_id'))
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Không tìm thấy đơn hàng',
            ], 404);
        }

        Log::info('Update order request: ', $request->all());
        $order->shipping_code = $request->input('shipping_code');
        $order->order_status_fast = $request->input('order_status');
        $order->shipping_partner = $request->input('shipping_partner');
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật dữ liệu đơn hàng thành công',
            'order' => $order,
        ], 200);
    }

    public function sync_member_detail_fast($phone)
    {
        $data = array(
            'phone' => $phone
        );
        $member = MemberModel::where('phone', $phone)->first();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->link . $this->type['pointcard']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_USERPWD, 'fasthn:fast$20#09@91');

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);
        $res = json_decode($response, true);

        DB::beginTransaction();
        try {
            $member->update([
                'ma_the' => $res['ma_the'],
                'hang_the' => $res['hang_the'] ?? null,
                'diem_tich_luy' => $res['diem_tich_luy'] ?? null,
                'diem_thuong' => $res['diem_thuong'] ?? null,
                'coupon_len_hang' => $res['coupon_len_hang'] ?? '',
                'diem_len_hang' => $res['diem_len_hang'] ?? null,
                'ty_le_len_hang' => $res['ty_le_len_hang'] ?? 0
            ]);
            DB::commit();
            Log::info('Member updated successfully', ['member' => $member]);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error when sync member', ['error' => $e->getMessage()]);
            return false;
        }
    }


    public function removeVoucher()
    {
        $request = request();
        $codes = $request->all(); // Nhận mảng các mã voucher cần xóa

        if (empty($codes)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có mã voucher nào được cung cấp'
            ], 400);
        }

        $year = now()->year;
        $tableName = 'vouchers_' . $year;

        if (!Schema::hasTable($tableName)) {
            return response()->json([
                'success' => false,
                'message' => 'Bảng voucher không tồn tại'
            ], 404);
        }

        $arr_code = [];

        DB::beginTransaction();
        try {
            foreach ($codes as $code) {
                $voucher = DB::table($tableName)->where('code', $code)->first();

                if ($voucher) {
                    $success = DB::table($tableName)
                        ->where('code', $code)
                        ->update([
                            'status' => 4, // Status 4 = removed/deleted
                            'updated_at' => now()
                        ]);

                    $arr_code[] = [
                        'success' => $success > 0,
                        'code' => $code,
                        'message' => $success > 0 ? 'Xóa thành công' : 'Xóa thất bại'
                    ];

                    Log::info('Voucher removed successfully', [
                        'voucher_code' => $code,
                        'table' => $tableName
                    ]);
                } else {
                    // Kiểm tra trong các năm khác nếu không tìm thấy
                    $found = false;
                    $years = [now()->year - 1, now()->year + 1];

                    foreach ($years as $checkYear) {
                        $checkTableName = 'vouchers_' . $checkYear;
                        if (Schema::hasTable($checkTableName)) {
                            $voucherInOtherYear = DB::table($checkTableName)->where('code', $code)->first();
                            if ($voucherInOtherYear) {
                                $success = DB::table($checkTableName)
                                    ->where('code', $code)
                                    ->update([
                                        'status' => 4,
                                        'updated_at' => now()
                                    ]);

                                $arr_code[] = [
                                    'success' => $success > 0,
                                    'code' => $code,
                                    'message' => $success > 0 ? 'Xóa thành công' : 'Xóa thất bại'
                                ];

                                Log::info('Voucher removed successfully from different year', [
                                    'voucher_code' => $code,
                                    'table' => $checkTableName
                                ]);
                                $found = true;
                                break;
                            }
                        }
                    }

                    if (!$found) {
                        $arr_code[] = [
                            'success' => false,
                            'code' => $code,
                            'message' => 'Voucher không tồn tại'
                        ];

                        Log::warning('Voucher not found for removal', ['voucher_code' => $code]);
                    }
                }
            }

            DB::commit();

            $successCount = count(array_filter($arr_code, function ($item) {
                return $item['success'];
            }));

            return response()->json([
                'success' => $successCount > 0,
                'message' => $successCount > 0 ? 'Xóa voucher thành công' : 'Không có voucher nào được xóa',
                'data' => $arr_code,
                'total_processed' => count($arr_code),
                'total_success' => $successCount
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing vouchers', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa voucher',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
