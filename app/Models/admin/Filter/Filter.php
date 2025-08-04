<?php

namespace App\Models\admin\Filter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;

    protected $appends = ['category'];

    public function category()
    {
        return $this->belongsTo(FilterCategory::class, 'category_id');
    }
}
