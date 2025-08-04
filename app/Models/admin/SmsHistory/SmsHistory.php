<?php

namespace App\Models\admin\SmsHistory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsHistory extends Model
{
    use HasFactory;
    
    protected $table = 'sms_history';

    protected $fillable = [
        'phone',
        'telco',
        'type',
        'sender',
        'message',
        'request_id',
        'use_unicode',
        'msg_length',
        'mt_count',
        'account',
        'error_code',
        'error_message',
        'referent_id',
        'status',
        'response_data'
    ];

    protected $casts = [
        'response_data' => 'array',
        'type' => 'integer',
        'use_unicode' => 'boolean',
        'msg_length' => 'integer',
        'mt_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope để lọc theo trạng thái
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Scope để lọc theo loại SMS
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessor để format response_data
    public function getFormattedResponseDataAttribute()
    {
        if (is_array($this->response_data)) {
            return json_encode($this->response_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        return $this->response_data;
    }

    // Accessor để hiển thị trạng thái
    public function getStatusLabelAttribute()
    {
        return $this->status === 'success' ? 'Thành công' : 'Thất bại';
    }

    // Accessor để hiển thị loại SMS
    public function getTypeLabelAttribute()
    {
        $types = [
            1 => 'SMS Thông báo',
            2 => 'SMS Khuyến mãi',
            3 => 'SMS OTP',
            4 => 'SMS Chăm sóc khách hàng'
        ];
        return $types[$this->type] ?? 'Khác';
    }
}
