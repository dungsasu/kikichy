<?php

namespace App\Models\admin\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\admin\Role\Role as RoleModel;
use App\Models\admin\Role\RolePermission as RolePermissionModel;


class User extends Authenticatable
{
    use HasFactory;

    protected $table = "users";

    protected $fillable = [
        'username',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function roles()
    {
        return $this->hasOne(RoleModel::class, 'id', 'role_id');
    }
    public function rolePermission()
    {
        return $this->hasMany(RolePermissionModel::class, 'role_id', 'role_id');
    }
}
