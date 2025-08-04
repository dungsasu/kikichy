<?php

namespace App\Models\admin\Members;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryMembers extends Model
{
    use HasFactory;
    protected $table = 'gallery_members';

    protected $fillable = [
        'id_nv', 'url', 'type', 'publish', 'category_name', 'category_id', 'id',
        'ordering'
    ];
}
