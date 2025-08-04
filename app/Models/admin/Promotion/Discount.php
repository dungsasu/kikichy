<?php

namespace App\Models\admin\Promotion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'promotion_discounts';

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(DiscountCategory::class, 'category_id', 'id');
    }
}
