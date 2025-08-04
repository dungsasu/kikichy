<?php

namespace App\Models\admin\Filter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterTable extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany(FilterTableItem::class, 'filter_table_id');
    }
}
