<?php

namespace App\Models\admin\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCommentImage extends Model
{
    use HasFactory;

    protected $table = 'product_comment_images';
    protected $guarded = ['id'];
}
