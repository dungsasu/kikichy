<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourColor extends Model
{
    use HasFactory;
    
    protected $table = 'tour_colors';
    protected $fillable = [
        'tour_id',
        'color_name',
        'color_code',
        'ordering',
        'published',
        'created_at',
        'updated_at'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'id');
    }
}
