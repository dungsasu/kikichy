<?php

namespace App\Models\admin\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Promotion\DiscountCategory as DiscountCategoryModel;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes';
    protected $fillable = [
        'created_at',
        'updated_at',
        'name',
        'code',
        'ordering',
        'published',
        'alias',
        'product_id',
        'price',
        'quantity',
        'price_old',
        'color_code'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductAttributeImage::class);
    }
}
