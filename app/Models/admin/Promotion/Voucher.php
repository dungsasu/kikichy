<?php

namespace App\Models\admin\Promotion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product\Product;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'promotion_vouchers';
    protected $guarded = ['id'];

    public function getProductsAttribute($value)
    {
        return Product::whereIn('id', explode(',', $value))->get();
    }
}
