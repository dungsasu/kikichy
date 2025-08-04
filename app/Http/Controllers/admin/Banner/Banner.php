<?php

namespace App\Http\Controllers\admin\Banner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\admin\Banner\Banner as BannerModel;
use App\Models\admin\Banner\BannerCategory as BannerCategoryModel;

class Banner extends Controller
{
    public function __construct()
    {
        $view = 'admin.banner';
        $prefix = 'banner';
        parent::__construct(BannerModel::class, $view, $prefix);
    }

    public function index()
    {
        $filter = request()->session()->get('filter');
        $categories = BannerCategoryModel::where('published', 1)->orderBy('id', 'desc')->get();

        $list = BannerModel::with('category')->paginate($this->limit);
        parent::setData([
            'filter' => $filter,
            'categories' => $categories,
            'list' => $list
        ]);
        return parent::index();
    }

    public function create()
    {
        $categories = BannerCategoryModel::where('published', 1)->get();
        parent::setData([
            'categories' => $categories,
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $categories = BannerCategoryModel::where('published', 1)->get();
        parent::setData([
            'categories' => $categories,
        ]);
        return parent::edit($id);
    }
 

    public function save(Request $request)
    {
        $cat = BannerCategoryModel::find($request->category_id);

        if ($cat && $cat->ratio) {
            $this->sizes_resize = [
                'resize' => explode(':', $cat->ratio),
            ];
        } 
        
        // Xử lý ordering để tránh lỗi null
        if (!$request->has('ordering') || $request->ordering === null || $request->ordering === '') {
            if ($request->id) {
                // Nếu đang edit, giữ nguyên ordering cũ hoặc lấy max + 1
                $oldRecord = BannerModel::find($request->id);
                if ($oldRecord && $oldRecord->ordering) {
                    $request->merge(['ordering' => $oldRecord->ordering]);
                } else {
                    $maxOrdering = BannerModel::max('ordering') ?? 0;
                    $request->merge(['ordering' => $maxOrdering + 1]);
                }
            } else {
                // Nếu tạo mới, lấy max ordering + 1
                $maxOrdering = BannerModel::max('ordering') ?? 0;
                $request->merge(['ordering' => $maxOrdering + 1]);
            }
        }
        
        return parent::save($request);
    }
}
