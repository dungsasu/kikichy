<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourTypes extends Model
{
    use HasFactory;

    protected $table = 'tour_types';
    protected $guarded = ['id'];
}
