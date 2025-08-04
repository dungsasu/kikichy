<?php

namespace App\Models\admin\Filter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterCategory extends Model
{
    use HasFactory;

    protected $appends = ['group'];

    public function group()
    {
        return $this->belongsTo(FilterGroup::class, 'group_id');
    }

    public function filters()
    {
        return $this->hasMany(Filter::class, 'filter_category_id');
    }
}
