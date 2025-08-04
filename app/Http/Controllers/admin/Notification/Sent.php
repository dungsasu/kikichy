<?php

namespace App\Http\Controllers\admin\Notification;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Notification\Sent as SentNotificationModel;

class Sent extends BaseController
{
    public function __construct()
    {
        $this->model = SentNotificationModel::class;
        $this->view = 'admin.sent';
        $this->prefix = 'sent';
        $this->sizes_resize = [
            'thumb' => [800, 400],
        ];
    }


}
