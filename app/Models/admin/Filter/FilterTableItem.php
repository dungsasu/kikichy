<?php

namespace App\Models\admin\Filter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterTableItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function table()
    {
        return $this->belongsTo(FilterTable::class, 'filter_table_id');
    }

    public function category()
    {
        return $this->belongsTo(FilterCategory::class, 'filter_category_id');
    }
}
