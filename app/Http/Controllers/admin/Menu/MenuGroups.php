<?php

namespace App\Http\Controllers\admin\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Http\Controllers\BaseController;
use App\Models\admin\Menu\MenuGroups as MenuGroupsModel;

class MenuGroups extends BaseController
{
    public function __construct()
    {
        parent::__construct(MenuGroupsModel::class, 'admin.menu.categories');
    }

}
