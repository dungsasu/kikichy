<?php

namespace App\Models\admin\Collection;

use App\Models\admin\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    
    public function products()
    {
        return $this->hasMany(Product::class, 'collection_id', 'id');
    }
}
