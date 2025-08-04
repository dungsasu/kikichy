<?php

namespace App\Http\Controllers\admin\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Http\Controllers\BaseController;
use App\Models\admin\Menu\MenuItems as MenuItemModel;
use App\Models\admin\Menu\MenuGroups as MenuGroupsModel;
use App\Traits\Tree;

class MenuItems extends BaseController
{
    use Tree;
    public function __construct()
    {
        parent::__construct(MenuItemModel::class, 'admin.menu', 'menu');
    }
    public function index()
    {
        $groups = MenuGroupsModel::where('published', 1)->orderBy('ordering', 'asc')->get();
        return view($this->view . '.index', compact('groups'));
    }
    public function list_category()
    {
        $model = request()->input('model_category');
        $type = request()->input('type');
        $list = [];
        $categories = $model::where('published', 1)->orderBy('ordering', 'asc')->get();
        $categories = $this->indentRows2($categories);
        $categories = array_values($categories);

        return response()->json([
            'status' => 200,
            'message' => 'Thành công',
            'data' => $categories
        ]);
    }

    public function list_item()
    {
        $model_category = request()->input('model_category');
        $category_id = request()->input('category_id');
        $model = request()->input('model');
        $list = $model::where('published', 1)->where('category_id_wrapper', 'like', '%,' . $category_id . ',%')->get(['id', 'alias', 'name']);

        return response()->json([
            'status' => 200,
            'message' => 'Thành công',
            'data' => $list
        ]);
    }

    public function create_link()
    {
        $type = request()->input('type');
        $route = request()->input('route');
        $model = request()->input('model');
        $model_category = request()->input('model_category');
        $category_id = request()->input('category_id');

        if ($type == 'default') {
            $link = route($route);
        } else if ($type == 'category') {
            $category = $model_category::find($category_id);
            $link = route($route, ['alias' => $category->alias]);
        } else {
            $id = request()->input('id');
            $category = $model_category::find($category_id);
            $data = $model::find($id);
            $link = route($route, ['category' => $category->alias, 'alias' => $data->alias]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Thành công',
            'data' => $link
        ]);
    }

    public function save(Request $request)
    {
        $data = $request->all();
        $instance = $this->model::where('group_id', $data['group_id'])->first();
        unset($data['shouldRedirect']);
        unset($data['_token']);

        if (!$instance) {
            $instance = new $this->model;
        }
        foreach ($data as $key => $value) {
            $instance->$key = $value;
        }
        $instance->save();
    }
}
