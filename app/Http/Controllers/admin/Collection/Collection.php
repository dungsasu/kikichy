<?php

namespace App\Http\Controllers\admin\Collection;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Collection\Collection as CollectionModel;
use App\Models\admin\Product\ProductCategories as CategoryModel;
use App\Models\admin\Product\Product as ProductModel;

class Collection extends BaseController
{
    public function __construct()
    {
        parent::__construct(CollectionModel::class, 'admin.collection', 'collection');
    }

    public function create()
    {
        $categories = CategoryModel::where('published', 1)->orderBy('ordering', 'desc')->get();
        $list_tree = $this->indentRows2($categories);
        parent::setData([
            'categories' => $list_tree
        ]);
        return parent::create();
    }
    public function edit($id)
    {
        $categories = CategoryModel::where('published', 1)->orderBy('ordering', 'desc')->get();
        $list_tree = $this->indentRows2($categories);
        $data = CollectionModel::find($id);
        $array_related = explode(',', $data->product_related);
        $related = ProductModel::whereIn('id', $array_related)->get();

        parent::setData([
            'categories' => $list_tree,
            'related' => $related
        ]);
        return parent::edit($id);
    }
    public function setRedirect()
    {
        return ['create_collection', 'edit_collection'];
    }
    public function save(Request $request)
    {
        $data = $request->all();

        if(!$data['name']) {
            return redirect()->back()->with('error', 'Bạn chưa nhập tên bộ sưu tập');
        }
        unset($data['type']);
        parent::setData($data);
        return parent::save($request);
    }

    public function save_extend($id)
    {
        ProductModel::where('collection_id', $id)->update(['collection_id' => null]);

        if($this->data['product_related']) {
            $products = explode(',', $this->data['product_related']);

            foreach ($products as $productId) {
                $product = ProductModel::find($productId);
                if ($product) {
                    $product->collection_id = $id;
                }
                $product->save();
            }
        }

    }
}
