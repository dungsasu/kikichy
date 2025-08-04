<?php

namespace App\Http\Controllers\admin\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Tour\TourCategories as TourCategoriesModel;
use App\Http\Controllers\BaseController;
use App\Models\admin\Filter\FilterTable as FilterTableModel;
use App\Traits\Tree;

class TourCategories extends BaseController
{
    use Tree;

    public function __construct()
    {
        $this->sizes_resize = [
            'resize' => [100, 100]
        ];
        parent::__construct(TourCategoriesModel::class, 'admin.tour.categories', 'TourCategories');
    }

    public function index()
    {
        $list = TourCategoriesModel::orderBy('ordering', 'asc')->get();
        $categories = $list->toArray();

        $list_tree = $this->indentRows2($list);
        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::index();
    }

    public function create()
    {
        $list = TourCategoriesModel::orderBy('ordering', 'asc')->get();
        $categories = $list->toArray();
        $list_tree = $this->indentRows2($list);

        $filterTable = FilterTableModel::where('published', 1)->get();

        parent::setData([
            'categories' => $list_tree,
            'filterTable' => $filterTable,
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $list = TourCategoriesModel::orderBy('ordering', 'asc')->get();
        $categories = $list->toArray();
        $list_tree = $this->indentRows2($list);

        $filterTable = FilterTableModel::where('published', 1)->get();

        parent::setData([
            'categories' => $list_tree,
            'filterTable' => $filterTable,
        ]);
        return parent::edit($id);
    }

    // public function setRedirect()
    // {
    //     return ['create_tour-categories', 'edit_tour-categories'];
    // }
    
    public function save(Request $request)
    {
        $data = $request->all();
        if (isset($data['parent_id']) && $data['parent_id'] == "0") {
            $data['parent_id'] = null;
        } else {
            if ($data['id']) {
                if ($this->checkParentId($data['parent_id'], $data['id'])) {
                    return back()->withErrors([
                        'Danh mục cha không hợp lệ',
                    ]);
                }
            }
        }
        parent::setData($data);
        return parent::save($request);
    }

    public function checkParentId($new_parent_id, $id)
    {
        if ($new_parent_id == $id) {
            return true;
        }
        $category = TourCategoriesModel::find($new_parent_id);
        if (!$category) {
            return false;
        }
        if ($category->parent_id == $id) {
            return true;
        }
        return $this->checkParentId($category->parent_id, $id);
    }

    protected function setRules()
    {
        return [
            'name' => 'required',
        ];
    }

    protected function setCustomMessages()
    {
        return [
            'name.required' => 'Tên danh mục không được để trống',
        ];
    }
}
