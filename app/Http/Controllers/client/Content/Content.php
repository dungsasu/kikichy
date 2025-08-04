<?php

namespace App\Http\Controllers\client\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Content\Content as ContentModel;
use App\Models\admin\Content\ContentCategories as ContentCategoryModel;

class Content extends Controller
{
    public function index(){
        $content_categories = ContentCategoryModel::where('published',1)->with('contents')->get();
        return view('client.content.index');
    }
    public function detail($category, $alias){
        $content_categories = ContentCategoryModel::where('published',1)
        ->where('alias', $category)
        ->with('contents')
        ->first();
        if (!$content_categories) {
            return redirect()->route('client.home')->with(['message' => 'Danh mục bài viết không tồn tại', 'status' => 'error']);
        }
        $data = ContentModel::where('published',1)
        ->where('alias', $alias)
        ->where('category_id', $content_categories->id)
        ->first();
 
        if(!$data) {
            return redirect()->route('client.home')->with(['message'=> 'Bài viết không tồn tại', 'status' => 'error']);
        }

        return view('client.content.detail', ["data"=>$data, "content_categories"=> $content_categories]);
    }
}