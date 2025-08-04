<?php

namespace App\Models\admin\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Province\Province as ProvincesModel;


class Store extends Model
{
    use HasFactory;

    public function province()
    {
        return $this->belongsTo(ProvincesModel::class, 'province_id', 'code');
    }
}