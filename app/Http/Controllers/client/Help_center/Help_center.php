<?php

namespace App\Http\Controllers\client\Help_center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Help_center extends Controller
{
    public function index(){
        return view('client.help_center.index');
    }

    public function payment_policy(){
        return view('client.help_center.payment_policy');
    }
    public function return_policy(){
        return view('client.help_center.return_policy');
    }
    public function shipping_policy(){
        return view('client.help_center.shipping_policy');
    }
}