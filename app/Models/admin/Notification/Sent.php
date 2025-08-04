<?php

namespace App\Models\admin\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Notification\Notification as NotificationModel;

class Sent extends Model
{
    use HasFactory;
    protected $table = 'notification_sent';

    public function notifications() {
        return $this->belongsTo(NotificationModel::class, 'notification_id', 'id');
    }
}
