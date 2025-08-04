<?php

namespace App\Models\admin\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Tour\Tour;
use App\Models\admin\Comment\TourCommentImage;

class TourComment extends Model
{
    use HasFactory;
    protected $table = 'tour_comments';
    protected $guarded = ['id'];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(TourComment::class, 'parent_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(TourCommentImage::class, 'tour_comment_id', 'id');
    }
}
