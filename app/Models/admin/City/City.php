<?php

namespace App\Models\admin\City;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class City extends Model
{
    use HasFactory;
    
    protected $table = 'cities';
    
    protected $fillable = [
        'name',
        'code',
        'alias',
        'country_id',
        'description',
        'published',
        'ordering'
    ];

    // Set default values
    protected $attributes = [
        'published' => 1,
        'ordering' => 0
    ];

    public function country()
    {
        return $this->belongsTo(\App\Models\admin\Country\Country::class, 'country_id', 'id');
    }

    // Check if ordering column exists
    public static function getMaxOrdering()
    {
        if (Schema::hasColumn((new static)->getTable(), 'ordering')) {
            return static::max('ordering') ?? 0;
        }
        return 0;
    }
}
