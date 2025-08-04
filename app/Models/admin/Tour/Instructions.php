<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructions extends Model
{
    use HasFactory;

    protected $table = 'tour_instructions';
    protected $guarded = ['id'];
}
