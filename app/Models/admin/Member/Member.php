<?php

namespace App\Models\admin\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Members\GalleryMembers as GalleryMembers;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Model implements Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'members';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'ma_kh',
        'ma_the',
        'hang_the',
        'diem_tich_luy',
        'diem_thuong',
        'coupon_len_hang',
        'diem_len_hang',
        'ty_le_len_hang',
        'diem_con_lai',
        'ma_coupon_tang',
        'so_diem_tang',
        'qua_tang_khac'
    ];

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function galleryImages()
    {
        return $this->hasMany(GalleryMembers::class, 'id_nv', 'id');
    }

    public function contactInfo()
    {
        return $this->hasOne(MemberContactInfo::class, 'user_id', 'id');
    }

    public function orderInfo()
    {
        return $this->hasOne(MemberOrder::class, 'user_id', 'id');
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }
}
