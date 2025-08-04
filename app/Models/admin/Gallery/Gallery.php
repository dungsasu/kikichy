<?php

namespace App\Models\admin\Gallery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    public function images() {
        return $this->hasMany(GalleryImages::class, 'gallery_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(GalleryCategories::class, 'category_id', 'id');
    }
}
