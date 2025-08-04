<?php

namespace App\Http\Controllers\admin\Extend;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Extend\Extend as ExtendModel;

class Extend extends BaseController
{
    public function __construct()
    {
        $model = ExtendModel::class;
        $view = 'admin.extends';
        $filter = 'extends';
        parent::__construct($model, $view, $filter);
    }
}
