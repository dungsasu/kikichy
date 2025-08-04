<?php

namespace App\Models\admin\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product\Product;
use App\Models\admin\Comment\ProductCommentImage;

class ProductComment extends Model
{
    use HasFactory;
    protected $table = 'product_comments';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'parent_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductCommentImage::class, 'product_comment_id', 'id');
    }
}
