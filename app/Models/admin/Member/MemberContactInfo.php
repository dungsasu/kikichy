<?php

namespace App\Models\admin\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberContactInfo extends Model
{
    use HasFactory;

    protected $table = 'members_info_contact';

    protected $fillable = [
        'user_id',
        'country_id', 
        'city_id',
        'address',
        'phone',
        'website',
        'youtube',
        'facebook',
        'instagram',
        'twitter',
        'logo'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\admin\Country\Country::class, 'country_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\admin\City\City::class, 'city_id', 'id');
    }
}
