<?php

namespace App\Http\Controllers\admin\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\admin\Role\Role as RoleModel;
use App\Models\admin\Role\RolePermission as RolePermissionsModel;
use Illuminate\Support\Facades\Auth;

class Role extends BaseController
{
    public function __construct()
    {
        parent::__construct(RoleModel::class, 'admin.roles', 'roles');
    }

    public function edit($id)
    {
        $data = $this->model::findOrFail($id);
        $data->permissions = $data->permissions()->get()->toArray();

        return view($this->view . '.form', compact('data'));
    }

    protected function setRedirect()
    {
        return ['admin.role.create', 'admin.role.edit'];
    }

    public function save(Request $request)
    {
        parent::setData([
            'id' => $request->id,
            'ordering' => $request->ordering,
            'name' => $request->name,
            'published' => $request->published,
            'alias' => $request->alias,
            'shouldRedirect' => $request->shouldRedirect
        ]);

        $user = Auth::user();
        $user->load(['rolePermission' => function ($query) {
            $query->where('permission', 1);
        }]);
        $request->session()->put('user', $user);
        return parent::save($request);
    }

    public function save_extend($id) {
        $this->save_permission($id);
    }

    public function save_permission($id)
    {
        $prefixes = request()->input('prefixes');
        $all = request()->all();
        unset($all['_token']);
        unset($all['name']);
        unset($all['published']);
        unset($all['alias']);
        unset($all['shouldRedirect']);
        unset($all['prefixes']);
        unset($all['email']);
        unset($all['id']);
        unset($all['ordering']);

        $templateArray = $this->createTemplateArray($prefixes, 3);
        $resultArray = array_merge($templateArray, $all);
        foreach ($resultArray as $key => $item) {
            $lastDotPos = strrpos($key, '_');
            if(strpos(substr($key, 0, $lastDotPos), '_') !== false) {
                $type[0] = str_replace('_', '.', substr($key, 0, $lastDotPos));
            } else {
                $type[0] = substr($key, 0, $lastDotPos);
            }
            $type[1] = substr($key, $lastDotPos + 1);

            $permission = '';

            if ($type[1] == 1) {
                $permission = 'view';
            }
            if ($type[1] == 2) {
                $permission = 'edit';
            }
            if ($type[1] == 3) {
                $permission = 'delete';
            }
            $rolePermission = RolePermissionsModel::where('role_id', $id)->where('route', $type[0] . '.' . $permission)->first();

            if (!$rolePermission) {
                $rolePermission = new RolePermissionsModel();
            }
            $rolePermission->role_id = $id;
            $rolePermission->route = $type[0] . '.' . $permission;
            $rolePermission->permission = $item;
            $rolePermission->save();
        }
    }


    function createTemplateArray($prefixes, $count)
    {
        $templateArray = [];
        foreach ($prefixes as $prefix) {
            for ($i = 1; $i <= $count; $i++) {
                $templateArray["{$prefix}_{$i}"] = "0";
            }
        }

        return $templateArray;
    }
}
