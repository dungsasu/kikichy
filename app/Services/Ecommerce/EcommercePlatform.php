<?php
namespace App\Services\Ecommerce;

interface EcommercePlatform
{
    public function getToken();
    public function refreshAccessToken($token);
    public function checkExpireToken();
    public function getOrders();
    public function getOrderItems($order, $token);

}