<?php

namespace App\Models\admin\Fashion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Fashion\FashionImage;

class Fashion extends Model
{
    use HasFactory;

    public function images() {
        return $this->hasMany(FashionImage::class, 'fashion_id', 'id');
    }
}
