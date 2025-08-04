<?php

namespace App\Http\Controllers\admin\Customer;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Customer\Customer as CustomerModel;

class Customer extends BaseController
{
    public function __construct()
    {
        $model = CustomerModel::class;
        $view = 'admin.customer';
        $filter = 'customer';
        parent::__construct($model, $view, $filter);
    }
}
