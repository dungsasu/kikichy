<?php

namespace App\Models\admin\Recruitment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;

    protected $table = 'recruitments';

    public function province() {
        return $this->hasOne('App\Models\admin\Province\Province', 'code', 'province_id');
    }

    public function department() {
        return $this->hasOne('App\Models\admin\Department\Department', 'id', 'department_id');
    }
}
