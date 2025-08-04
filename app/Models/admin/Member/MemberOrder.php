<?php

namespace App\Models\admin\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberOrder extends Model
{
    use HasFactory;

    protected $table = 'members_orders';

    protected $fillable = [
        'user_id',
        'logo',
        'order_description',
        'order_summary_business',
        'min_age',
        'max_age',
        'avg_age',
        'min_group_size',
        'max_group_size',
        'avg_group_size',
        'private_min_size',
    ];

    /**
     * Quan hệ với Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id', 'id');
    }
}
