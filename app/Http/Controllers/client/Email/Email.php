<?php

namespace App\Http\Controllers\client\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Order\Order;

class Email extends Controller
{
    public function customerOrderPreview()
    {
        $data = Order::find(21);

        return view('emails.customer.order-confirmation', [
            'data' => [
                'order' => $data
            ]
        ]);
    }

    public function adminOrderPreview()
    {
        $data = Order::find(92);
     
        return view('emails.admin.order-new', [
            'data' => [
                'order' => $data
            ]
        ]);
    }
}
