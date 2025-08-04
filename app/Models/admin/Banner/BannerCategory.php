<?php

namespace App\Models\admin\Banner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerCategory extends Model
{
    use HasFactory;

    protected $table = 'banner_categories';

    public function banners()
    {
        return $this->hasMany(Banner::class, 'category_id', 'id');
    }
}
