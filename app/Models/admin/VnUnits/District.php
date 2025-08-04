<?php

namespace App\Models\admin\VnUnits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';

    protected $fillable = [
        'code', 'name', 'name_en', 'full_name', 'full_name_en', 'code_name', 'province_code', 'administrative_unit_id'
    ];

    public function wards()
    {
        return $this->hasMany(Ward::class, 'district_code', 'code');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }
}
