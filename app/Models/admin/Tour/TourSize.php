<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourSize extends Model
{
    use HasFactory;
    
    protected $table = 'tour_sizes';
    protected $fillable = [
        'tour_id',
        'size_name',
        'size_value',
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
