<?php

namespace App\Services;

use Jackiedo\Cart\Cart;
use App\Models\admin\Product\Product as ProductModel;
use App\Traits\CommonFunctionTrait;

class CartService
{
    use CommonFunctionTrait;
    public $cart;

    public function __construct(Cart $cart)
    {
        $cart->name('default');
        $this->cart = $cart;

        $this->addShipping();
    }

    public function getDetails()
    {
        return $this->cart->getDetails();
    }

    public function addCartItem($item)
    {
        $this->cart->addItem($item);
    }

    public function getCartItems()
    {
        return $this->cart->getDetails()->get('items');
    }

    public function getTotal()
    {
        return $this->format_money($this->cart->getItemsSubtotal());
    }

    public function getQuantity()
    {
        $items = $this->cart->getDetails()->get('items'); 
        $quantity = 0;

        foreach ($items as $item) {
            $quantity += $item->quantity;
        }

        return $quantity;
    }

    public function clearCart()
    {
        $this->cart->clearItems();
    }

    public function clearActions()
    {
        $this->cart->clearActions();
    }

    public function removeCartItem($hash)
    {
        $this->cart->removeItem($hash);
    }

    public function addShipping()
    {
        return $this->cart->applyAction([
            'group' => 'Shipping',
            'id'    => 1,
            'title' => 'Shipping cost',
            'value' => 0
        ]);
    }

    public function getDataCart()
    {
        $data = [
            'cartItems' => $this->getCartItems(),
            'total' => $this->getTotal(),
            'quantity' => $this->getQuantity(),
            'sumAmount' => format_money($this->cart->sumActionsAmount()),
            'shipping' => format_money($this->getShippingAmount()),
            'subTotal' => format_money($this->cart->getSubtotal()),
            'voucher' => format_money($this->getVoucherAmount()),
            'voucherCode' => $this->getVoucher() ? $this->getVoucher()->getExtraInfo('code') : null,
        ];
       
        return  $data;
    }

    public function getSubtotal()
    {
        return $this->cart->getSubtotal();
    }

    public function sumActionsAmount()
    {
        return $this->cart->sumActionsAmount();
    }

    public function updateCartItems($hash, $data)
    {
        $this->cart->updateItem($hash, $data);
        // return $this->cart->getDetails()->get('items');
    }

    public function applyVoucher($voucher)
    {
        return $this->cart->applyAction([
            'group' => 'Voucher',
            'id'    => $voucher->id,
            'title' => $voucher->name,
            'value' => $voucher->price ? -$voucher->price : -$voucher->percent."%",
            'extra_info' => [
                'code' => $voucher->code,
            ]
        ]);
    }

    public function removeAction($actionHash, $withEvent = true)
    {
        $this->cart->removeAction($actionHash, $withEvent);
    }

    public function getCartProductDetails($cartItems)
    {
        $productDetails = [];
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $product = ProductModel::where('id', $item['id'])
                    ->with([
                        'attributes' => function ($query) use ($item) {
                            $query->where('id', $item['options']['id']);
                        }
                    ])
                    ->first();

                if ($product) {
                    $productDetails[] = (object)[
                        'image' => @$item['options']['image'] ? $item['options']['image'] : $product->image,
                        'price_old' => $product->attributes[0]->price_old,
                        'price_old_format' => $product->attributes[0]->price_old_format,

                        'price_public' => $product->attributes[0]->price_public,
                        'price_public_format' => $product->attributes[0]->price_public_format,

                        'quantity' => $item['quantity'],
                        'href' => $product->href,
                        'name' => $product->name,
                        'attribute_name' => $product->attributes[0]->name,
                        'hash' => $item['hash'],
                        // 'colors' => $product->colors,
                        // 'sizes' => $product->sizes,
                    ];
                }
            }
        }

        return $productDetails;
    }

    public function clearVoucher()
    {
        return $this->getVoucher() ? $this->cart->removeAction($this->getVoucher()->getHash()) : null;
    }
    public function getVoucher()
    {
        $action = $this->cart->getActions(['group' => 'Voucher']);
        return reset($action);
    }
    public function getVoucherAmount()
    {
        return $this->getVoucher() ? $this->getVoucher()->getAmount() : 0;
    }

    public function getShippingAmount()
    {
        return $this->getShipping() ? $this->getShipping()->getAmount() : 0;
    }
    public function getShipping()
    {
        $action = $this->cart->getActions(['group' => 'Shipping']);
        return reset($action);
    }

    public function getDiscountAmount()
    {
        return $this->getVoucherAmount();
    }
}
