<?php

namespace App\Http\Controllers\admin\Tour;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Tour\TourTypes as TourTypesModel;

class TourTypes extends BaseController
{
    public function __construct()
    {
        $this->model = TourTypesModel::class;
        $this->view = 'admin.tour-types';
        $this->prefix = 'tour_types';
        $this->sizes_resize = [
            'thumb' => [800, 400],
        ];
    }

    public function index()
    {
        // Gọi parent index method và lấy view response
        $response = parent::index();
        
        // Nếu là view response, ghi đè show_button_action
        if ($response instanceof \Illuminate\View\View) {
            $data = $response->getData();
            $data['show_button_action'] = true;
            $response->with($data);
        }
        
        return $response;
    }

    public function create()
    {
        return parent::create();
    }

    public function edit($id)
    {
        return parent::edit($id);
    }

    public function save(Request $request)
    {
        parent::setData([
            'id' => $request->id,
            'ordering' => $request->ordering,
            'name' => $request->name,
            'published' => $request->published,
            'alias' => $request->alias,
        ]);

        return parent::save($request);
    }

    protected function setRedirect()
    {
        return ['admin.tour-types.create', 'admin.tour-types.edit', 'admin.tour-types.index'];
    }

    public function deleteRecords(Request $request)
    {
        return parent::delete($request);
    }
}
