<?php

namespace App\Http\Controllers\admin\Product;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Product\Size as SizeModel;

class Size extends BaseController
{
    public function __construct()
    {
        parent::__construct(SizeModel::class, 'admin.product.size', 'size');
    }

    protected function setRedirect()
    {
        return ['create_size', 'edit_size'];
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
