@if ($prod->inventory > 0)
    @if (count($availableSizes) > 0)
        <div class="ps-4" style="width: 100%">
            <button class="btn-add-to-cart" data-product-id="{{ $prod->id }}" data-product-price="{{ $prod->price }}"
                data-product-name="{{ $prod->name }}">
                @translate('Thêm vào giỏ hàng')
            </button>
        </div>
    @else
        <div class="ps-4">
            <button class="btn-add-to-cart-out-of-stock" data-product-id="{{ $prod->id }}"
                data-product-price="{{ $prod->price }}" data-product-name="{{ $prod->name }}">
                @translate('HẾT HÀNG')
            </button>
        </div>
    @endif
@endif
