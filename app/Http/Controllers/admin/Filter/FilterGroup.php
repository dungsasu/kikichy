<?php

namespace App\Http\Controllers\admin\Filter;

use App\Http\Controllers\Controller;
use App\Models\admin\Filter\FilterGroup as FilterGroupModel;
use Illuminate\Http\Request;

class FilterGroup extends Controller
{
    public function __construct()
    {
        $this->model = FilterGroupModel::class;
        $this->view = 'admin.filter.group';
        $this->prefix = 'filter_group';
    }
}
