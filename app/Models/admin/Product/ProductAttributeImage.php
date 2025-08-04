<?php

namespace App\Models\admin\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeImage extends Model
{
    use HasFactory;
    protected $table = 'product_attribute_images';
    protected $guarded = ['id'];

}
