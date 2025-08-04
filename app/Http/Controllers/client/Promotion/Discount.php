<?php

namespace App\Http\Controllers\client\Promotion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Promotion\DiscountCategory as DiscountCategoryModel;
use App\Models\admin\Banner\Banner as BannerModel;
use App\Models\admin\Product\Product as ProductModel;
use App\Models\admin\News\News as NewsModel;
use App\Models\admin\News\NewsCategories as NewsCategoriesModel;

class Discount extends Controller
{
    public function detail($alias)
    {
        $data = DiscountCategoryModel::where('alias', $alias)
            ->where('published', 1) 
            ->first();

        if (!$data) {
            return redirect(route('client.home'))->with(
                'error',
                'Danh mục không tồn tại!',
            );
        }

        $breadcrumbs = [];
        $breadcrumbs[] = $data; 

        return view('client.promotion.discount.index', compact(
            'data',
            'breadcrumbs',
        ));
    } 

    public function home()
    {
        $data = DiscountCategoryModel::where('published', 1)
            // ->whereDate('date_start', '<=', date('Y-m-d'))
            // ->whereDate('date_end', '>=', date('Y-m-d'))
            // ->where('date_start', '<=', date('Y-m-d'))
            ->where('date_end', '>=', date('Y-m-d'))
            ->with([
                'products' => function($query) {
                    $query->where('published', 1)
                        ->orderBy('ordering', 'asc')
                        ->with('attributes', function($query2) {
                            $query2->where('published', 1)
                                ->where('price', '>', 0)
                                ->orderBy('ordering', 'asc');
                        });
                }
            ])
            ->orderBy('ordering', 'asc')
            ->get(); 

        $banner = BannerModel::where('category_id', 8)
            ->where('published', 1)
            ->get();

        // $products = ProductModel::where('published', 1)
        //     ->where('hot', 1) // Thêm điều kiện hot = 1
        //     ->orderBy('ordering', 'asc')
        //     ->limit(60)
        //     ->get();


        // $news = NewsCategoriesModel::where('published', 1)
        //     ->where('id', 9)
        //     ->with([
        //         'news' => function($query) {
        //             return $query->where('published', 1)
        //                 ->orderBy('ordering', 'asc')
        //                 ->limit(6);
        //         }
        //     ])
        //     ->first();

        return view('client.promotion.discount.categories', compact(
            'data',
            'banner',
            // 'products',
            // 'news'
        ));
    } 
}
