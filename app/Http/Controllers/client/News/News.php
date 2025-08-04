<?php

namespace App\Http\Controllers\client\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\admin\News\NewsCategories as NewsCategoriesModel;
use App\Models\admin\News\News as NewsModel;

class News extends Controller
{
    public function index()
    {
        $list_category_news = [];
        $list_hot = NewsModel::where('published', 1)
            ->where('hot', 1)
            ->orderBy('created_at', 'desc')
            ->with('category')
            ->take(5)
            ->get();

        $list_category_news = NewsCategoriesModel::where('published', 1)
            ->orderBy('created_at')
            ->with('news', function ($query) {
                $query->where('published', 1)
                    ->orderBy('created_at', 'desc');
            })->get();

        foreach ($list_category_news as $index => $category) {
            $limit = $index === 0 ? 5 : 4;
            $category->news = $category->news()->where('published', 1)->orderBy('created_at','desc')->take($limit)->get();
        }

        return view('client.news.home', [
            'list_category_news' => $list_category_news,
            'list_hot' => $list_hot
        ]);
    }

    public function detail($category, $alias)
    {
        $data = NewsModel::where('alias', $alias)->where('published', 1)->first();
        // dd($alias);
        if (!$data) {
            return redirect()->route('client.news')->with([
                'message' => 'Bài viết không tồn tại',
                'status' => 'error',
            ]);
        }
        $news_data = NewsModel::where('published', 1)
            ->where('alias', $category)
            ->orderBy('updated_at')
            ->take(20)
            ->get();
        $relatedArticles = [];
        if ($data && $data->category_id) {
            // Lấy các bài viết liên quan dựa trên category_id
            $relatedArticles = NewsModel::where('category_id', $data->category_id)
                ->where('id', '<>', $data)  // Loại bỏ bài viết hiện tại
                ->where('published', 1)  // Chỉ lấy các bài viết đã được xuất bản
                ->orderBy('created_at', 'desc')  // Sắp xếp theo ngày tạo, mới nhất ở đầu
                ->take(20)  // Giới hạn số lượng bài viết liên quan, có thể điều chỉnh
                ->get();
        }

        return view('client.news.detail', ['data' => $data, 'news_data' => $news_data, 'relatedArticles' => $relatedArticles]);
    }
    public function categories($alias)
    {

        $category = NewsCategoriesModel::where('alias', $alias)->where('published', 1)->first();
        //dd($category);

        $category = NewsCategoriesModel::where('alias', $alias)->where('published', 1)->first();
        //dd($category);

        $getNewsByCategories = NewsModel::where('published', 1)
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->get();
        //dd($getNewsByCategories);
        return view('client.news.categories', [
            'category' => $category,
            'getNewsByCategories' => $getNewsByCategories,
            'alias' => $alias
        ]);
    }
}

