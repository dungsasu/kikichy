<?php

namespace App\Http\Controllers\admin\Destination;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Destination\Destination as DestinationModel;
use App\Models\admin\Destination\DestinationCategories as DestinationCategoriesModel;
use App\Traits\Tree;

class Destination extends BaseController
{
    use Tree;

    public function __construct()
    {
        $this->model = DestinationModel::class;
        $this->view = 'admin.destination';
        $this->prefix = 'destination';
        $this->sizes_resize = [
            'thumb' => [800, 400],
        ];
    }

    public function index()
    {
        $filter = Request()->session()->get('filter');
        $categories = DestinationCategoriesModel::where('published', 1)->orderBy('ordering', 'desc')->get();
        $list_tree = $this->indentRows2($categories);
        $list = DestinationModel::with('category')->orderBy('ordering', 'asc')->get();

        parent::setData([
            'filter' => $filter,
            'categories' => $list_tree,
            'list' => $list
        ]);
        return parent::index();
    }

    public function create()
    {
        $list = DestinationCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);

        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::create();
    }
    public function edit($id)
    {
        // dd($id);
        $list = DestinationCategoriesModel::orderBy('ordering', 'asc')->get();
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
            $data_category = DestinationCategoriesModel::where('id', $data['category_id'])->first();

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