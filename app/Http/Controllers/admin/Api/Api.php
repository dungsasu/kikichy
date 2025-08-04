<?php

namespace App\Http\Controllers\admin\Api;

use App\Http\Controllers\BaseController;
use App\Models\admin\Menu\MenuItems as MenuItemsModel;
use Illuminate\Http\Request;
use App\Models\admin\Users\Users as UserModel;
use App\Models\admin\Product\Product as ProductModel;
use Illuminate\Support\Facades\DB;
use App\Models\admin\News\News as NewsModel;

class Api extends BaseController
{
    function createResponse($status, $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

    public $data = [
        'Kích hoạt thành công',
        'Ngừng kích hoạt thành công',
        'Xoá bản ghi thành công'
    ];
    public function get_menu_items(Request $request)
    {
        $data = $request->all();
        $group_id = $data['group_id'];
        $data = MenuItemsModel::where('group_id', $group_id)->first();
        return $this->createResponse(200, 'Success', $data);
    }

    public function updateStatus($status, $value)
    {
        $ids = request()->input('ids');
        $model = request()->input('model');
        if (!$ids) {
            return $this->createResponse(404, 'Chưa có bản ghi nào được chọn');
        }
        $model::whereIn('id', $ids)->update([$status => $value]);
        return $this->createResponse(200, 'Thành công', []);
    }

    public function delete()
    {
        $ids = request()->input('ids');
        $model = request()->input('model');
        $list = $model::whereIn('id', $ids)->get();
        foreach ($list as $item) {
            $item->delete();
        }
        return $this->createResponse(200, $this->data[2], []);
    }


    public function duplicate()
    {
        $ids = request()->input('ids');
        $model = request()->input('model');
        $controller = request()->input('controller');
        
        $duplicatedRecords = [];
        if (!$ids) {
            return $this->createResponse(404, 'Chưa có bản ghi nào được chọn');
        }
        foreach ($ids as $id) {
            $record = $model::find($id);
            if (!$record) {
                return $this->createResponse(404, 'Record not found');
                break;
            }
            $duplicateRecord = $record->replicate();
            $duplicateRecord->save();

            if ($controller) {
                if ($controller == 'App\Http\Controllers\admin\Product\Product') {
                    $productService = app()->make('App\Services\ProductService');
                    $instance = new $controller($productService);
                } else {
                    $instance = new $controller();
                }
                $method = 'duplicate_extend';
                if (method_exists($instance, $method)) {
                    $instance->$method($id, $duplicateRecord->id);
                }
            }
        }

        return $this->createResponse(200, 'Success', $duplicatedRecords);
    }

    function get_products_by_category(Request $request)
    {
        $data = $request->all();
        $category_id = $data['category_id'];
        $keyword = $data['keyword'];

        $allCategoryIds = $this->getAllCategoryIds([$category_id]);

        $data = ProductModel::where(function($query) use ($category_id, $keyword, $allCategoryIds) {
            if ($category_id) {
                // return $query->where('category_id_wrapper', 'like', '%' . $keyword . '%');
                return $query->whereIn('category_id', $allCategoryIds);
            }

            if ($keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%');
            }
        })->take(50)->get();

        return $this->createResponse(200, 'Success', $data);
    }

    function get_news_by_category(Request $request)
    {
        $data = $request->all();
        $category_id = $data['category_id'];
        $keyword = $data['keyword']; 

        $data = NewsModel::where(function($query) use ($category_id, $keyword) {
            if ($category_id) {
                return $query->where('category_id_wrapper', 'like', '%' . $category_id . '%'); 
            }

            if ($keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%');
            }
        })->take(50)->get();

        return $this->createResponse(200, 'Success', $data);
    }

    public function getGalleryComponent(Request $request)
    {
        $id = $request->id ?: 0;
        
        $name = $request->name ? $request->name: 'gallery';

        $html = view('components.gallery', ['name' => $name, 'field' => 'image', 'type' => 'Images', 'index' => $id])->render();

        return response()->json(['html' => $html]);
    }
}
