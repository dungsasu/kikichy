<?php

namespace App\Http\Controllers\admin\Store;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Store\Store as StoreModel;
use App\Models\admin\Province\Province as ProvinceModel;

class Store extends BaseController
{
    function __construct()
    {
        parent::__construct(StoreModel::class, 'admin.store', 'store');
    }
    
    function create() {
        $cities = ProvinceModel::all();
        parent::setData([
            'cities' => $cities
        ]);
        return parent::create();
    }

    function edit($id) {
        $cities = ProvinceModel::all();
        parent::setData([
            'cities' => $cities
        ]);
        return parent::edit($id);
    }
}
