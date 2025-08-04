<?php

namespace App\Http\Controllers\admin\Content;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Content\Content as ContentModel;
use App\Models\admin\Content\ContentCategories as ContentCategoriesModel;

class Content extends BaseController
{
    public function __construct()
    {
        $this->model = ContentModel::class;
        $this->view = 'admin.contents';
        $this->prefix = 'contents';
    }

    public function index()
    {
        $filter = Request()->session()->get('filter');
        $categories = ContentCategoriesModel::where('published', 1)->orderBy('ordering', 'desc')->get();
        $list_tree = $this->indentRows2($categories);
        $list = ContentModel::with('category')->orderBy('ordering', 'asc')->get();
        parent::setData([
            'filter' => $filter,
            'categories' => $list_tree,
            'list' => $list
        ]);
        return parent::index();
    }

    public function create()
    {
        $list = ContentCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);

        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::create();
    }
    public function edit($id)
    {
        $list = ContentCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);
        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::edit($id);
    }

    public function save(Request $request)
    {
        $data = $request->all();
        
        if ($data['category_id'] == 0) {
            return back()->withErrors([
                'Bạn chưa chọn danh mục',
            ]);
        } else {
            $data_category = ContentCategoriesModel::where('id', $data['category_id'])->first();

            if ($data_category->list_parent_id) {
                $data['category_id_wrapper'] = $data_category->list_parent_id . $data['category_id'] . ',';
            } else {
                $data['category_id_wrapper'] = ',' . $data['category_id'] . ',';
            }
        }

        parent::setData($data);

        return parent::save($request);
    }
}