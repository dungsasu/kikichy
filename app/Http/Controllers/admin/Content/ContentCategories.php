<?php

namespace App\Http\Controllers\admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\admin\Content\ContentCategories as ContentCategoriesModel;

class ContentCategories extends BaseController
{
    public function __construct()
    {
        $this->model = ContentCategoriesModel::class;
        $this->view = 'admin.contents.categories';
        $this->prefix = 'contents.categories';
    }
    
    public function create() {
        $list = ContentCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);
        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::create();
    }

    public function edit($id) {
        $list = ContentCategoriesModel::orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($list);
        parent::setData([
            'categories' => $list_tree,
        ]);
        return parent::edit($id);
    }

}
