<?php

namespace App\Models\admin\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Role\RolePermission as RolePermissionsModel;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }
}
