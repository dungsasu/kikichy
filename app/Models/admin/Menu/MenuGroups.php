<?php

namespace App\Models\admin\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Menu\MenuItems;

class MenuGroups extends Model
{
    use HasFactory;

    protected $table = 'menu_groups';

    public function children() {
        return $this->hasMany(MenuItems::class, 'group_id', 'id');
    }
}
