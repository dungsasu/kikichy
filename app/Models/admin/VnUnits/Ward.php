<?php

namespace App\Models\admin\VnUnits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    protected $table = 'wards';

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

}
