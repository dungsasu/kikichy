<?php

namespace App\Models\admin\Destination;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Destination\DestinationCategories as DestinationCategories;

class Destination extends Model
{
    use HasFactory;


    public function category() {
        return $this->belongsTo(DestinationCategories::class, 'category_id', 'id');
    }

 
}
