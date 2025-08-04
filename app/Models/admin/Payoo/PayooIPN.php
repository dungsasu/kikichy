<?php

namespace App\Models\admin\Payoo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayooIPN extends Model
{
    use HasFactory;

    protected $table = 'log_payoo_ipn';

    protected $fillable = ['request', 'response', 'created_at'];

}
