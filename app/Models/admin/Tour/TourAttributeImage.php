<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourAttributeImage extends Model
{
    use HasFactory;
    protected $table = 'tour_attribute_images';
    protected $guarded = ['id'];
}
