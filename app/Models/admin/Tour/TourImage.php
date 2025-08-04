<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourImage extends Model
{
    use HasFactory;

    protected $table = 'tour_images';
    protected $fillable = [
        'tour_id', 'ordering', 'published', 'name', 'image', 'type', 'updated_at', 'created_at'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'id');
    }
}
