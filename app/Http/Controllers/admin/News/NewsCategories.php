<?php

namespace App\Http\Controllers\admin\News;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\News\NewsCategories as NewsCategoriesModel;

class NewsCategories extends BaseController
{
    public function __construct()
    {
        parent::__construct(NewsCategoriesModel::class, 'admin.news.categories', 'news-categories');
    }
    

    public function create() {
        $list = NewsCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);
        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::create();
    }

    public function edit($id) {
        $list = NewsCategoriesModel::orderBy('ordering', 'asc')->get();
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
        $category = NewsCategoriesModel::find($new_parent_id);
        if(!$category) {
            return false;
        }
        if($category->parent_id == $id) {
            return true;
        }
        return $this->checkParentId($category->parent_id, $id);
    }

}
