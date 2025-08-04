<?php

namespace App\Http\Controllers\admin\Banner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Banner\BannerCategory as BannerCategoryModel;
use App\Models\admin\Product\ProductCategories as ProductCategoriesModel;

class BannerCategory extends Controller
{
    public function __construct()
    {
        $this->model = BannerCategoryModel::class;
        $this->view = 'admin.banner.categories';
        $this->prefix = 'banner_categories';
    }

    public function create()
    {
        $categories = ProductCategoriesModel::where('published', 1)->orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($categories);
        parent::setData([
            'productCategories' => $list_tree
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $categories = ProductCategoriesModel::where('published', 1)->orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($categories);
        parent::setData([
            'productCategories' => $list_tree
        ]);
        return parent::edit($id);
    }
}
