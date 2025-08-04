<?php

namespace App\Http\Controllers\client\Fashion_shows;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Fashion\Fashion as FashionModel;

class Fashion_shows extends Controller
{
    public function index(){
        $fashions = FashionModel::where('published', 1)->with('images')->paginate(8);
        $groupedFashions = $fashions->chunk(4);
        return view('client.fashion_shows.index', compact('groupedFashions', 'fashions'));
    }


}