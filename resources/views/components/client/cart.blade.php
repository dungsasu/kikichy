@if (count($cartProductDetail) > 0)
    @foreach ($cartProductDetail as $cartItem)
        <x-client.cart-item :item="$cartItem" />
    @endforeach
@else
    <div class="text-center">
        Bạn chưa có sản phẩm nào trong giỏ hàng!
    </div>
@endif