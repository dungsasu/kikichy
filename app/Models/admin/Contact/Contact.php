<?php

namespace App\Models\admin\Contact;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'title',
        'content',
        'published',
        'ordering',
    ];
}