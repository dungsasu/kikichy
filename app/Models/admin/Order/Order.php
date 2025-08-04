<?php

namespace App\Models\admin\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Order\OrderItem;
use App\Models\admin\Member\Member as MemberModel;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'fast_id',
        'fast_items',
        'user_fast',
        'name',
        'phone',
        'email',
        'address',
        'payment_method',
        'note',
        'total_shipping',
        'total',
        'total_price',
        'created_at',
        'order_status',
        'type',
        'province_id',
        'district_id',
        'ward_id',
        'voucher_code',
        'member_id'
    ];
    
    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(MemberModel::class, 'member_id', 'id');
    }
}
