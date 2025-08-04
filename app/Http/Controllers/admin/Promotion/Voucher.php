<?php

namespace App\Http\Controllers\admin\Promotion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Promotion\Voucher as VoucherModel;
use App\Models\admin\Product\ProductCategories as ProductCategoriesModel;

class Voucher extends Controller
{
    public function __construct()
    {
        $this->model = VoucherModel::class;
        $this->view = 'admin.promotion.voucher';
        $this->prefix = 'promotion_voucher';
    }

    public function create()
    {
        $categories = ProductCategoriesModel::where('published', 1)->orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($categories);
        parent::setData([
            'categories' => $list_tree
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $categories = ProductCategoriesModel::where('published', 1)->orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($categories);

        $data = VoucherModel::where('id', $id)->first();
        $data->products;

        // $data = DiscountCategoryModel::where('id', $id)->with('products')->first();

        // if (@$data->products) {
        //     foreach ($data->products as $product) {
        //         $product->price = $product->pivot->price;
        //         $product->percent = $product->pivot->percent;
        //         $product->sold = $product->pivot->sold;
        //     }
        // }

        parent::setData([
            'categories' => $list_tree,
            'data' => @$data,
        ]);

        return parent::edit($id);
    }

    public function save(Request $request)
    {
        $data = $request->all();
     
        $product = @$data['products']['id'];
        $data = $data['info'];

        if (!empty($product)) {
            $data['products'] = implode(',', $product);
        }

        if (!@$data['code']) {
            $data['code'] = $this->createUniqueVoucherCode();
        }

        $data['min_price'] = $this->remove_fomart_money($data['min_price'] ?: 0);
        $data['price'] = $this->remove_fomart_money($data['price']);
        $data['percent'] = $data['price'] != 0 ? 0 : $data['percent'];
        $data['alias'] = $this->generate_alias($data['name']);
        $data['quantity'] = $data['quantity'] ? $data['quantity'] : 0; 

        parent::setData($data);

        return parent::save($request);
    }

    private function generateVoucherCode($length)
    {
        return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', ceil($length / strlen($x)))), 1, $length);
    }

    private function createUniqueVoucherCode($length = 8)
    {
        $voucherCode = $this->generateVoucherCode($length);

        while ($this->voucherCodeExistsInDatabase($voucherCode)) {
            $voucherCode = $this->generateVoucherCode($length);
        }

        return $voucherCode;
    }

    private function voucherCodeExistsInDatabase($voucherCode, $idEdit = 0)
    {
        $exist = VoucherModel::where('code', $voucherCode)->where('id', '!=', $idEdit)->get();

        $existingVouchers = $exist->pluck('code')->toArray();

        return in_array($voucherCode, $existingVouchers);
    }
}
