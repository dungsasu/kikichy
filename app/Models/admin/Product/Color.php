<?php

namespace App\Models\admin\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colors';

    public function getColorIdAttribute()
    {
        return $this->attributes['id'];
    }
}
