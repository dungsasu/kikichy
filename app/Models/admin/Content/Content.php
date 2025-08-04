<?php

namespace App\Models\admin\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(ContentCategories::class, 'category_id');
    }
}
