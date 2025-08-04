<?php

namespace App\Http\Controllers\client\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Tour\TourCategories as TourCategoriesModel;
use App\Models\admin\Tour\Tour as TourModel;

class TourCategories extends Controller
{
    public function home($alias)
    {
        $category = TourCategoriesModel::where('alias', $alias)->where('published', 1)->first();
        
        if (!$category) {
            abort(404, 'Danh mục tour không tồn tại');
        }

        $tours = TourModel::where('published', 1)
            ->where('category_id', $category->id)
            ->with(['images', 'category'])
            ->paginate(12);

        return view('client.tour.categories', compact('category', 'tours'));
    }
}
