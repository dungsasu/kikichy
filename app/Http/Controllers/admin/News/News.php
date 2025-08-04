<?php

namespace App\Http\Controllers\admin\News;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\News\News as NewsModel;
use App\Models\admin\News\NewsCategories as NewsCategoriesModel;
use App\Traits\Tree;

class News extends BaseController
{
    use Tree;

    public function __construct()
    {
        $this->model = NewsModel::class;
        $this->view = 'admin.news';
        $this->prefix = 'news';
        $this->sizes_resize = [
            'thumb' => [800, 400],
        ];
    }

    public function index()
    {
        $filter = Request()->session()->get('filter');
        $categories = NewsCategoriesModel::where('published', 1)->orderBy('ordering', 'desc')->get();
        $list_tree = $this->indentRows2($categories);
        $list = NewsModel::with('category')->orderBy('ordering', 'asc')->get();
        
        parent::setData([
            'filter' => $filter,
            'categories' => $list_tree,
            'list' => $list
        ]);
        return parent::index();
    }

    public function create()
    {
        $list = NewsCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);

        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::create();
    }
    public function edit($id)
    {
        $list = NewsCategoriesModel::orderBy('ordering', 'asc')->get();
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
            $data_category = NewsCategoriesModel::where('id', $data['category_id'])->first();

            if ($data_category->list_parent_id) {
                $data['category_id_wrapper'] = $data_category->list_parent_id . $data['category_id'] . ',';
            } else {
                $data['category_id_wrapper'] = ',' . $data['category_id'] . ',';
            }
        }

        if (!isset($data['hot'])) {
            $data['hot'] = 0;
        }
        parent::setData($data);

        return parent::save($request);
    }

    public function setRules() {
        return ['name' => 'required'];
    }
    public function setCustomMessages() {
        return [
            'name.required' => 'Tiêu đề không được để trống',
            'category_id.required' => 'Danh mục không được để trống'
        ];
    }

}