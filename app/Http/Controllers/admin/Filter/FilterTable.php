<?php

namespace App\Http\Controllers\admin\Filter;

use App\Http\Controllers\Controller;
use App\Models\admin\Filter\FilterTable as FilterTableModel;
use App\Models\admin\Filter\FilterCategory as FilterCategoryModel;
use App\Models\admin\Filter\FilterTableItem as FilterTableItemModel;
use Illuminate\Http\Request;

class FilterTable extends Controller
{
    public function __construct(){
        $this->model = FilterTableModel::class;
        $this->view = 'admin.filter.table';
        $this->prefix = 'filter_table';
    }

    public function create()
    {
        $categories = FilterCategoryModel::where('published', 1)->get();
        parent::setData([
            'categories' => $categories,
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $categories = FilterCategoryModel::where('published', 1)->get();
        $data = FilterTableModel::with([
            'items' => function($query) {
                $query->orderBy('ordering', 'asc');
            }
        ])->find($id);

        parent::setData([
            'categories' => $categories,
            'data' => $data,
        ]);
        return parent::edit($id);
    } 

    function save_extend($id)
    {
        $this->saveItems($id);
    }

    private function saveItems($id) {
        $request = request()->all();
        $items = $request['items'];
         
        if (!empty($items)) {
            $data = [];

            $remove = explode(',', @$items['remove']);
            if (!empty($remove)) {
                FilterTableItemModel::whereIn('id', $remove)->delete(); 
            }

            unset($items['remove']);
           
            foreach ($items as $name => $nameValue) {
                foreach ($nameValue as $key => $value) {
                    $data[$key][$name] = $value;
                    $data[$key]['filter_table_id'] = $id;
                    $data[$key]['published'] = isset($items['published'][$key]) && $items['published'][$key] ? 1 : 0;
                    $data[$key]['is_filter'] = isset($items['is_filter'][$key]) && $items['is_filter'][$key] ? 1 : 0;
                    $data[$key]['is_outstanding'] = isset($items['is_outstanding'][$key]) && $items['is_outstanding'][$key] ? 1 : 0;
                } 
            } 
            
            foreach ($data as $item) {
                $updateOrCreate = $item; 

                FilterTableItemModel::updateOrCreate(
                    ['id' => @$item['id']],
                    $updateOrCreate
                );
            }         
        }
    }
}
