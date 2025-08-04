<?php

namespace App\Http\Controllers\admin\Cart;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class Cart extends BaseController
{
    public function __construct()
    {
        parent::__construct(Cart::class, 'admin.cart', 'cart');
    }
}
