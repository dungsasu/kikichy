<?php

namespace App\Models\admin\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsProductRelated extends Model
{
    use HasFactory;

    protected $table = 'news_product_related';
    protected $guarded = ['id'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
