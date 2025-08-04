<?php

namespace App\Models\admin\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColorOrdering extends Model
{
    protected $table = 'product_colors_ordering';
    use HasFactory;

    public function colors() {
        return $this->belongsTo(ProductColor::class, 'color_id', 'id');
    }
}
