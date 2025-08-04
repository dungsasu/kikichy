<?php

namespace App\Models\admin\Payoo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPayoo extends Model
{
    use HasFactory;

    protected $table = 'log_payoo';

    protected $fillable = ['request', 'response', 'created_at'];

}
