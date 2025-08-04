<?php

namespace App\Http\Controllers\admin\Product;

use App\Models\admin\Product\Fast as FastModel;
use App\Http\Controllers\BaseController;
use App\Traits\Tree;


class Fast extends BaseController
{
    use Tree;

    public $fastService;
    public function __construct()
    {
        $this->model = FastModel::class;
        $this->view = 'admin.product-fast';
        $this->prefix = 'product-fast';
        $this->searchField = 'code_prd,variant';
    }

    public function index()
    {
        $code = request()->input('code');
        if ($code) {
            $list = FastModel::where('code_prd', 'like', "%$code%")
                ->orWhere('variant', 'like', "%$code%")
                ->orderBy('code_prd', 'asc')->paginate(100);
            $this->list = $list;
        }

        return parent::index();
    }
}
