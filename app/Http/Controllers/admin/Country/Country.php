<?php

namespace App\Http\Controllers\admin\Country;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Country\Country as CountryModel;

class Country extends BaseController
{
    public function __construct()
    {
        parent::__construct(CountryModel::class, 'admin.country', 'country');
        // Use ordering for sorting
        $this->order_by = ['ordering', 'asc'];
    }

    public function index()
    {
        // Debug view path
        $viewPath = $this->view . '.index';
        if (!view()->exists($viewPath)) {
            return response("View [$viewPath] không tồn tại. Kiểm tra file: " . resource_path('views/admin/country/index.blade.php'), 404);
        }
        
        try {
            return parent::index();
        } catch (\Exception $e) {
            return response("Lỗi: " . $e->getMessage(), 500);
        }
    }
}
