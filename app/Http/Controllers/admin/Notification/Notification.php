<?php

namespace App\Http\Controllers\admin\Notification;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Notification\Notification as NotificationModel;
use App\Traits\Tree;

class Notification extends BaseController
{
    use Tree;

    public function __construct()
    {
        $this->model = NotificationModel::class;
        $this->view = 'admin.notification';
        $this->prefix = 'notification';
        $this->sizes_resize = [
            'thumb' => [800, 400],
        ];
    }

    public function create() {
        $types = array(
            array('value' => 'ALL', 'label' => 'Tất cả'),
            array('value' => 'DMCCLUB', 'label' => 'SIXCLUB'),
            array('value' => 'DMC60', 'label' => 'DMC60'),
            array('value' => 'DMC120', 'label' => 'DMC120'),
            array('value' => 'DMC300', 'label' => 'DMC300'),
            array('value' => 'DMC600', 'label' => 'DMC600'),
        );

        parent::setData([
            'types' => $types
        ]);

        return parent::create();

    }

    public function edit($id) {
        $types = array(
            (object)array('value' => 'ALL', 'label' => 'Tất cả'),
            (object)array('value' => 'DMCCLUB', 'label' => 'DMCCLUB'),
            (object)array('value' => 'DMC60', 'label' => 'DMC60'),
            (object)array('value' => 'DMC120', 'label' => 'DMC120'),
            (object)array('value' => 'DMC300', 'label' => 'DMC300'),
            (object)array('value' => 'DMC600', 'label' => 'DMC600'),
            (object)array('value' => 'OPTIONAL', 'label' => 'Tuỳ chọn'),
        );

        parent::setData([
            'types' => $types
        ]);

        return parent::edit($id);

    }

}