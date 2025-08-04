<?php

namespace App\Models\admin\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTourRelated extends Model
{
    use HasFactory;

    protected $table = 'news_tour_related';
    protected $guarded = ['id'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
