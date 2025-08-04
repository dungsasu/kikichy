<?php

namespace App\Http\Controllers\admin\City;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\City\City as CityModel;
use App\Models\admin\Country\Country as CountryModel;

class City extends BaseController
{
    public function __construct()
    {
        parent::__construct(CityModel::class, 'admin.city', 'city');
        // Use ordering for sorting
        $this->order_by = ['ordering', 'asc'];
    }

    public function index()
    {
        // Debug view path
        $viewPath = $this->view . '.index';
        if (!view()->exists($viewPath)) {
            return response("View [$viewPath] không tồn tại. Kiểm tra file: " . resource_path('views/admin/city/index.blade.php'), 404);
        }
        
        try {
            return parent::index();
        } catch (\Exception $e) {
            return response("Lỗi: " . $e->getMessage(), 500);
        }
    }

    public function create() {
        $countries = CountryModel::where('published', 1)->orderBy('name', 'asc')->get();
        parent::setData([
            'countries' => $countries,
        ]);
        return parent::create();
    }

    public function edit($id) {
        $countries = CountryModel::where('published', 1)->orderBy('name', 'asc')->get();
        parent::setData([
            'countries' => $countries,
        ]);
        return parent::edit($id);
    }
}
