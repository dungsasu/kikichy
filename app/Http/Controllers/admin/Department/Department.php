<?php

namespace App\Http\Controllers\admin\Department;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Department\Department as DepartmentModel;
use App\Traits\Tree;

class Department extends BaseController
{
    use Tree;

    public function __construct()
    {
        $this->model = DepartmentModel::class;
        $this->view = 'admin.department';
        $this->prefix = 'department';
        $this->sizes_resize = [
            'thumb' => [800, 400],
        ];
    }

    
}