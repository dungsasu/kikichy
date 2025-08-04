<?php

namespace App\Services\Payment;

interface PaymentStrategy
{
    public function pay($order);
}