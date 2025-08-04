<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Promotion\DiscountCategory as DiscountCategoryModel;

class TourAttribute extends Model
{
    use HasFactory;

    protected $table = 'tour_attributes';
    protected $fillable = [
        'created_at',
        'updated_at',
        'name',
        'code',
        'ordering',
        'published',
        'alias',
        'tour_id',
        'price',
        'quantity',
        'price_old',
        'color_code'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function images()
    {
        return $this->hasMany(TourAttributeImage::class);
    }
}
