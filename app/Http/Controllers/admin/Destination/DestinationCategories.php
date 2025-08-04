<?php

namespace App\Http\Controllers\admin\Destination;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Destination\DestinationCategories as DestinationCategoriesModel;

class DestinationCategories extends BaseController
{
    public function __construct()
    {
        parent::__construct(DestinationCategoriesModel::class, 'admin.destination.categories', 'destination-categories');
    }
    

    public function create() {
        $list = DestinationCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);
        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::create();
    }

    public function edit($id) {
        $list = DestinationCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);
        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::edit($id);
    }

    function save(Request $request) {
        $data = $request->all();
        if($data['parent_id'] == "0") {
            $data['parent_id'] = null;
        } else {
            if($data['id']) {
                if($this->checkParentId($data['parent_id'], $data['id'])) {
                    return back()->withErrors([
                        'Danh mục cha không hợp lệ',
                    ]);
                }
            }
        }

        return parent::save($request);
    }

    public function checkParentId($new_parent_id, $id) {
        if($new_parent_id == $id) {
            return true;
        }
        $category = DestinationCategoriesModel::find($new_parent_id);
        if(!$category) {
            return false;
        }
        if($category->parent_id == $id) {
            return true;
        }
        return $this->checkParentId($category->parent_id, $id);
    }

}
