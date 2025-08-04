<?php

namespace App\Models\admin\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourCommentImage extends Model
{
    use HasFactory;
    protected $table = 'tour_comment_images';
    protected $guarded = ['id'];

    public function comment()
    {
        return $this->belongsTo(TourComment::class, 'tour_comment_id', 'id');
    }
}
