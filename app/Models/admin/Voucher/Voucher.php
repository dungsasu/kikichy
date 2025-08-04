<?php

namespace App\Models\admin\Voucher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    public function getTable()
    {
        $year = Carbon::now()->year;
        return 'vouchers_' . $year;
    }

    protected $fillable = [
        'code',
        'discount',
        'name',
        'type',
        'date_start',
        'date_expiration',
        'status',
        'minuspoint',
        'customer',
        'ad_ckc_yn',
        'ad_ckvip_yn',
        'ad_cktang_yn',
        'ad_ckcombo_yn',
        'offline',
        'used',
        'itemgroup1'
    ];
}
