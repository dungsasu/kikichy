<?php 

namespace App\Services\Payment;
use App\Mail\Email;
use App\Services\OrderService;

class CodPayment implements PaymentStrategy
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function pay($order)
    {
        // Email::sendOrderConfirmationMail($order);
        // Email::sendAdminOrderMail($order);
        // $this->orderService->addOrder($order);

        return redirect(route('client.pay_success', ['data' => $order->id]));
    } 
}