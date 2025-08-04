<?php

namespace App\Models\admin\Destination;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationCategories extends Model
{
    use HasFactory;
    protected $appends = ['href'];

    public function destination()
    {
        return $this->hasMany(Destination::class, 'category_id', 'id');
    }

    public function getHrefAttribute()
    {
        return route('client.destination-categories', [
            'alias' => $this->alias,
        ]);
    }
}
