<?php

namespace App\Http\Controllers\client\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Collection\Collection as CollectionModel;

class Collection extends Controller
{
    public function home($alias) {

        $data = CollectionModel::where('alias', $alias)->where('published', 1)->with('products')->orderBy('created_at', 'desc')->first();

        $other_collections = CollectionModel::where('alias', '!=', $alias)->where('published', 1)->orderBy('created_at', 'desc')->get();
        if(!$data) {
            return redirect()->route('client.home')->with(['message' => 'Chưa có bộ sưu tập này', 'status' => 'error']);
        }

        return view('client.collection.index', ['data' => $data, 'other_collections' => $other_collections]);
    }
}