<?php

namespace App\Models\admin\Tour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourTranslate extends Model
{
    use HasFactory;
    
    protected $table = 'tour_translates';
    protected $fillable = [
        'record_id',
        'locale',
        'name',
        'alias',
        'summary',
        'description',
        'guide_management',
        'return_policy',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published',
        'created_at',
        'updated_at'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'record_id', 'id');
    }
}
