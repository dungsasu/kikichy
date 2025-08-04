<?php

namespace App\Models\admin\Banner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    public function category()
    {
        return $this->belongsTo(BannerCategory::class, 'category_id', 'id');
    }
}
