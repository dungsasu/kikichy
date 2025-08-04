<?php

namespace App\Models\admin\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product\Product as ProductModel;

class ProductCampaign extends Model
{
    use HasFactory;
    protected $table = 'products_campaign';

    protected $fillable = ['product_code', 'campaign_id', 'product_id'];

    public function product() {
        return $this->hasOne(ProductModel::class, 'code', 'product_code');
    }
}
