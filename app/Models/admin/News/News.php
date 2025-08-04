<?php

namespace App\Models\admin\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\News\NewsCategories as NewsCategories;
use App\Models\admin\Product\Product;

class News extends Model
{
    use HasFactory;

    protected $appends = ['href'];

    public function category() {
        return $this->belongsTo(NewsCategories::class, 'category_id', 'id');
    }

    public function getImageAttribute($value) {
        $imageName = basename($value);
        $thumbImageName = 'thumb_' . $imageName;
        $thumbImagePath = str_replace($imageName, $thumbImageName, $value);

        
        return $thumbImagePath;
    }

    public function getHrefAttribute()
    {
        return route('client.news-detail', [
            'category' => $this->category->alias,
            'alias' => $this->alias,
        ]); 
    }

    public function products_related()
    {
        return $this->belongsToMany(Product::class, 'news_product_related', 'news_id', 'product_id');
    }
}
