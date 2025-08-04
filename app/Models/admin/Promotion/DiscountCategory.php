<?php

namespace App\Models\admin\Promotion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product\Product;

class DiscountCategory extends Model
{
    use HasFactory;

    protected $table = 'promotion_discount_categories';

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(Discount::class, 'promotion_discount_category_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_discounts', 'promotion_discount_category_id', 'product_id')->withPivot('price', 'percent', 'sold');
    }
}
