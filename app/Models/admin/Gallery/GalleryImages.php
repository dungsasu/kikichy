<?php

namespace App\Models\admin\Gallery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImages extends Model
{
    use HasFactory;

    protected $table = 'gallery_images';
    protected $fillable = ['gallery_id', 'url', 'type', 'ordering', 'published', 'href'];
}
