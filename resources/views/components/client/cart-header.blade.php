@if (count($cartProductDetail) > 0)
    <div class="fw-semibold fs-5 mb-3">Giỏ hàng của bạn</div>
                                
    <div class="cart-scroll mb-3">
        <x-client.cart />
    </div>

    <div class="d-flex align-items-center gap-3 justify-content-between mb-3">
        <div class="fw-semibold fs-6 text-757575">Tạm tính</div>
        <div class="fw-semibold fs-5">{{ $cart['total'] }}</div>
    </div>

    <a href="{{ route('client.pay.index') }}" class="pay-btn">Đặt hàng</a>
@else
    <div class="text-center">
        Bạn chưa có sản phẩm nào trong giỏ hàng!
    </div>
@endif