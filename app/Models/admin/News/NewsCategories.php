<?php

namespace App\Models\admin\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategories extends Model
{
    use HasFactory;
    protected $appends = ['href'];

    public function news()
    {
        return $this->hasMany(News::class, 'category_id', 'id');
    }

    public function getHrefAttribute()
    {
        return route('client.news-categories', [
            'alias' => $this->alias,
        ]);
    }
}
