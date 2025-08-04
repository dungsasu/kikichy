<?php

namespace App\Models\admin\VnUnits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $table = 'provinces';

    protected $fillable = [
        'code', 'name', 'name_en', 'full_name', 'full_name_en', 'code_name', 'administrative_unit_id', 'administrative_region_id'
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'code');
    }
}
