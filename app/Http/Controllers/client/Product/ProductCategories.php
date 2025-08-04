<?php

namespace App\Http\Controllers\client\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Product\ProductCategories as ProductCategoriesModel;
use App\Models\admin\Product\Product as ProductModel;
use App\Models\admin\Product\ProductColorImage;

class ProductCategories extends Controller
{
    public function home($alias)
    {
        $data = ProductCategoriesModel::where('alias', $alias)
            ->where('published', 1)
            ->with('products', function ($query) {
                $query->orderBy('ordering', 'desc');
            })->first();

        $list_parent_id = [];
        if(!$data) {
            return redirect(route('client.home'))->with([
                'message' => 'Danh mục không tồn tại',
                'status' => 'error']);
        }
        if ($data->list_parent_id != "0") {
            $list_parent_id = explode(',', trim($data->list_parent_id, ',') . ',' . $data->id);
        } else {
            $list_parent_id = array(0 => $data->id);
        }
        if (!$data->list_parent_id || count($list_parent_id) == 1 || count($list_parent_id) == 2) {
            $products = ProductModel::where('published', 1)
                ->where('category_id_wrapper', 'like', '%,' . $data->id . ',%')
                ->orderBy('ordering', 'desc')
                ->paginate(40);
        } else {
            $products = ProductModel::where('published', 1)
                ->where('category_id', $data->id)
                ->orderBy('ordering', 'desc')
                ->paginate(40);
        }

        foreach ($products as $item) {
            $productColorImages = ProductColorImage::where('product_id', $item->id)->get()->groupBy('color_id')->toArray();
            $newImages = [];
            foreach ($productColorImages as $imageGroup) {
                foreach ($imageGroup as $image) {
                    $imageObject = (object) $image;
                    $newImages[] = $imageObject;
                }
            }
            $item->images = $newImages;
        }

        $breadcrumbs =  ProductCategoriesModel::whereIn('id', explode(',', $data->list_parent_id))->get();
        $other_categories = ProductCategoriesModel::where('parent_id', $data->id)->orderBy('ordering', 'asc')->get();

        return view('client.product.categories.index', [
            'data' => $data,
            'products' => $products,
            'breadcrumbs' => $breadcrumbs,
            'other_categories' => $other_categories
        ]);
    }
}
