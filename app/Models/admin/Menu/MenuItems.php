<?php

namespace App\Models\admin\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{
    use HasFactory;

    protected $table = 'menu_items';

    public function group()
    {
        return $this->belongsTo(MenuGroups::class, 'group_id', 'id');
    }
}
