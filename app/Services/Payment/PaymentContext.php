<?php 

namespace App\Services\Payment;
use App\Services\Fast\FastService;

class PaymentContext
{
    private $paymentStrategy;

    public function __construct(PaymentStrategy $paymentStrategy)
    {
        $this->paymentStrategy = $paymentStrategy;
    }

    public function executePayment($order)
    {
        return $this->paymentStrategy->pay($order);
    }
}