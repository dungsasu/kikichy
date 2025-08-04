<?php

namespace App\Models\admin\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Content\Content as ContentModel;

class ContentCategories extends Model
{
    use HasFactory;

    public function contents() {
        return $this->hasMany(ContentModel::class, 'category_id', 'id');
    }
}
