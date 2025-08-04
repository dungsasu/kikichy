<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourRelated extends Model
{
    use HasFactory;
    protected $table = 'tours_related';
    protected $guarded = ['id'];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
