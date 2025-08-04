<?php

namespace App\Models\admin\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product\Product as ProductModel;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';

    protected $fillable = [
        'name',
        'order_id',
        'price_old',
        'price',
        'fast_code',
        'quantity',
        'created_at',
        'options',
        'product_id',
        'total'
    ];
    
    public function product() {
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }
}
