<?php

namespace App\Http\Controllers\admin\Filter;

use App\Http\Controllers\Controller;
use App\Models\admin\Filter\Filter as FilterModel;
use App\Models\admin\Filter\FilterCategory as FilterCategoryModel;
use Illuminate\Http\Request;

class Filter extends Controller
{
    public function __construct()
    {
        $this->model = FilterModel::class;
        $this->view = 'admin.filter';
        $this->prefix = 'filter';
        $this->searchField = 'name';
        $this->categoryField = 'filter_category_id';
    }

    public function index()
    {
        $filter = request()->session()->get('filter');
        $categories = FilterCategoryModel::where('published', 1)->get();
        parent::setData([
            'categories' => $categories,
            'filter' => $filter,
        ]);
        return parent::index();
    }

    public function create()
    {
        $category = FilterCategoryModel::where('published', 1)->get();
        parent::setData([
            'category' => $category,
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $category = FilterCategoryModel::where('published', 1)->get();
        parent::setData([
            'category' => $category,
        ]);
        return parent::edit($id);
    } 
}
