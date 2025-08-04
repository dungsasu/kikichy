<?php

namespace App\Http\Controllers\admin\Recruitment;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Recruitment\Recruitment as RecruitmentModel;
use App\Traits\Tree;
use Illuminate\Support\Facades\DB;

class Recruitment extends BaseController
{
    use Tree;

    public function __construct()
    {
        $this->model = RecruitmentModel::class;
        $this->view = 'admin.recruitment';
        $this->prefix = 'recruitment';
        $this->sizes_resize = [
            'thumb' => [800, 400],
        ];
    }

    public function create()
    {
        $provinces = DB::table('provinces')->get();
        $departments = DB::table('departments')->where('published', 1)->get();

        parent::setData([
            'provinces' => $provinces,
            'departments' => $departments
        ]);
        return parent::create();
    }
    public function edit($id)
    {
        $provinces = DB::table('provinces')->get();
        $departments = DB::table('departments')->where('published', 1)->get();
        $data = RecruitmentModel::find($id);
        parent::setData([
            'provinces' => $provinces,
            'departments' => $departments,
            'data' => $data
        ]);
        return parent::edit($id);
    }
}