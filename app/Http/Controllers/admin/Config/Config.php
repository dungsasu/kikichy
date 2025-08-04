<?php

namespace App\Http\Controllers\admin\Config;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\admin\Config\Config as ConfigModel;
use App\Traits\CommonFunctionTrait;
use Illuminate\Http\Request;

class Config extends BaseController
{
    use CommonFunctionTrait;
    public function __construct()
    {
        $this->model = ConfigModel::class;
        $this->view = 'admin.config';
        $this->prefix = 'config';
    }
    public function index() {
        $this->order_by = ['ordering', 'asc'];
        return parent::index();
    }
    public function save(Request $request) {
        $data = request()->all();
        foreach ($data as $key => $value) {
            if ($key != '_token') {
                ConfigModel::where('alias', $key)->update(['value' => $value]);
            }
            if ($key == 'logo') {
                $pathImage = $data['logo'];
                $this->upload2($pathImage, [[50, 50]], '/img/favicon/', 'favicon.png');
            }
        }

        return redirect()->back();
    }
}
