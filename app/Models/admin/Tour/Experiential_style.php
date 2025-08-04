<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experiential_style extends Model
{
    use HasFactory;

    protected $table = 'tour_experiential_style';
    protected $guarded = ['id'];
}
