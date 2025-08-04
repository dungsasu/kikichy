<?php

namespace App\Http\Controllers\client\Shops;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Store\Store as StoreModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Province\Province as ProvincesModel;

class Shops extends Controller
{
    public function index(){
        $provinces = ProvincesModel::get();
        $stores = StoreModel::where('published', 1)->orderBy('ordering', 'desc')->with('province')->get();
        return view('client.shops.index',[
            'stores' => $stores,
            'provinces' => $provinces,
        ]);
    }
    public function getMapData(Request $request)
  {
    $id = $request->input('id');
    // Lấy item cửa hàng theo ID
    $item = StoreModel::find($id);
    
    if ($item) {
      return response()->json(['description' => $item->description]);
    } else {
      return response()->json(['error' => 'Item không tìm thấy'], 404);
    }
  }
}