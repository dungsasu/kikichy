<?php

namespace App\Http\Controllers\admin\Product;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Product\Color as ColorModel;

class Color extends BaseController
{
    public function __construct()
    {
        parent::__construct(ColorModel::class, 'admin.product.color', 'color');
    }

    protected function setRedirect()
    {
        return ['create_color', 'edit_color'];
    }

    protected function setRules()
    {
        return [
            'name' => 'required',
        ];
    }

    protected function setCustomMessages()
    {
        return [
            'name.required' => 'Tên không được để trống',
        ];
    }


}
