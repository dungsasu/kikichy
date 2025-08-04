<?php

namespace App\Http\Controllers\admin\Filter;

use App\Http\Controllers\Controller;
use App\Models\admin\Filter\FilterCategory as FilterCategoryModel;
use App\Models\admin\Filter\FilterGroup as FilterGroupModel;
use Illuminate\Http\Request;

class FilterCategory extends Controller
{
    public function __construct()
    {
        $this->model = FilterCategoryModel::class;
        $this->view = 'admin.filter.category';
        $this->prefix = 'filter_category';
        $this->searchField = 'name';
        $this->categoryField = 'group_id';
    }

    public function index()
    {
        $filter = request()->session()->get('filter');
        $groups = FilterGroupModel::where('published', 1)->get();
        parent::setData([
            'groups' => $groups,
            'filter' => $filter,
        ]);
        return parent::index();
    }

    public function create()
    {
        $group = FilterGroupModel::where('published', 1)->get();
        parent::setData([
            'group' => $group,
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $group = FilterGroupModel::where('published', 1)->get();
        parent::setData([
            'group' => $group,
        ]);
        return parent::edit($id);
    }
}
