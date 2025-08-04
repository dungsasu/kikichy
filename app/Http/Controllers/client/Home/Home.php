<?php

namespace App\Http\Controllers\client\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Gallery\Gallery as GalleryModel;
use App\Models\admin\Collection\Collection as CollectionModel;
use App\Models\admin\Product\Product as ProductModel;
use App\Traits\CommonFunctionTrait;
use App\Models\admin\Customer\Customer as CustomerModel;
use App\Models\admin\Product\ProductColorImage;
use stdClass;
use App\Models\admin\News\News as NewsModel;
use App\Models\admin\Banner\Banner as BannerModel;
use App\Models\admin\Banner\BannerCategory as BannerCategoryModel;

class Home extends Controller
{
    use CommonFunctionTrait;

    public function index()
    {
        // Lấy banners từ category có id = 6 (slide banner trang chủ)
        $banners = BannerModel::where('published', 1)
            ->where('category_id', 6)
            ->with('category')
            ->orderBy('ordering', 'asc')
            ->get();

        return view('client.home.index', [
            'banners' => $banners,
        ]);
    }
}