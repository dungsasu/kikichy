<?php

namespace App\Models\admin\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Country extends Model
{
    use HasFactory;
    
    protected $table = 'country';
    
    protected $fillable = [
        'name',
        'code',
        'alias',
        'description',
        'published',
        'ordering'
    ];

    // Set default values
    protected $attributes = [
        'published' => 1,
        'ordering' => 0
    ];

    public function cities()
    {
        return $this->hasMany(\App\Models\admin\City\City::class, 'country_id', 'id');
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
