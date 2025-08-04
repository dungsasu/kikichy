<?php

namespace App\Http\Controllers\admin\Promotion;

use App\Http\Controllers\client\Promotion\Discount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Promotion\DiscountCategory as DiscountCategoryModel;
use App\Models\admin\Product\ProductCategories as ProductCategoriesModel;
use App\Models\admin\Promotion\Discount as DiscountModel;
use Carbon\Carbon;

class DiscountCategory extends Controller
{
    public function __construct()
    {
        $this->model = DiscountCategoryModel::class;
        $this->view = 'admin.promotion.discount';
        $this->prefix = 'promotion_discount';
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

        $data = DiscountCategoryModel::where('id', $id)->with('products')->first();

        if (@$data->products) {
            foreach ($data->products as $product) {
                $product->price = $product->pivot->price;
                $product->percent = $product->pivot->percent;
                $product->sold = $product->pivot->sold;
            }
        }

        parent::setData([
            'categories' => $list_tree,
            'data' => $data,
        ]);

        return parent::edit($id);
    }

    public function save(Request $request)
    {
        $data = $request->all();

        if (!isset($data['info']['show_home_page'])) {
            $data['info']['show_home_page'] = 0;
        }

        parent::setData($data);

        return parent::save($request);
    }

    function save_extend($id)
    {
        $this->saveProducts($id);
    }

    private function saveProducts($id)
    {
        $data = request()->input('products');
         
        if (!empty($data['id'])) {
            $products = $data['id'];
            $remove = explode(',', @$data['remove']);
            if (!empty($remove)) {
                DiscountModel::whereIn('product_id', $remove)->where('promotion_discount_category_id', $id)->delete();
            }

            $promotion = DiscountCategoryModel::find($id);

            $newStart = Carbon::parse($promotion->date_start);
            $newEnd = Carbon::parse($promotion->date_end);

            $promotionOther = DiscountCategoryModel::where('id', '!=', $id)
                ->where('published', 1)
                ->where(function ($query) use ($newStart, $newEnd) {
                    $query->whereBetween('date_start', [$newStart, $newEnd])
                        ->orWhereBetween('date_end', [$newStart, $newEnd])
                        ->orWhere(function ($query) use ($newStart, $newEnd) {
                            $query->where('date_start', '<=', $newStart)
                                ->where('date_end', '>=', $newEnd);
                        });
                })
                ->with('items')
                ->get();

            $productsExist = $promotionOther->pluck('items')->flatten()->pluck('product_id')->toArray();
            
            if (!empty($products)) {
                foreach ($products as $i => $item) {
                    if (!in_array($item, $productsExist)) {
                        $price = $data['price'][$i] ? $this->remove_fomart_money($data['price'][$i]) : NULL;
                     
                        DiscountModel::updateOrCreate(
                            ['product_id' => $item, 'promotion_discount_category_id' => $id],
                            [
                                'price' => $price,
                                'percent' => $price ? NULL : $data['percent'][$i],
                            ]
                        );
                    }
                }
            }
        }
    }
}
